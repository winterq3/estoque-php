<?php

namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use Src\Models\Movimentacao;

class MovimentacaoValidacaoTest extends TestCase
{
    public function test_rejeita_tipo_diferente_de_entrada_ou_saida(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Tipo deve ser 'entrada' ou 'saida'");

        Movimentacao::registrar([
            'produto_id' => 1,
            'tipo'       => 'transferencia',
            'quantidade' => 10,
        ]);
    }

    public function test_rejeita_quantidade_zero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Quantidade deve ser maior que zero');

        Movimentacao::registrar([
            'produto_id' => 1,
            'tipo'       => 'entrada',
            'quantidade' => 0,
        ]);
    }

    public function test_rejeita_quantidade_negativa(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Movimentacao::registrar([
            'produto_id' => 1,
            'tipo'       => 'saida',
            'quantidade' => -5,
        ]);
    }
}