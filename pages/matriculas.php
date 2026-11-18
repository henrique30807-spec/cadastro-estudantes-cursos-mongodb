<?php
use MongoDB\BSON\UTCDateTime;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'matricular') {
    try {
        $studentId = id($_POST['estudante_id'] ?? '');
        $courseId = id($_POST['curso_id'] ?? '');
        if (!$estudantes->findOne(['_id' => $studentId]) || !$cursos->findOne(['_id' => $courseId])) throw new InvalidArgumentException('Estudante ou curso não encontrado.');
        if ($matriculas->findOne(['estudante_id' => $studentId, 'curso_id' => $courseId])) throw new InvalidArgumentException('Este estudante já está matriculado neste curso.');
        $matriculas->insertOne(['estudante_id' => $studentId, 'curso_id' => $courseId, 'data_matricula' => new UTCDateTime(), 'status' => 'Ativa']);
        flash('Matrícula realizada com sucesso.');
    } catch (Throwable $ex) { flash($ex->getMessage(), 'danger'); }
    redirect('matriculas');
}
if (isset($_GET['cancelar'])) {
    try { $matriculas->deleteOne(['_id' => id($_GET['cancelar'])]); flash('Matrícula excluída.'); }
    catch (Throwable $ex) { flash($ex->getMessage(), 'danger'); }
    redirect('matriculas');
}
$listaEstudantes = $estudantes->find([], ['sort' => ['nome' => 1]])->toArray();
$listaCursos = $cursos->find([], ['sort' => ['nome' => 1]])->toArray();
$listaMatriculas = $matriculas->find([], ['sort' => ['_id' => -1]])->toArray();
?>
<h1 class="h3 mb-3">Matrículas</h1>
<div class="card p-4 mb-4"><h2 class="h5 mb-3">Matricular estudante em um curso</h2>
<?php if (!$listaEstudantes || !$listaCursos): ?><div class="alert alert-info mb-0">Cadastre pelo menos um estudante e um curso antes de realizar uma matrícula.</div><?php else: ?>
<form method="post" class="row g-3"><input type="hidden" name="acao" value="matricular"><div class="col-md-5"><label class="form-label">Estudante</label><select class="form-select" name="estudante_id" required><option value="">Selecione</option><?php foreach ($listaEstudantes as $s): ?><option value="<?= e((string)$s['_id']) ?>"><?= e($s['nome']) ?></option><?php endforeach; ?></select></div><div class="col-md-5"><label class="form-label">Curso</label><select class="form-select" name="curso_id" required><option value="">Selecione</option><?php foreach ($listaCursos as $c): ?><option value="<?= e((string)$c['_id']) ?>"><?= e($c['nome']) ?></option><?php endforeach; ?></select></div><div class="col-md-2 d-flex align-items-end"><button class="btn btn-primary w-100">Matricular</button></div></form>
<?php endif; ?></div>
<div class="card p-4"><h2 class="h5">Matrículas cadastradas</h2><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Estudante</th><th>Curso</th><th>Status</th><th>Ação</th></tr></thead><tbody>
<?php foreach ($listaMatriculas as $mat): $s = $estudantes->findOne(['_id' => $mat['estudante_id']]); $c = $cursos->findOne(['_id' => $mat['curso_id']]); ?><tr><td><?= e($s['nome'] ?? 'Estudante removido') ?></td><td><?= e($c['nome'] ?? 'Curso removido') ?></td><td><span class="badge text-bg-success"><?= e($mat['status']) ?></span></td><td><a class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir esta matrícula?')" href="index.php?page=matriculas&cancelar=<?= e((string)$mat['_id']) ?>">Excluir</a></td></tr><?php endforeach; ?>
<?php if (!$listaMatriculas): ?><tr><td colspan="4" class="text-center text-muted">Nenhuma matrícula cadastrada.</td></tr><?php endif; ?>
</tbody></table></div></div>

