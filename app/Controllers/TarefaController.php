<?php

namespace App\Controllers;

use App\Repositories\TarefaRepository;
use App\UseCase\{CriarTarefa, ListarTarefa, EditarTarefa, DeletarTarefa};

final class TarefaController
{
    /**
     * Injeta a conexão PDO no controller.
     * 
     * @param \PDO $pdo Conexão com o banco de dados.
     */
    public function __construct(private \PDO $pdo) {}

    /**
     * Cria e retorna uma instância do TarefaRepository
     * utilizando a conexão PDO injetada no controller.
     * 
     * @return TarefaRepository Instância do repositório de tarefas.
     */
    public function repo(): TarefaRepository
    {
        return new TarefaRepository($this->pdo);
    }

    /**
     * Valida o token CSRF enviado pelo formulário.
     * 
     * @return void Encerra a execução caso o token for inválido.
     */
    private function assertCsrf(): void
    {
        $token = $_POST['_csrf'] ?? '';

        if (!hash_equals($_SESSION['csrf'] ?? '', $token)) {
            http_response_code(403);
            
            exit('CSRF Inválido!');
        }
    }

    /**
     * Exibe listagem de tarefas.
     * 
     * @return void Renderiza a view com a lista de tarefas.
     */
    public function index(): void
    {
        $tarefas = (new ListarTarefa($this->repo()))->executar();
        $title = 'Tarefas';
        require __DIR__ . '/../Views/tarefas/listar.php';
    }

    /**
     * Cria uma nova tarefa.
     * 
     * - Exibe o formulário de criação (GET).
     * - Processa a submissão do formulário e salva no banco (POST).
     * 
     * @return void Redireciona após salvar ou renderiza a view com erros.
     */
    public function create(): void
    {
        $title = 'Nova Tarefa';
        $erro = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->assertCsrf();

            try {
                (new CriarTarefa($this->repo()))->executar(
                    $_POST['titulo'] ?? '',

                     (isset($_POST['descricao']) && trim((string)$_POST['descricao']) !== '')
                    ? trim((string)$_POST['descricao'])
                    : null,

                     (isset($_POST['categoria_id']) && $_POST['categoria_id'] !== '')
                    ? (int)$_POST['categoria_id']
                    : null

                );

                header('Location: /?r=tarefas.index');

                exit;
            } catch (\InvalidArgumentException $e) {
                $erro = $e->getMessage();
            }
        }

        $categorias = $this->repo()->listarCategorias();
        require __DIR__ . '/../Views/tarefas/criar.php';
    }

    /**
     * Mostra o formulário de edição de uma tarefa e processa a atualização.
     * 
     * Serve para exibir os dados da tarefa escolhida e salvar as alterações
     * feitas pelo usuário. Caso a tarefa não exista, retorna o erro 404.
     * 
     * @return void
     */
    public function edit(): void
    {
        $useCase = new EditarTarefa($this->repo());
        $title = 'Editar Tarefa';
        $erro = null;

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $this->assertCsrf();

            $ok = $useCase->atualizar
            (
                (int)($_POST['id'] ?? 0),

                $_POST['titulo'] ?? '',

                (isset($_POST['descricao']) && $_POST['descricao'] !== '')
                ? (string)$_POST['descricao']
                : null,

                (isset($_POST['categoria_id']) && $_POST['categoria_id'] !== '')
                ? (int)$_POST['categoria_id']
                : null
            );

            if($ok)
            {
                header('Location: /?r=tarefas.index');

                exit;
            }

            $erro = 'Título é obrigatório!';
        }

        $dados = $useCase->carregarFormulario((int)($_GET['id'] ?? 0));

        if(!$dados)
        {
            http_response_code(404);

            exit('Tarefa não encontrada!');
        }

        $tarefa = $dados['tarefa'] ?? [];
        $categorias = $dados['categorias'] ?? [];

        require __DIR__ . '/../Views/tarefas/editar.php';
    }
}
