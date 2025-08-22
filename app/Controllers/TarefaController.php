<?php

namespace App\Controllers;

use App\Repositories\TarefaRepository;
use App\UseCase\{CriarTarefa, ListarTarefa, EditarTarefa, DeletarTarefa};

final class TarefaController
{
    public function __construct(private \PDO $pdo) {}
}
