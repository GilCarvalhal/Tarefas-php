<?php require __DIR__ . '/../layout/header.php'; ?>

<h1 class="h4 mb-3 ms-1">Nova tarefa</h1>

<?php if (!empty($erro)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="post" class="vstack gap-3 ms-1 me-1" action="/?r=tarefas.create">
  <!-- CSRF -->
  <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">

  <div>
    <label class="form-label">Título</label>
    <input name="titulo" class="form-control" required
           value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>">
  </div>

  <div>
    <label class="form-label">Descrição</label>
    <textarea name="descricao" class="form-control"><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>
  </div>

  <div>
    <label class="form-label">Categoria (opcional)</label>
    <?php $oldCat = isset($_POST['categoria_id']) ? (string)$_POST['categoria_id'] : ''; ?>
    <select name="categoria_id" class="form-select">
      <option value="" <?= $oldCat === '' ? 'selected' : '' ?>>Sem categoria</option>
      <?php foreach ($categorias as $c): ?>
        <option value="<?= (int)$c['id'] ?>"
          <?= $oldCat !== '' && $oldCat === (string)$c['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($c['nome']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <button class="btn btn-primary">Salvar</button>
  <a class="btn btn-secondary" href="/?r=tarefas.index">Cancelar</a>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>
