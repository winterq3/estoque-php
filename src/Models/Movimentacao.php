<?php

namespace Src\Models;

use Src\Database\Connection;

class Movimentacao
{
    public static function listarPorProduto(int $produtoId): array
    {
        $pdo = Connection::get();
        $stmt = $pdo->prepare(
            "SELECT * FROM movimentacoes WHERE produto_id = :produto_id ORDER BY data_movimentacao DESC"
        );
        $stmt->execute(['produto_id' => $produtoId]);

        return $stmt->fetchAll();
    }

    public static function listarTodas(): array
    {
        $pdo = Connection::get();
        $sql = "SELECT m.*, p.nome AS produto_nome
                FROM movimentacoes m
                JOIN produtos p ON p.id = m.produto_id
                ORDER BY m.data_movimentacao DESC";
        return $pdo->query($sql)->fetchAll();
    }

    public static function buscarPorId(int $id): ?array 
    {
        $pdo = Connection::get();
        $stmt = $pdo->prepare("SELECT *FROM movimentacoes WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $movimentacao = $stmt->fetch();

        return $movimentacao ?: null;
    }

    public static function deletar(int $id): bool
    {
        $pdo = Connection::get();
        $stmt = $pdo->prepare("DELETE FROM movimentacoes WHERE id = :id");

        return $stmt->execute(['id' => $id]);
    }

    public static function filtrar(?string $dataInicio, ?string $dataFim, ?string $categoria, ?string $tipo): array
    {
        $pdo = Connection::get();
        $sql = "SELECT m.*, p.nome AS produto_nome, p.categoria AS produto_categoria, p.unidade_medida
                FROM movimentacoes m
                JOIN produtos p ON p.id = m.produto_id
                WHERE 1 = 1";
            $params = [];

            if ($dataInicio) {
                $sql .= " AND m.data_movimentacao >= :data_inicio";
                $params['data_inicio'] = $dataInicio . ' 00:00:00';
            }
            if ($dataFim) {
                $sql .= " AND m.data_movimentacao <= :data_fim";
                $params['data_fim'] = $dataFim . ' 23:59:59';
            }
            if ($categoria) {
                $sql .= " AND p.categoria = :categoria";
                $params['categoria'] = $categoria;
            }
            if ($tipo) {
                $sql .= " AND m.tipo = :tipo";
                $params['tipo'] = $tipo;
            }

            $sql .= " ORDER BY m.data_movimentacao DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll();
    }

    public static function registrar(array $dados): int
    {
        if (!in_array($dados['tipo'], ['entrada', 'saida'], true)) {
            throw new \InvalidArgumentException("Tipo deve ser 'entrada' ou 'saida'");
        }

        if($dados['quantidade'] <= 0) {
            throw new \InvalidArgumentException('Quantidade deve ser maior que zero');
        }

        if($dados['tipo'] === 'saida') {
            $saldo = Produto::saldoAtual($dados['produto_id']);
            
            if ($dados['quantidade'] > $saldo) {
                throw new \InvalidArgumentException("Saldo insuficiente. Disponível : {$saldo}");
            }
        }

        $pdo = Connection::get();
        $sql = "INSERT INTO movimentacoes (produto_id, tipo, quantidade, responsavel)
            VALUES (:produto_id, :tipo, :quantidade, :responsavel)"; 
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'produto_id'  => $dados['produto_id'],
            'tipo'        => $dados['tipo'],
            'quantidade'  => $dados['quantidade'],
            'responsavel' => $dados['responsavel'] ?? null,
        ]);

        return (int) $pdo->lastInsertId();
    }
}