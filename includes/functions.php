<?php
use MongoDB\BSON\ObjectId;

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function id(string $value): ObjectId
{
    if (!preg_match('/^[a-f0-9]{24}$/i', $value)) {
        throw new InvalidArgumentException('Identificador inválido.');
    }
    return new ObjectId($value);
}

function redirect(string $page): never
{
    header('Location: index.php?page=' . urlencode($page));
    exit;
}

function flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

