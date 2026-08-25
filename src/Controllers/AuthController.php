<?php

namespace Src\Controllers;

use Src\Models\Usuario;

class AuthController
{
    public function register(): void
    {
        $dados = json_decode(file_get_contents('php://input'), true);
        header('Content-Type: application/json; charset=utf-8');
        if (empty($dados['nome']) || empty($dados['email']) || empty($dados['senha'])) {
            http_response_code(422);
            echo json_encode(['erro' => 'Nome, e-mail e senha são obrigatórios.']);
            return;
        }

        if (strlen($dados['senha']) < 6) {
            http_response_code(422);
            echo json_encode(['erro' => 'A senha deve ter pelo menos 6 caracteres.']);
            return;
        }

        try {
            $id = Usuario::cadastrar($dados);
            http_response_code(201);
            echo json_encode(['id' => $id, 'mensagem' => 'Usuário cadastrado com sucesso.']);
        } catch (\RuntimeException $e) {
            http_response_code(409);
            echo json_encode(['erro' => $e->getMessage()]);
        }
    }

    public function login(): void
    {
        $dados = json_decode(file_get_contents('php://input'), true);
        header('Content-Type: application/json; charset=utf-8');
        if (empty($dados['email']) || empty($dados['senha'])) {
            http_response_code(422);
            echo json_encode(['erro' => 'E-mail e senha são obrigatórios.']);
            return;
        }

        $usuario = Usuario::verificarLogin($dados['email'], $dados['senha']);
        if (!$usuario) {
            http_response_code(401);
            echo json_encode(['erro' => 'E-mail ou senha inválidos.']);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];

        echo json_encode(['mensagem' => 'Login bem-sucedido.', 'usuario' => $usuario]);
    }

    public function logout(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $_SESSION = [];
        session_destroy();
        echo json_encode(['mensagem' => 'Logout bem-sucedido.']);
    }

    public function me(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        if (empty($_SESSION['usuario_id'])) {
            http_response_code(401);
            echo json_encode(['erro' => 'Usuário não autenticado.']);
            return;
        }

        echo json_encode([
            'id' => $_SESSION['usuario_id'],
            'nome' => $_SESSION['usuario_nome']
        ]);
    }
}