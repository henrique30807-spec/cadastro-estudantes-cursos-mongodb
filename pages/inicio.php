<?php
$totalEstudantes = $estudantes->countDocuments();
$totalCursos = $cursos->countDocuments();
$totalMatriculas = $matriculas->countDocuments();
?>
<div class="mb-4">
    <h1 class="h3">Sistema de Cadastro Acadêmico</h1>
    <p class="text-muted">Gerencie estudantes, cursos e matrículas em um só lugar.</p>
</div>
<div class="row g-3">
    <div class="col-md-4"><div class="card p-4"><div class="text-muted">Estudantes</div><div class="display-6 fw-semibold"><?= $totalEstudantes ?></div><a href="index.php?page=estudantes">Acessar cadastro</a></div></div>
    <div class="col-md-4"><div class="card p-4"><div class="text-muted">Cursos</div><div class="display-6 fw-semibold"><?= $totalCursos ?></div><a href="index.php?page=cursos">Acessar cadastro</a></div></div>
    <div class="col-md-4"><div class="card p-4"><div class="text-muted">Matrículas</div><div class="display-6 fw-semibold"><?= $totalMatriculas ?></div><a href="index.php?page=matriculas">Acessar matrículas</a></div></div>
</div>
<div class="card p-4 mt-4"><h2 class="h5">Sobre o projeto</h2><p class="mb-0">Os dados dos estudantes, cursos e matrículas são armazenados em coleções separadas no MongoDB. A coleção de matrículas utiliza os identificadores dos estudantes e dos cursos para representar o relacionamento muitos-para-muitos.</p></div>

