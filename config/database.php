<?php
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    throw new RuntimeException('Dependências não instaladas. Execute composer install no terminal do projeto.');
}
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use MongoDB\Client;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$uri = $_ENV['MONGODB_URI'] ?? getenv('MONGODB_URI') ?: '';
$databaseName = $_ENV['MONGODB_DATABASE'] ?? getenv('MONGODB_DATABASE') ?: 'unipar_academico';

if ($uri === '') {
    throw new RuntimeException('Configure MONGODB_URI no arquivo .env antes de iniciar o sistema.');
}

$client = new Client($uri);
$db = $client->selectDatabase($databaseName);

$estudantes = $db->estudantes;
$cursos = $db->cursos;
$matriculas = $db->matriculas;

// Índices preservam regras de negócio mesmo se os dados forem alterados fora da interface.
$estudantes->createIndex(['cpf' => 1], ['unique' => true]);
$estudantes->createIndex(['registro_academico' => 1], ['unique' => true]);
$cursos->createIndex(['codigo' => 1], ['unique' => true]);
$matriculas->createIndex(['estudante_id' => 1, 'curso_id' => 1], ['unique' => true]);

