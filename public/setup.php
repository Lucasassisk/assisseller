<?php
// ATENÇÃO: Delete este arquivo após o uso!
if (!isset($_GET['key']) || $_GET['key'] !== 'assisseller2026') {
    die('Acesso negado.');
}

$root = dirname(__DIR__);
echo "<pre style='font-family:monospace;font-size:13px;padding:20px'>";

// === MIGRATE via Laravel bootstrap ===
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

// === STORAGE LINK ===
echo "\n=== STORAGE LINK ===\n";
$target = $root . '/storage/app/public';
$link   = __DIR__ . '/storage';
if (is_link($link)) {
    echo "Symlink já existe.\n";
} elseif (@symlink($target, $link)) {
    echo "Symlink criado com sucesso.\n";
} else {
    // Fallback: pasta real
    if (!is_dir($link)) {
        mkdir($link, 0775, true);
    }
    // Copia arquivos existentes
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

echo "\n=== CONCLUÍDO ===\n";
echo "DELETE este arquivo agora pelo gerenciador de arquivos!\n";
echo "</pre>";
