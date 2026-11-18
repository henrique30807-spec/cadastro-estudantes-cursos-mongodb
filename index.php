<?php
session_start();
require_once __DIR__ . '/includes/functions.php';

try {
    require_once __DIR__ . '/config/database.php';
} catch (Throwable $exception) {
    http_response_code(500);
    $pageTitle = 'Configuração necessária';
    require __DIR__ . '/includes/header.php';
    echo '<div class="alert alert-warning"><h4>Banco de dados não conectado</h4><p>' . e($exception->getMessage()) . '</p><p>Consulte o README.md para configurar o MongoDB Atlas.</p></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$page = $_GET['page'] ?? 'inicio';
$allowedPages = ['inicio', 'estudantes', 'cursos', 'matriculas'];
if (!in_array($page, $allowedPages, true)) $page = 'inicio';

$pageTitle = match ($page) {
    'estudantes' => 'Estudantes',
    'cursos' => 'Cursos',
    'matriculas' => 'Matrículas',
    default => 'Início',
};

require __DIR__ . '/includes/header.php';
if ($page === 'estudantes') require __DIR__ . '/pages/estudantes.php';
elseif ($page === 'cursos') require __DIR__ . '/pages/cursos.php';
elseif ($page === 'matriculas') require __DIR__ . '/pages/matriculas.php';
else require __DIR__ . '/pages/inicio.php';
require __DIR__ . '/includes/footer.php';

