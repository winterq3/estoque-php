<?php

namespace Src\Controllers;

use Src\Models\Fornecedor;

class FornecedorController
{
    public function index(): void
    {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode(Fornecedor::listarTodos());
    }

    public function show(int $id): void
    {
        $fornecedor = Fornecedor::buscarPorId($id);
        header('Content-type: application/json; charset=utf-8');
        if (!$fornecedor) {
            http_response_code(404);
            echo json_encode(['erro' => 'Fornecedor não encontrado']);
            return;
        }
        echo json_encode($fornecedor);
    }

    public function store(): void
    {
        $dados = json_decode(file_get_contents('php://input'), true);
        header('Content-type: application/json; charset=utf-8');
        if (empty($dados['nome'])) {
            http_response_code(422);
            echo json_encode(['erro' => 'Nome é obrigatório']);
            return;
        }
        $id = Fornecedor::cadastrar($dados);
        http_response_code(201);
        echo json_encode(['id' => $id, 'mensagem' => 'Fornecedor cadastrado com sucesso']);
    }

    public function update(int $id): void
    {
        $dados = json_decode(file_get_contents('php://input'), true);
        header('Content-type: application/json; charset=utf-8');
        if (!Fornecedor::buscarPorId($id)) {
            http_response_code(404);
            echo json_encode(['erro' => 'Fornecedor não encontrado']);
            return;
        }
        Fornecedor::atualizar($id, $dados);
        echo json_encode(['mensagem' => 'Fornecedor atualizado com sucesso']);
    }

    public function destroy(int $id): void
    {
        header('Content-type: application/json; charset=utf-8');
        if (!Fornecedor::buscarPorId($id)) {
            http_response_code(404);
            echo json_encode(['erro' => 'Fornecedor não encontrado']);
            return;
        }
        Fornecedor::deletar($id);
        echo json_encode(['mensagem' => 'Fornecedor removido com sucesso']);
    }
}