<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Repositories\TarefaRepository;

final class ListarTarefa
{
    /**
     * Injetado via construtor para garantir desacoplamento da camada
     * de infraestrutura e facilitar testes.
     */
    public function __construct(private TarefaRepository $repo) {}

    /**
     * Executa a listagem de tarefas com suas categorias associadas.
     * 
     * @return \App\Entities\Tarefa[] Coleção de objetos Tarefa.
     */
    public function executar(): array
    {
        return $this->repo->listarComCategoria();
    }
}
