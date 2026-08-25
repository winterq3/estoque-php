<?php

session_start();

require __DIR__ . '/../src/Database/Connection.php';
require __DIR__ . '/../src/Models/Produto.php';
require __DIR__ . '/../src/Models/Movimentacao.php';
require __DIR__ . '/../src/Models/Fornecedor.php';
require __DIR__ . '/../src/Models/Usuario.php';
require __DIR__ . '/../src/Controllers/ProdutoController.php';
require __DIR__ . '/../src/Controllers/MovimentacaoController.php';
require __DIR__ . '/../src/Controllers/FornecedorController.php';
require __DIR__ . '/../src/Controllers/AuthController.php';
require __DIR__ . '/../src/Controllers/RelatorioController.php';

use Src\Controllers\ProdutoController;
use Src\Controllers\MovimentacaoController;
use Src\Controllers\FornecedorController;
use Src\Controllers\AuthController;
use Src\Controllers\RelatorioController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/estoque/public', '', $uri);
$uri = trim($uri, '/');
$partes = explode('/', $uri);

$metodo = $_SERVER['REQUEST_METHOD'];
$recurso = $partes[0] ?? '';
$id = isset($partes[1]) && is_numeric($partes[1]) ? (int) $partes[1] : null;
$acao = $partes[1] ?? null;

$rotasPublicas = ['login', 'register'];

if ($recurso === 'auth') {
    $authController = new AuthController();

    if ($acao === 'login' && $metodo === 'POST') {
        $authController->login();
    } elseif ($acao === 'register' && $metodo === 'POST') {
        $authController->register();
    } elseif ($acao === 'logout' && $metodo === 'POST') {
        $authController->logout();
    } elseif ($acao === 'me' && $metodo === 'GET') {
        $authController->me();
    } else {
        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['erro' => 'Rota não encontrada']);
    }
    exit;
}

if (empty($_SESSION['usuario_id'])) {
    http_response_code(401);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['erro' => 'Não autorizado. Faça login para acessar este recurso.']);
    exit;
}

$controller = new ProdutoController();

if ($recurso === 'produtos') {
    if($acao === 'estoque-baixo' && $metodo === 'GET') {
        $controller->estoqueBaixo();
    } elseif ($metodo === 'GET' && $id !== null) {
        $controller->show($id);
    } elseif ($metodo === 'GET') {
        $controller->index();
    } elseif ($metodo === 'POST') {
        $controller->store();
    } elseif ($metodo === 'PUT' && $id !== null) {
        $controller->update($id);
    } elseif ($metodo === 'DELETE' && $id !== null) {
        $controller->destroy($id);
    } else {
        http_response_code(405);
        echo json_encode(['erro' => 'Método não permitido']);
    }
} elseif ($recurso === 'movimentacoes') {
    $movController = new MovimentacaoController();

    if ($metodo === 'GET' && $id !== null) {
        $movController->porProduto($id);
    } elseif ($metodo === 'GET') {
        $movController->index();
    } elseif ($metodo === 'POST') {
        $movController->store();
    } elseif ($metodo === 'DELETE' && $id !== null) {
        $movController->destroy($id);
    } else {
        http_response_code(405);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['erro' => 'Método não permitido']);
    }

} elseif($recurso === 'fornecedores') {
    $fornecedorController = new FornecedorController();

    if ($metodo === 'GET' && $id !== null) {
        $fornecedorController->show($id);
    } elseif ($metodo === 'GET') {
        $fornecedorController->index();
    } elseif ($metodo === 'POST') {
        $fornecedorController->store();
    } elseif ($metodo === 'PUT' && $id !== null) {
        $fornecedorController->update($id);
    } elseif ($metodo === 'DELETE' && $id !== null) {
        $fornecedorController->destroy($id);
    } else {
        http_response_code(405);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['erro' => 'Método não permitido']);
    }
} elseif ($recurso === 'relatorios') {
    $relController = new RelatorioController();

    if ($acao === 'exportar-csv' && $metodo === 'GET') {
        $relController->exportarCsv();
    } elseif ($acao === 'movimentacoes' && $metodo ==='GET') {
        $relController->movimentacoes();
    } else {
        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['erro' => 'Rota não encontrada']);
    }
} else {
    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['erro' => 'Rota não encontrada']);
}
