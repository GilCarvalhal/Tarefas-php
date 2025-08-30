<?php

$controller = new \App\Controllers\TarefaController(
    \App\Config\Database::getConnection() // Injeta o PDO
);

$ROUTES =
    [
        'tarefas.index' => [$controller, 'index'],
        'tarefas.create' => [$controller, 'create'],
        'tarefas.edit' => [$controller, 'edit'],
        'tarefas.confirmDelete' => [$controller, 'confirmDelete'],
        'tarefas.delete' => [$controller, 'delete'],
        'tarefas.show' => [$controller, 'show']
    ];
