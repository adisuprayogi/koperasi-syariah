<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;

// Test basic PDF generation
echo "Testing PDF generation...\n";

try {
    $data = [
        'title' => 'Panduan Pengguna Aplikasi Koperasi Syariah',
        'version' => '1.0.0',
        'date' => \Carbon\Carbon::now()->format('d F Y'),
        'screenshots' => [
            'login' => [
                'title' => 'Halaman Login',
                'description' => 'Halaman login untuk masuk ke aplikasi Koperasi Syariah',
                'path' => 'login-page.png'
            ]
        ],
        'sections' => [
            ['title' => 'Test Section 1'],
            ['title' => 'Test Section 2']
        ]
    ];

    echo "Data prepared successfully\n";
    echo "PDF generation test completed successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}