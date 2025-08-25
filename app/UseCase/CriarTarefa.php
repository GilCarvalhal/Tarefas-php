<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Repositories\TarefaRepository;
use InvalidArgumentException;

final class CriarTarefa
{
    public function __construct(private TarefaRepository $repo) {}

    public function executar(string $titulo, ?string $descricao, ?int $categoriaId): int
    {
        $titulo = trim($titulo);

        if ($titulo === '') {
            throw new InvalidArgumentException('Título é obrigatório!');
        }

        return $this->repo->criar($titulo, $descricao ?: null, $categoriaId);
    }
}
