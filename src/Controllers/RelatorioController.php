<?php
namespace Src\Controllers;

use Src\Models\Movimentacao;

class RelatorioController
{
    public function movimentacoes(): void
    {
        $filtros = $this->lerFiltros();
        $itens = Movimentacao::filtrar(...$filtros);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'total_entradas' => $this->somar($itens, 'entrada'),
            'total_saidas' => $this->somar($itens, 'saida'),
            'quantidade_itens' => count($itens),
            'movimentacoes' => $itens,
        ]);
    }

    private function somar(array $itens, ?string $tipo): int
    {
        return array_sum(array_map(
            fn($m) => $m['tipo'] === $tipo ? (int) $m ['quantidade'] : 0,
            $itens
        ));
    }

    public function exportarCsv(): void
    {
        [$dataInicio, $dataFim, $categoria, $tipo] = $this->lerFiltros();
        $itens = Movimentacao::filtrar($dataInicio, $dataFim, $categoria, $tipo);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="relatorio-movimentacoes.csv"');

        $saida = fopen('php://output', 'w');

        fwrite($saida, "\xEF\xBB\xBF");

        fputcsv($saida, ['Data', 'Produto', 'Categoria', 'Tipo', 'Quantidade', 'Unidade', 'Responsavel'], ';');

        foreach ($itens as $m) {
            fputcsv($saida, [
                $m['data_movimentacao'],
                $m['produto_nome'],
                $m['produto_categoria'] ?? '-',
                $m['tipo'] === 'entrada' ? 'Entrada' : 'Saída',
                $m['quantidade'],
                $m['unidade_medida'],
                $m['responsavel'] ?? '-',
            ], ';');
        }

        fclose($saida);
    }

    private function lerFiltros(): array
    {
        return [
            $_GET['inicio'] ?? null,
            $_GET['fim'] ?? null,
            $_GET['categoria'] ?? null,
            $_GET['tipo'] ?? null,
        ];
    }
}
