<?php

namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use Src\Database\Connection;
use Src\Models\Produto;
use Src\Models\Movimentacao;

class ProdutoSaldoTest extends TestCase
{
    private \PDO $pdo;
    private int $produtoId;

    protected function setUp(): void
    {
        $this->pdo = Connection::get();
        $this->pdo->beginTransaction();

        $stmt = $this->pdo->prepare(
            "INSERT INTO produtos (nome, unidade_medida, quantidade_minima) VALUES ('Produto Teste PHPUnit', 'un', 10)"
        );
        $stmt->execute();
        $this->produtoId = (int) $this->pdo->lastInsertId();
    }

    protected function tearDown(): void
    {
        $this->pdo->rollBack();
    }

    public function test_saldo_comeca_zerado_sem_movimentacoes(): void
    {
        $this->assertSame(0, Produto::saldoAtual($this->produtoId));
    }

    public function test_saldo_soma_entradas(): void
    {
        Movimentacao::registrar(['produto_id' => $this->produtoId, 'tipo' => 'entrada', 'quantidade' => 50]);
        Movimentacao::registrar(['produto_id' => $this->produtoId, 'tipo' => 'entrada', 'quantidade' => 30]);

        $this->assertSame(80, Produto::saldoAtual($this->produtoId));
    }

    public function test_saldo_subtrai_saidas(): void
    {
        Movimentacao::registrar(['produto_id' => $this->produtoId, 'tipo' => 'entrada', 'quantidade' => 100]);
        Movimentacao::registrar(['produto_id' => $this->produtoId, 'tipo' => 'saida', 'quantidade' => 40]);

        $this->assertSame(60, Produto::saldoAtual($this->produtoId));
    }

    public function test_rejeita_saida_maior_que_saldo_disponivel(): void
    {
        Movimentacao::registrar(['produto_id' => $this->produtoId, 'tipo' => 'entrada', 'quantidade' => 20]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Saldo insuficiente');

        Movimentacao::registrar(['produto_id' => $this->produtoId, 'tipo' => 'saida', 'quantidade' => 21]);
    }

    public function test_produto_aparece_em_estoque_baixo_quando_saldo_menor_que_minimo(): void
    {
        Movimentacao::registrar(['produto_id' => $this->produtoId, 'tipo' => 'entrada', 'quantidade' => 5]);

        $criticos = Produto::comEstoqueBaixo();
        $ids = array_column($criticos, 'id');

        $this->assertContains($this->produtoId, $ids);
    }

    public function test_produto_nao_aparece_em_estoque_baixo_quando_saldo_maior_que_minimo(): void
    {
        Movimentacao::registrar(['produto_id' => $this->produtoId, 'tipo' => 'entrada', 'quantidade' => 15]);

        $criticos = Produto::comEstoqueBaixo();
        $ids = array_column($criticos, 'id');

        $this->assertNotContains($this->produtoId, $ids);
    }
}