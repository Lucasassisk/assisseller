<?php
// ATENÇÃO: Delete este arquivo após o uso!
if (!isset($_GET['key']) || $_GET['key'] !== 'assisseller2026') {
    die('Acesso negado.');
}

// Em produção (Hostinger): raiz do Laravel é ../assisseller/
// Em desenvolvimento local: raiz do Laravel é ../
$root = is_dir(dirname(__DIR__) . '/assisseller')
    ? dirname(__DIR__) . '/assisseller'
    : dirname(__DIR__);

echo "<pre style='font-family:monospace;font-size:13px;padding:20px'>";
echo "Raiz detectada: $root\n\n";

// === MIGRATE ===
echo "=== MIGRATE ===\n";
try {
    define('LARAVEL_START', microtime(true));
    require $root . '/vendor/autoload.php';
    $app = require_once $root . '/bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $status = $kernel->call('migrate', ['--force' => true]);
    echo "Migrations concluídas. Status: $status\n";
} catch (\Throwable $e) {
    echo "ERRO migrate: " . $e->getMessage() . "\n";
}

// === SEED (usuário admin) ===
echo "\n=== SEED (usuário admin) ===\n";
try {
    $status = $kernel->call('db:seed', ['--force' => true]);
    echo "Seed concluído. Status: $status\n";
} catch (\Throwable $e) {
    echo "ERRO seed: " . $e->getMessage() . "\n";
}

// === STORAGE LINK ===
echo "\n=== STORAGE LINK ===\n";
$target = $root . '/storage/app/public';
$link   = __DIR__ . '/storage';
if (is_link($link)) {
    echo "Symlink já existe.\n";
} elseif (@symlink($target, $link)) {
    echo "Symlink criado com sucesso.\n";
} else {
    if (!is_dir($link)) {
        mkdir($link, 0775, true);
    }
    echo "Symlink falhou — pasta /public/storage criada como fallback.\n";
}

// === CONFIG CACHE ===
echo "\n=== CONFIG CACHE ===\n";
try {
    $kernel->call('config:cache');
    echo "Config cache OK.\n";
} catch (\Throwable $e) {
    echo "ERRO config:cache: " . $e->getMessage() . "\n";
}

// === ROUTE CACHE ===
echo "\n=== ROUTE CACHE ===\n";
try {
    $kernel->call('route:cache');
    echo "Route cache OK.\n";
} catch (\Throwable $e) {
    echo "ERRO route:cache: " . $e->getMessage() . "\n";
}

// === VIEW CACHE ===
echo "\n=== VIEW CACHE ===\n";
try {
    $kernel->call('view:cache');
    echo "View cache OK.\n";
} catch (\Throwable $e) {
    echo "ERRO view:cache: " . $e->getMessage() . "\n";
}

echo "\n=== CONCLUÍDO ===\n";
echo "DELETE este arquivo agora pelo gerenciador de arquivos da Hostinger!\n";
echo "</pre>";
