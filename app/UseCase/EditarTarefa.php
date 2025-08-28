<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Repositories\TarefaRepository;

final class EditarTarefa
{
    /**
     * Repositório responsável por consultar e atualizar tarefas.
     * 
     * Injetado via construtor para manter o caso de uso
     * desaclopado da camada de persistência e facilitar testes.
     * 
     * @param \App\Repositories\TarefaRepository $repo Instância do repositório de tarefas.
     */
    public function __construct(private TarefaRepository $repo) {}

    /**
     * Busca uma tarefa pelo ID e retorna seus dados em array.
     * 
     * Converte a entidade Tarefa vinda do repositório em array
     * para ser utilizada na camada de apresentação.
     * 
     * @param int $id ID da tarefa.
     * @return array|null Dados da tarefa ou null se não encontrada.
     */
    public function obter(int $id): ?array
    {
        $tarefa = $this->repo->obter($id);

        if ($tarefa === null)
            {
                return null;
            }

        return
            [
                'id' => $tarefa->id,
                'titulo' => $tarefa->titulo,
                'descricao' => $tarefa->descricao,
                'categoria_id' => $tarefa->categoriaId ?? null,
                'categoria_nome' => $tarefa->categoriaNome,
                'criada_em' => $tarefa->criadaEm
            ];
    }

    /**
     * Carrega os dados necessários para preencher o formulário de edição.
     * 
     * Retorna a tarefa e a lista de categorias para exibição na view.
     * 
     * @param int $id ID da tarefa.
     * @return array|null Array com 'tarefa' e 'categorias', ou null se a tarefa não existir.
     */
    public function carregarFormulario(int $id): ?array
    {
        $tarefa = $this->obter($id);

        if($tarefa === null)
        {
            return null;
        }

        $categorias = $this->repo->listarCategorias();

        return
            [
                'tarefa' => $tarefa,
                'categorias' => $categorias
            ];
    }

    /**
     * Atualiza os dados de uma tarefa existente.
     * 
     * Valida o título obrigatório, normaliza a descrição vazia para null,
     * e delega ao repositório a execução da atualização no banco.
     * 
     * @param int $id ID da tarefa a ser atualizada.
     * @param string $titulo Novo título da tarefa (obrigatório).
     * @param mixed $descricao Nova descrição (opcional).
     * @param mixed $categoriaId ID da categoria (opcional).
     * @return bool True em caso de sucesso, False se o título for inválido ou ocorrer falha.
     */
    public function atualizar(int $id, string $titulo, ?string $descricao, ?int $categoriaId): bool
    {
        $titulo = trim($titulo);

        if($titulo === '')
        {
            return false;
        }

        $descricao = ($descricao !== null && trim($descricao) === '') ? null : $descricao;

        return $this->repo->atualizar($id, $titulo, $descricao, $categoriaId);
    }
}
