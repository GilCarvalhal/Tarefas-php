<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">Tarefas</h1>
  <a href="/?r=tarefas.create" class="btn btn-success">+ Nova Tarefa</a>
</div>

<table class="table table-striped align-middle">
  <thead>
    <tr>
      <th>#</th><th>Título</th><th>Categoria</th><th>Criada em</th><th class="text-end">Ações</th>
    </tr>
  </thead>
  <tbody>
  <?php if (empty($tarefas)): ?>
    <tr>
      <td colspan="5" class="text-center text-muted">Nenhuma tarefa encontrada.</td>
    </tr>
  <?php else: ?>
    <?php foreach ($tarefas as $t): ?>
      <tr>
        <td><?= (int)$t->id ?></td>
        <td><?= htmlspecialchars($t->titulo) ?></td>
        <td><?= htmlspecialchars($t->categoriaNome ?? '—') ?></td>
        <td><?= htmlspecialchars($t->criadaEm ?? '') ?></td>
        <td class="text-end">
          <a class="btn btn-sm btn-primary" href="/?r=tarefas.edit&id=<?= (int)$t->id ?>">Editar</a>
          <a class="btn btn-sm btn-danger"  href="/?r=tarefas.confirmDelete&id=<?= (int)$t->id ?>">Excluir</a>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
  </tbody>
</table>

<?php require __DIR__ . '/../layout/footer.php'; ?>
