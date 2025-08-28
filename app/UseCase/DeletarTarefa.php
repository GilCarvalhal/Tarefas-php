<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Repositories\TarefaRepository;

final class DeletarTarefa
{
    /**
     * Inicializa o caso de uso de exclusão de tarefa.
     * 
     * @param \App\Repositories\TarefaRepository $repo Repositório responsável por executar a operação
     * de exclusão no banco de dados.
     */
    public function __construct(private TarefaRepository $repo)
    {
    }

    /**
     * Executa a exclusão de uma tarefa com base no ID informado.
     * 
     * @param int $id Identificador único da tarefa a ser excuída.
     * @return bool Retorna true se a exclusão foi realizada com sucesso, ou false
     * caso nenhuma linha tenha sido afetada (tarefa inexistente, por exemplo).
     */
    public function executar(int $id): bool
    {
        return $this->repo->deletar($id);
    }
}