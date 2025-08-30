<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0 ms-1"><?= htmlspecialchars($title ?? 'Detalhes da Tarefa')?></h1>
    <div class="d-flex gap-2 mt-1 me-1">
        <a href="/?r=tarefas.index" class="btn btn-outline-secondary">Voltar</a>
    </div>
</div>

<div class="card ms-1 me-1">
    <div class="card body">
        <h2 class="h5 mb-3 ms-1 me-1">
            <?=htmlspecialchars($tarefa['titulo'])?>
        </h2>

        <dl class="row mb-0">
            <dt class="col-sm-3 ms-1 me-1">
                Descrição
            </dt>
            <dd class="col-sm-9 ms-1 me-1">
                <?= !empty($tarefa['descricao']) 
                    ? nl2br(htmlspecialchars($tarefa['descricao']))
                    : '<span class="text-muted fst-italic">Sem descrição.</span>'
                ?>
            </dd>
        </dl>

        <dl class="row mb-0">
            <dt class="col-sm-3 ms-1 me-2">
                Categoria
            </dt>
            <dd class="col-sm-9 ms-1 me-1">
                <?= htmlspecialchars($tarefa['categoria_nome'] ?? '-') ?>    
            </dd>

            <dt class="col-sm-3 ms-1 me-1">
                Criada em
            </dt>
            <dd class="col-sm-9 ms-1 me-1">
                <?= htmlspecialchars($tarefa['criada_em'] ?? '') ?>
            </dd>
        </dl>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>