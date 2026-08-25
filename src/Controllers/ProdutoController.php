<?php

namespace Src\Controllers;

use Src\Models\Produto;

class ProdutoController
{
    public function index(): void
    {
        $produtos = Produto::listarTodos();
        
        foreach ($produtos as &$produto) {
            $produto['saldo_atual'] = Produto::saldoAtual($produto['id']);
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($produtos);
    }

    public function show(int $id): void
    {
        $produto = Produto::buscarPorId($id);
        
        header('Content-Type: application/json; charset=utf-8');
        
        if (!$produto) {
            http_response_code(404);
            echo json_encode(['erro' => 'Produto não encontrado']);
            return;
        }

        $produto['saldo_atual'] = Produto::saldoAtual($id);
        echo json_encode($produto);
    }

    public function store(): void
    {
        $dados = json_decode(file_get_contents('php://input'), true);
        
        header('Content-Type: application/json; charset=utf-8');
        
        if (empty($dados['nome']) || empty($dados['unidade_medida'])) {
            http_response_code(422);
            echo json_encode(['erro' => 'Nome e unidade de medida são obrigatórios']);
            return;
        }

        $id = Produto::cadastrar($dados);

        http_response_code(201);
        echo json_encode(['id' => $id, 'mensagem' => 'Produto cadastrado com sucesso']);
    }

    public function update(int $id): void
    {
        $dados = json_decode(file_get_contents('php://input'), true);
        
        header('Content-Type: application/json; charset=utf-8');
        
        if (!Produto::buscarPorId($id)) {
            http_response_code(404);
            echo json_encode(['erro' => 'Produto não encontrado']);
            return;
        }

        Produto::atualizar($id, $dados);
        echo json_encode(['mensagem' => 'Produto atualizado com sucesso']);
    }

    public function destroy(int $id): void
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if (!Produto::buscarPorId($id)) {
            http_response_code(404);
            echo json_encode(['erro' => 'Produto não encontrado']);
            return;
        }

        try {
            Produto::deletar($id);
            echo json_encode(['mensagem' => 'Produto removido com sucesso']);
        } catch (\RuntimeException $e) {
            http_response_code(409);
            echo json_encode(['erro' => $e->getMessage()]);
        }
    }

    public function estoqueBaixo(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(Produto::comEstoqueBaixo());
    }
}