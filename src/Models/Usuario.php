<?php

namespace Src\Models;

use Src\Database\Connection;

class Usuario {
    public static function buscarPorEmail(string $email): ?array
    {
        $pdo = Connection::get();
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public static function cadastrar(array $dados): int
    {
        if (self::buscarPorEmail($dados['email'])) {
            throw new \RuntimeException('Já existe um usuário cadastrado com este e-mail.');
        }

        $pdo = Connection::get();
        $sql = "INSERT INTO usuarios (nome, email, senha_hash)
                VALUES (:nome, :email, :senha_hash)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nome'       => $dados['nome'],
            'email'      => $dados['email'],
            'senha_hash' => password_hash($dados['senha'], PASSWORD_DEFAULT)
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function verificarLogin(string $email, string $senha): ?array
    {
        $usuario = self::buscarPorEmail($email);
        if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
            return null;
        }
        unset($usuario['senha_hash']);
        return $usuario;
    }
}