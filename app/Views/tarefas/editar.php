<?php require __DIR__ . '/../layout/header.php'; ?>

<h1 class="h4 mb-3 ms-1"><?= htmlspecialchars($title ?? 'Editar Tarefa') ?></h1>

<?php if (!empty($erro)): ?>
  <div class="alert alert-danger" role="alert" aria-live="assertive" id="form-error">
    <?= htmlspecialchars($erro) ?>
  </div>
<?php endif; ?>

<form method="post" class="vstack gap-3 ms-1 me-1" action="/?r=tarefas.edit" novalidate>
  <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">
  <input type="hidden" name="id" value="<?= (int)($tarefa['id'] ?? 0) ?>">

  <div>
    <label class="form-label" for="titulo">Título</label>
    <input
      id="titulo"
      name="titulo"
      class="form-control"
      required
      autofocus
      placeholder="Ex.: Comprar sementes"
      maxlength="120"
      autocomplete="off"
      <?php if (!empty($erro)): ?>aria-describedby="form-error"<?php endif; ?>
      value="<?= htmlspecialchars($_POST['titulo'] ?? ($tarefa['titulo'] ?? '')) ?>">
    <div class="form-text">Campo obrigatório (máx. 120 caracteres).</div>
  </div>

  <div>
    <label class="form-label" for="descricao">Descrição <span class="text-muted">(opcional)</span></label>
    <textarea
      id="descricao"
      name="descricao"
      class="form-control"
      rows="4"
      placeholder="Detalhes da tarefa…"
      maxlength="1000"
      <?php if (!empty($erro)): ?>aria-describedby="form-error"<?php endif; ?>
    ><?= htmlspecialchars($_POST['descricao'] ?? ($tarefa['descricao'] ?? '')) ?></textarea>
    <div class="form-text">Até 1000 caracteres.</div>
  </div>

  <?php
    $oldCat = isset($_POST['categoria_id'])
      ? (string)$_POST['categoria_id']
      : (isset($tarefa['categoria_id']) ? (string)$tarefa['categoria_id'] : '');
  ?>

  <div>
    <label class="form-label" for="categoria_id">Categoria <span class="text-muted">(opcional)</span></label>
    <select id="categoria_id" name="categoria_id" class="form-select" <?php if (!empty($erro)): ?>aria-describedby="form-error"<?php endif; ?>>
      <option value="">— sem categoria —</option>
      <?php foreach (($categorias ?? []) as $c): ?>
        <?php
          $id   = (string)($c['id'] ?? '');
          $nome = (string)($c['nome'] ?? '');
          $selected = ($oldCat !== '' && $oldCat === $id) ? 'selected' : '';
        ?>
        <option value="<?= htmlspecialchars($id) ?>" <?= $selected ?>>
          <?= htmlspecialchars($nome) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label class="form-label" for="criada_em">Criada em</label>
    <input id="criada_em" class="form-control" value="<?= htmlspecialchars($tarefa['criada_em'] ?? '') ?>" disabled>
  </div>

  <div class="d-flex gap-2">
    <button class="btn btn-primary" type="submit">Salvar</button>
    <a class="btn btn-outline-secondary" href="/?r=tarefas.index">Cancelar</a>
  </div>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>
