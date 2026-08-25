<?php

namespace Src\Models;

use Src\Database\Connection;

class Fornecedor
{
    public static function listarTodos(): array
    {
        $pdo = Connection::get();
        return $pdo->query("SELECT * FROM fornecedores ORDER BY nome")->fetchAll();
    }

    public static function buscarPorId(int $id): ?array
    {
        $pdo = Connection::get();
        $stmt = $pdo->prepare("SELECT * FROM fornecedores WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $fornecedor = $stmt->fetch();
        return $fornecedor ?: null;
    }

    public static function cadastrar(array $dados): int
    {
        $pdo = Connection::get();
        $sql = "INSERT INTO fornecedores (nome, telefone, email)
                Values(:nome, :telefone, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nome'     => $dados['nome'],
            'telefone' => $dados['telefone'] ?? null,
            'email'    => $dados['email'] ?? null,
        ]);
        return(int) $pdo->lastInsertId();
    }

    public static function atualizar(int $id, array $dados): bool
    {
        $pdo = Connection::get();
        $sql = "UPDATE fornecedores
                SET nome = :nome, telefone = :telefone, email = :email
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'id'       => $id,
            'nome'     => $dados['nome'],
            'telefone' => $dados['telefone'] ?? null,
            'email'    => $dados['email'] ?? null,
        ]);
    }

    public static function deletar(int $id): bool
    {
        $pdo = Connection::get();
        $stmt = $pdo->prepare("DELETE FROM fornecedores WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}