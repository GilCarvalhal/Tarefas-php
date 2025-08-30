<?php require __DIR__ . '/../layout/header.php'; ?>

<h1 class="h4 mb-3 ms-1">Confirmar exclusão</h1>

<div class="card mb-3 ms-1 me-1">
    <div class="card-body">
        <h5 class="card-title mb-2"><?=htmlspecialchars($tarefa['titulo'])?></h5>
        <p class="card-text"><?=htmlspecialchars($tarefa['descricao'] ?? '')?></p>
        <p class="text-muted small mb-0">
            Categoria: <?=htmlspecialchars($tarefa['categoria_nome'] ?? '')?>
            Criada em: <?=htmlspecialchars($tarefa['criada_em'] ?? '')?>
        </p>
    </div>
</div>

<form action="/?r=tarefas.delete" method="post" class="d-flex gap-2 ms-1">
    <input type="hidden" name="_csrf" value="<?=htmlspecialchars($_SESSION['csrf'] ?? '')?>">
    <input type="hidden" name="id" value="<?=(int)$tarefa['id']?>">
    <button class="btn btn-danger">
        Confirmar exclusão
    </button>
    <a href="/?r=tarefas.index" class="btn btn-secondary">Cancelar</a>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>