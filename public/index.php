<?php

declare(strict_types=1);

// Config / Infra
require_once __DIR__ . '/../app/Config/Database.php';

// Domínio e casos de uso
require_once __DIR__ . '/../app/Entities/Tarefa.php';
require_once __DIR__ . '/../app/Repositories/TarefaRepository.php';
require_once __DIR__ . '/../app/UseCase/CriarTarefa.php';
require_once __DIR__ .  '/../app/UseCase/EditarTarefa.php';
require_once __DIR__ . '/../app/UseCase/DeletarTarefa.php';
require_once __DIR__ . '/../app/UseCase/ListarTarefa.php';

// Controllers
require_once __DIR__ . '/../app/Controllers/TarefaController.php';

// Bootstrap
ini_set('display_errors', '1');
error_reporting(E_ALL);
if (session_status() !== PHP_SESSION_ACTIVE)
    session_start();
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));

// Rotas
$route = $_GET['r'] ?? 'tarefas.index';

if (!isset($ROUTES[$route])) {
    http_response_code(404);
    exit('Rota não encontrada');
}
[$obj, $method] = $ROUTES[$route];
$obj->$method();
