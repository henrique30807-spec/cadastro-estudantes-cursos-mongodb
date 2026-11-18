<?php
use MongoDB\BSON\UTCDateTime;

$edit = null;
if (isset($_GET['editar'])) $edit = $estudantes->findOne(['_id' => id($_GET['editar'])]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'salvar_estudante') {
    try {
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'cpf' => trim($_POST['cpf'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? ''),
            'data_nascimento' => trim($_POST['data_nascimento'] ?? ''),
            'registro_academico' => trim($_POST['registro_academico'] ?? ''),
            'endereco' => [
                'rua' => trim($_POST['rua'] ?? ''),
                'numero' => trim($_POST['numero'] ?? ''),
                'complemento' => trim($_POST['complemento'] ?? ''),
                'bairro' => trim($_POST['bairro'] ?? ''),
                'cidade' => trim($_POST['cidade'] ?? ''),
                'estado' => trim($_POST['estado'] ?? ''),
                'cep' => trim($_POST['cep'] ?? ''),
            ],
            'atualizado_em' => new UTCDateTime(),
        ];
        foreach (['nome', 'cpf', 'email', 'telefone', 'data_nascimento', 'registro_academico'] as $campo) {
            if ($dados[$campo] === '') throw new InvalidArgumentException('Preencha todos os campos obrigatórios.');
        }
        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) throw new InvalidArgumentException('Informe um e-mail válido.');
        foreach (['rua', 'numero', 'bairro', 'cidade', 'estado', 'cep'] as $campo) {
            if ($dados['endereco'][$campo] === '') throw new InvalidArgumentException('Preencha todos os campos obrigatórios do endereço.');
        }

        $existente = $estudantes->findOne(['cpf' => $dados['cpf']]);
        if ($existente && (!isset($_POST['id']) || (string)$existente['_id'] !== $_POST['id'])) throw new InvalidArgumentException('Já existe um estudante cadastrado com este CPF.');
        $registroExistente = $estudantes->findOne(['registro_academico' => $dados['registro_academico']]);
        if ($registroExistente && (!isset($_POST['id']) || (string)$registroExistente['_id'] !== $_POST['id'])) throw new InvalidArgumentException('Já existe um estudante cadastrado com este registro acadêmico.');

        if (!empty($_POST['id'])) $estudantes->updateOne(['_id' => id($_POST['id'])], ['$set' => $dados]);
        else { $dados['criado_em'] = new UTCDateTime(); $estudantes->insertOne($dados); }
        flash('Estudante salvo com sucesso.');
    } catch (Throwable $ex) { flash($ex->getMessage(), 'danger'); }
    redirect('estudantes');
}

if (isset($_GET['excluir'])) {
    try {
        $studentId = id($_GET['excluir']);
        $estudantes->deleteOne(['_id' => $studentId]);
        $matriculas->deleteMany(['estudante_id' => $studentId]);
        flash('Estudante e suas matrículas foram excluídos.');
    } catch (Throwable $ex) { flash($ex->getMessage(), 'danger'); }
    redirect('estudantes');
}

$lista = $estudantes->find([], ['sort' => ['nome' => 1]]);
$valor = fn($campo) => $edit[$campo] ?? '';
$endereco = $edit['endereco'] ?? [];
?>
<h1 class="h3 mb-3">Cadastro de estudantes</h1>
<div class="card p-4 mb-4">
    <h2 class="h5 mb-3"><?= $edit ? 'Editar estudante' : 'Novo estudante' ?></h2>
    <form method="post" class="row g-3">
        <input type="hidden" name="acao" value="salvar_estudante">
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= e((string)$edit['_id']) ?>"><?php endif; ?>
        <div class="col-md-6"><label class="form-label">Nome completo *</label><input class="form-control" name="nome" required value="<?= e($valor('nome')) ?>"></div>
        <div class="col-md-3"><label class="form-label">CPF *</label><input class="form-control" name="cpf" required value="<?= e($valor('cpf')) ?>"></div>
        <div class="col-md-3"><label class="form-label">Data de nascimento *</label><input type="date" class="form-control" name="data_nascimento" required value="<?= e($valor('data_nascimento')) ?>"></div>
        <div class="col-md-6"><label class="form-label">E-mail *</label><input type="email" class="form-control" name="email" required value="<?= e($valor('email')) ?>"></div>
        <div class="col-md-3"><label class="form-label">Telefone *</label><input class="form-control" name="telefone" required value="<?= e($valor('telefone')) ?>"></div>
        <div class="col-md-3"><label class="form-label">Registro acadêmico *</label><input class="form-control" name="registro_academico" required value="<?= e($valor('registro_academico')) ?>"></div>
        <div class="col-12"><hr><h3 class="h6">Endereço</h3></div>
        <div class="col-md-6"><label class="form-label">Rua *</label><input class="form-control" name="rua" required value="<?= e($endereco['rua'] ?? '') ?>"></div>
        <div class="col-md-2"><label class="form-label">Número *</label><input class="form-control" name="numero" required value="<?= e($endereco['numero'] ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">Complemento</label><input class="form-control" name="complemento" value="<?= e($endereco['complemento'] ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">Bairro *</label><input class="form-control" name="bairro" required value="<?= e($endereco['bairro'] ?? '') ?>"></div>
        <div class="col-md-5"><label class="form-label">Cidade *</label><input class="form-control" name="cidade" required value="<?= e($endereco['cidade'] ?? '') ?>"></div>
        <div class="col-md-3"><label class="form-label">Estado *</label><input class="form-control" name="estado" maxlength="2" required value="<?= e($endereco['estado'] ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">CEP *</label><input class="form-control" name="cep" required value="<?= e($endereco['cep'] ?? '') ?>"></div>
        <div class="col-12"><button class="btn btn-primary">Salvar estudante</button> <?php if ($edit): ?><a class="btn btn-outline-secondary" href="index.php?page=estudantes">Cancelar</a><?php endif; ?></div>
    </form>
</div>
<div class="card p-4"><h2 class="h5">Estudantes cadastrados</h2><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Nome</th><th>Registro</th><th>CPF</th><th>E-mail</th><th>Cidade</th><th>Ações</th></tr></thead><tbody>
<?php foreach ($lista as $item): ?><tr><td><?= e($item['nome']) ?></td><td><?= e($item['registro_academico'] ?? '') ?></td><td><?= e($item['cpf']) ?></td><td><?= e($item['email']) ?></td><td><?= e($item['endereco']['cidade'] ?? '') ?></td><td class="text-nowrap"><a class="btn btn-sm btn-outline-primary" href="index.php?page=estudantes&editar=<?= e((string)$item['_id']) ?>">Editar</a> <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir este estudante?')" href="index.php?page=estudantes&excluir=<?= e((string)$item['_id']) ?>">Excluir</a></td></tr><?php endforeach; ?>
<?php if ($estudantes->countDocuments() === 0): ?><tr><td colspan="6" class="text-center text-muted">Nenhum estudante cadastrado.</td></tr><?php endif; ?>
</tbody></table></div></div>

