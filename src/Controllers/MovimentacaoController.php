<?php

namespace Src\Controllers;

use Src\Models\Movimentacao;

class MovimentacaoController
{
    public function index(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(Movimentacao::listarTodas());
    }

    public function porProduto(int $produtoId): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(Movimentacao::listarPorProduto($produtoId));
    }
    
    public function store(): void
    {
        $dados = json_decode(file_get_contents('php://input'), true);

        header('Content-Type: application/json; charset=utf-8');

        if (empty($dados['produto_id']) || empty($dados['tipo']) || empty($dados['quantidade'])) {
            http_response_code(422);
            echo json_encode(['erro' => 'produto_id, tipo e quantidade são obrigatórios']);
            return;
        }

        try {
            $id = Movimentacao::registrar($dados);
            http_response_code(201);
            echo json_encode(['id' => $id, 'mensagem' => 'Movimentação registrada com sucesso']);
        } catch (\InvalidArgumentException $e) {
            http_response_code(422);
            echo json_encode(['erro' => $e->getMessage()]);
        }
    }

    public function destroy(int $id): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!Movimentacao::buscarPorId($id)) {
            http_response_code(404);
            echo json_encode(['erro' => 'Movimentação não encontrada']);
            return;
        }

        Movimentacao::deletar($id);
        echo json_encode(['mensagem' => 'Movimentação removida com sucesso']);
    }
}