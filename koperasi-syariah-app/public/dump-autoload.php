<?php
// Script untuk dump autoload di server tanpa terminal
// HAPUS FILE INI SETELAH SELESAI!

// Path ke aplikasi
$basePath = dirname(__DIR__);

// Cek secret key untuk keamanan
$secretKey = isset($_GET['key']) ? $_GET['key'] : '';
$validKey = 'kopsyah2025'; // Ganti dengan key yang aman

if ($secretKey !== $validKey) {
    die('Unauthorized: Invalid secret key. Use ?key=kopsyah2025');
}

echo "<h1>Dump Autoload</h1>";
echo "<pre>";

// Jalankan composer dump-autoload
$command = "cd {$basePath} && composer dump-autoload -o 2>&1";
$output = shell_exec($command);

echo "Running: {$command}\n\n";
echo $output;

echo "\n\nDone! <strong>HAPUS FILE INI SEGERA!</strong>";
echo "</pre>";
