<?php

namespace App\Repositories;

use PDO;
use App\Entities\Tarefa;

final class TarefaRepository
{
    /**
     * Injeta a conexão PDO no repositório.
     * 
     * @param PDO $pdo Conexão com o banco de dados.
     */
    public function __construct(private PDO $pdo) {}

    /**
     * Lista todas as categorias cadastradas.
     * 
     * @return array Retorna um array associativo com id e nome das categorias.
     */
    public function listarCategorias(): array
    {
        $sql = "SELECT id, nome FROM categorias ORDER BY nome";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lista todas as tarefas com suas respectivas categorias.
     * 
     *  @return Tarefa[] Retorna um array de objetos Tarefa
     */
    public function listarComCategoria(): array
    {
        $sql = "SELECT t.id, t.titulo, t.descricao, t.criada_em, c.nome AS categoria_nome
        FROM tarefas t
        LEFT JOIN categorias c ON c.id = t.categoria_id
        ORDER BY t.criada_em DESC, t.id DESC";

        $declaracao = $this->pdo->query($sql);

        $items = [];
        while ($response = $declaracao->fetch()) {
            $tarefa = new Tarefa();
            $tarefa->id = (int) $response['id'];
            $tarefa->titulo = $response['titulo'];
            $tarefa->descricao = $response['descricao'];
            $tarefa->categoriaNome = $response['categoria_nome'];
            $tarefa->criadaEm = $response['criada_em'];
            $items[] = $tarefa;
        }

        return $items;
    }

    /**
     * Cria uma nova tarefa no banco de dados.
     * 
     * @param string $titulo Título da tarefa.
     * @param mixed $descricao Descrição da tarefa (pode ser nula).
     * @param mixed $categoriaId ID da categoria associada (pode ser nulo).
     * @return int Retorna o ID gerado pela nova tarefa.
     */
    public function criar(string $titulo, ?string $descricao, ?int $categoriaId): int
    {
        $sql = "INSERT INTO tarefas (titulo, descricao, categoria_id) VALUES (:t, :d, :c)";

        $declaracao = $this->pdo->prepare($sql);

        $declaracao->bindValue(':t', $titulo);

        $descricao === null
            ? $declaracao->bindValue(':d', null, PDO::PARAM_NULL)
            : $declaracao->bindValue(':d', $descricao, PDO::PARAM_STR);

        $categoriaId === null
            ? $declaracao->bindValue(':c', null, PDO::PARAM_NULL)
            : $declaracao->bindValue(':c', $categoriaId, PDO::PARAM_INT);

        $declaracao->execute();

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Obtém uma tarefa pelo seu ID.
     * 
     * @param int $id ID da tarefa a ser buscada.
     * @return Tarefa|null Retorna a tarefa encontrada ou null se não existir.
     */
    public function obter(int $id): ?Tarefa
    {
        $declaracao = $this->pdo->prepare(
            "SELECT t.*, c.nome AS categoria_nome 
            FROM tarefas t 
            LEFT JOIN categorias c ON c.id = t.categoria_id 
            WHERE t.id = :id"
        );

        $declaracao->execute([':id' => $id]);

        $response = $declaracao->fetch();

        if (!$response) {
            return null;
        }

        $tarefa = new Tarefa();
        $tarefa->id = (int) $response['id'];
        $tarefa->titulo = $response['titulo'];
        $tarefa->descricao = $response['descricao'];
        $tarefa->categoriaId = $response['categoria_id'];
        $tarefa->categoriaNome = $response['categoria_nome'];
        $tarefa->criadaEm = $response['criada_em'];

        return $tarefa;
    }

    /**
     * Atualiza uma tarefa existente no banco de dados.
     * 
     * @param int $id ID da tarefa a ser atualizada.
     * @param string $titulo Novo título da tarefa.
     * @param mixed $descricao Nova descrição (pode ser nula).
     * @param mixed $categoriaId Novo ID de categoria (pode ser nulo).
     * @return bool Retorna true em caso de sucesso ou false em caso de falha.
     */
    public function atualizar(int $id, string $titulo, ?string $descricao, ?int $categoriaId): bool
    {
        $declaracao = $this->pdo->prepare(
            "UPDATE tarefas SET titulo=:t, descricao=:d, categoria_id=:c WHERE id=:id"
        );

        $declaracao->bindValue(':t', $titulo);

        $descricao === null
            ? $declaracao->bindValue(':d', null, PDO::PARAM_NULL)
            : $declaracao->bindValue(':d', $descricao, PDO::PARAM_STR);

        $categoriaId === null
            ? $declaracao->bindValue(':c', null, PDO::PARAM_NULL)
            : $declaracao->bindValue(':c', $categoriaId, PDO::PARAM_INT);

        $declaracao->bindValue(':id', $id, PDO::PARAM_INT);

        return $declaracao->execute();
    }

    /**
     * Exclui uma tarefa no banco de dados.
     * 
     * @param int $id ID da tarefa a ser excluída.
     * @return bool Retorna true em caso de sucesso ou false em caso de falha.
     */
    public function deletar(int $id): bool
    {
        $declaracao = $this->pdo->prepare("DELETE FROM tarefas WHERE id = :id");

        $declaracao->bindValue(':id', $id, PDO::PARAM_INT);

        return $declaracao->execute();
    }
}
