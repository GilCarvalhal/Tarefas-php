<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Repositories\TarefaRepository;

final class MostrarTarefa
{
    /**
     * Injeta o repositório de tarefas.
     * 
     * @param \App\Repositories\TarefaRepository $repo Repositório de persistência/consulta.
     */
    public function __construct(private TarefaRepository $repo) {}
    
    /**
     * Carrega a tarefa pelo ID e retorna os dados normalizados para a view.
     * 
     * Retorna 'null' quando a tarefa não é encontrada.
     * 
     * @param int $id ID da tarefa.
     * @return array{
     *   id:int,
     *   titulo:string|null,
     *   descricao:string|null,
     *   categoria_id:int|null,
     *   categoria_nome:string|null,
     *   criada_em:string
     * }|null
     */
    public function executar(int $id): ?array
    {
        $tarefa = $this->repo->obter($id);

        if($tarefa === null)
        {
            return null;
        }

        return
            [
                'id' => $tarefa->id,
                'titulo' => $tarefa->titulo,
                'descricao' => $tarefa->descricao,
                'categoria_id' => $tarefa->categoriaId,
                'categoria_nome' => $tarefa->categoriaNome,
                'criada_em' => $tarefa->criadaEm
            ];
    }
}

