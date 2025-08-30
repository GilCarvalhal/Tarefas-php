<?php

namespace App\Controllers;

use App\Repositories\TarefaRepository;
use App\UseCase\{CriarTarefa, ListarTarefa, EditarTarefa, DeletarTarefa, MostrarTarefa};

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

    /**
     * Exibe a tabela de confirmação de exclusão de uma tarefa.
     * 
     * - Busca a tarefa pelo ID informado via query string (?id=...).
     * - Caso não encontre a tarefa, retorna erro 404.
     * - Caso exista, renderiza a view de confirmação de exclusão.
     * 
     * @return void
     */
    public function confirmDelete(): void
    {
        $tarefa = (new EditarTarefa($this->repo()))->obter((int)($_GET['id'] ?? 0));


        if(!$tarefa)
        {
            http_response_code(404);

            exit('Tarefa não encontrada!');
        }

        $title = 'Confirmar exclusão';

        require __DIR__ . '/../Views/tarefas/deletar.php';
    }

    /**
     * Processa a exclusão de uma tarefa.
     * 
     * - Aceita apenas requisições POST.
     * - Valida o token CSRF antes de executar a operação.
     * - Converte o ID da tarefa recebido no formulário em inteiro.
     * - Chama o caso de uso DeletarTarefa para efetivar a exclusão.
     * - Redireciona o usuário de volta à listagem de tarefas.
     * 
     * @return never Encerra a execução após o redirecionamento.
     */
    public function delete(): void
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            http_response_code(405);

            exit('Método não permitido!');
        }

        $this->assertCsrf();

        $id = (int) ($_POST['id'] ?? 0);

        if($id)
        {
            (new DeletarTarefa($this->repo()))->executar($id);
        }

        header('Location: /?r=tarefas.index');

        exit;
    }

    /**
     * Exibe os detalhes de uma tarefa (somente leitura).
     * 
     * - Obtém o parâmetro 'id' via query string.
     * - Usa o caso de uso 'MostrarTarefa' para buscar os dados.
     * - Responde 404 se a tarefa não existir.
     * - Define '$title' e renderiza a view 'tarefas/mostrar.php'.
     * 
     * @return void
     */
    public function show()
    {
        $useCase = new MostrarTarefa($this->repo());

        $tarefa = $useCase->executar((int)($_GET['id'] ?? 0));

        if($tarefa === null)
        {
            http_response_code(404);

            exit('Tarefa não encontrada!');
        }

        $title = 'Detalhes da Tarefa';

        require __DIR__ . '/../Views/tarefas/mostrar.php';
    }
}
