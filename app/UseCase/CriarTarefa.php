<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Repositories\TarefaRepository;
use InvalidArgumentException;

final class CriarTarefa
{
    /**
     * Repositório responsável por persistir a Tarefa.
     * 
     * Injetado via construtor para garantir desacoplamento da camada
     * de infraestrutura e facilitar testes.
     * 
     * @param \App\Repositories\TarefaRepository $repo Instância concreta do repositório de tarefas.
     */
    public function __construct(private TarefaRepository $repo) {}

    /**
     * Executa o caso de uso de criação de tarefa.
     * 
     * Realiza validação do título e delega a operação
     * de gravação ao repositório.
     * 
     * @param string $titulo Título da tarefa (obrigatório).
     * @param string|null $descricao Descrição opcional da tarefa.
     * @param int|null $categoriaId ID da categoria opnional.
     * @throws InvalidArgumentException Se o título vier vazio após o trim.
     * @return int ID gerado para a nova tarefa.
     */
    public function executar(string $titulo, ?string $descricao, ?int $categoriaId): int
    {
        // trim() remove espaços em branco do início e do fim da string.
        $titulo = trim($titulo);

        if ($titulo === '') {
            throw new InvalidArgumentException('Título é obrigatório!');
        }

        return $this->repo->criar($titulo, $descricao ?: null, $categoriaId);
    }
}
