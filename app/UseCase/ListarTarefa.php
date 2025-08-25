<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Repositories\TarefaRepository;

final class ListarTarefa
{
    public function __construct(private TarefaRepository $repo) {}

    public function executar(): array
    {
        return $this->repo->ListarComCategoria();
    }
}
