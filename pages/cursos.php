<?php
use MongoDB\BSON\UTCDateTime;
$edit = null;
if (isset($_GET['editar'])) $edit = $cursos->findOne(['_id' => id($_GET['editar'])]);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'salvar_curso') {
    try {
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'codigo' => trim($_POST['codigo'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'carga_horaria' => (int)($_POST['carga_horaria'] ?? 0),
            'categoria' => trim($_POST['categoria'] ?? ''),
            'modalidade' => trim($_POST['modalidade'] ?? ''),
            'atualizado_em' => new UTCDateTime(),
        ];
        if ($dados['nome'] === '' || $dados['codigo'] === '' || $dados['descricao'] === '' || $dados['carga_horaria'] < 1 || $dados['categoria'] === '' || $dados['modalidade'] === '') throw new InvalidArgumentException('Preencha todos os campos corretamente.');
        $existente = $cursos->findOne(['codigo' => $dados['codigo']]);
        if ($existente && (!isset($_POST['id']) || (string)$existente['_id'] !== $_POST['id'])) throw new InvalidArgumentException('Já existe um curso cadastrado com este código.');
        if (!empty($_POST['id'])) $cursos->updateOne(['_id' => id($_POST['id'])], ['$set' => $dados]);
        else { $dados['criado_em'] = new UTCDateTime(); $cursos->insertOne($dados); }
        flash('Curso salvo com sucesso.');
    } catch (Throwable $ex) { flash($ex->getMessage(), 'danger'); }
    redirect('cursos');
}
if (isset($_GET['excluir'])) {
    try {
        $courseId = id($_GET['excluir']);
        $cursos->deleteOne(['_id' => $courseId]);
        $matriculas->deleteMany(['curso_id' => $courseId]);
        flash('Curso e suas matrículas foram excluídos.');
    } catch (Throwable $ex) { flash($ex->getMessage(), 'danger'); }
    redirect('cursos');
}
$lista = $cursos->find([], ['sort' => ['nome' => 1]]);
$valor = fn($campo) => $edit[$campo] ?? '';
?>
<h1 class="h3 mb-3">Cadastro de cursos</h1>
<div class="card p-4 mb-4"><h2 class="h5 mb-3"><?= $edit ? 'Editar curso' : 'Novo curso' ?></h2>
<form method="post" class="row g-3"><input type="hidden" name="acao" value="salvar_curso"><?php if ($edit): ?><input type="hidden" name="id" value="<?= e((string)$edit['_id']) ?>"><?php endif; ?>
<div class="col-md-6"><label class="form-label">Nome do curso *</label><input class="form-control" name="nome" required value="<?= e($valor('nome')) ?>"></div>
<div class="col-md-3"><label class="form-label">Código do curso *</label><input class="form-control" name="codigo" required value="<?= e($valor('codigo')) ?>"></div>
<div class="col-md-3"><label class="form-label">Carga horária (horas) *</label><input type="number" min="1" class="form-control" name="carga_horaria" required value="<?= e($valor('carga_horaria')) ?>"></div>
<div class="col-md-3"><label class="form-label">Categoria *</label><input class="form-control" name="categoria" required value="<?= e($valor('categoria')) ?>"></div>
<div class="col-md-4"><label class="form-label">Modalidade *</label><select class="form-select" name="modalidade" required><option value="">Selecione</option><?php foreach (['Presencial','Online','Híbrido'] as $m): ?><option <?= $valor('modalidade') === $m ? 'selected' : '' ?>><?= e($m) ?></option><?php endforeach; ?></select></div>
<div class="col-md-8"><label class="form-label">Descrição *</label><input class="form-control" name="descricao" required value="<?= e($valor('descricao')) ?>"></div>
<div class="col-12"><button class="btn btn-primary">Salvar curso</button> <?php if ($edit): ?><a class="btn btn-outline-secondary" href="index.php?page=cursos">Cancelar</a><?php endif; ?></div>
</form></div>
<div class="card p-4"><h2 class="h5">Cursos cadastrados</h2><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Curso</th><th>Código</th><th>Carga horária</th><th>Categoria</th><th>Modalidade</th><th>Ações</th></tr></thead><tbody>
<?php foreach ($lista as $item): ?><tr><td><?= e($item['nome']) ?><div class="small text-muted"><?= e($item['descricao']) ?></div></td><td><?= e($item['codigo'] ?? '') ?></td><td><?= e($item['carga_horaria']) ?>h</td><td><?= e($item['categoria']) ?></td><td><?= e($item['modalidade']) ?></td><td class="text-nowrap"><a class="btn btn-sm btn-outline-primary" href="index.php?page=cursos&editar=<?= e((string)$item['_id']) ?>">Editar</a> <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir este curso?')" href="index.php?page=cursos&excluir=<?= e((string)$item['_id']) ?>">Excluir</a></td></tr><?php endforeach; ?>
<?php if ($cursos->countDocuments() === 0): ?><tr><td colspan="6" class="text-center text-muted">Nenhum curso cadastrado.</td></tr><?php endif; ?>
</tbody></table></div></div>

