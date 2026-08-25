<?php

namespace Src\Models;

use Src\Database\Connection;
use PDO;

class Produto
{
    public static function listarTodos(): array
    {
        $pdo = Connection::get();
        $sql = "SELECT p.*, f.nome AS fornecedor_nome
                FROM produtos p
                LEFT JOIN fornecedores f ON f.id = p.fornecedor_id
                ORDER BY p.nome";

        return $pdo->query($sql)->fetchAll();
    }

    public static function buscarPorId(int $id): ?array
    {
        $pdo = Connection::get();
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $produto = $stmt->fetch();

        return $produto ?: null;
    }

    public static function cadastrar(array $dados): int
    {
        $pdo = Connection::get();
        $sql = "INSERT INTO produtos (nome, categoria, unidade_medida, quantidade_minima, fornecedor_id)
                VALUES (:nome, :categoria, :unidade_medida, :quantidade_minima, :fornecedor_id)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nome'              => $dados['nome'],
            'categoria'         => $dados['categoria'] ?? null,
            'unidade_medida'    => $dados['unidade_medida'],
            'quantidade_minima' => $dados['quantidade_minima'] ?? 0,
            'fornecedor_id'     => $dados['fornecedor_id'] ?? null,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function atualizar(int $id, array $dados): bool
    {
        $pdo = Connection::get();
        $sql = "UPDATE produtos
                SET nome = :nome,
                    categoria = :categoria,
                    unidade_medida = :unidade_medida,
                    quantidade_minima = :quantidade_minima,
                    fornecedor_id = :fornecedor_id
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id'                => $id,
            'nome'              => $dados['nome'],
            'categoria'         => $dados['categoria'] ?? null,
            'unidade_medida'    => $dados['unidade_medida'],
            'quantidade_minima' => $dados['quantidade_minima'] ?? 0,
            'fornecedor_id'     => $dados['fornecedor_id'] ?? null,
        ]);
    }

    public static function deletar(int $id): bool
    {
        $pdo = Connection::get();
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");

        try {
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new \RuntimeException(
                    'Não é possível excluir este produto porque ele já tem movimentações registradas.'
                );
            }
            throw $e;
        }
    }

    public static function saldoAtual(int $produtoId): int
    {
        $pdo = Connection::get();
        $sql = "SELECT
                    COALESCE(SUM(CASE WHEN tipo = 'entrada' THEN quantidade ELSE 0 END), 0) -
                    COALESCE(SUM(CASE WHEN tipo = 'saida' THEN quantidade ELSE 0 END), 0) AS saldo
                FROM movimentacoes
                WHERE produto_id = :produto_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['produto_id' => $produtoId]);

        return (int) $stmt->fetch()['saldo'];
    }

    public static function comEstoqueBaixo(): array
    {
        $produtos = self::listarTodos();
        $comEstoqueBaixo = [];

        foreach ($produtos as $produto) {
            $saldo = self::saldoAtual($produto['id']);
            if ($saldo < $produto['quantidade_minima']) {
                $produto['saldo_atual'] = $saldo;
                $comEstoqueBaixo[] = $produto;
            }
        }

        return $comEstoqueBaixo;
    }
}