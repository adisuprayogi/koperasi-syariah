<?php

/**
 * Script untuk membuat placeholder screenshots
 * Run dengan: php create_screenshots.php
 */

$screenshots = [
    'login-page' => [
        'title' => 'Halaman Login',
        'description' => 'Form login aplikasi Koperasi Syariah'
    ],
    'dashboard' => [
        'title' => 'Dashboard Anggota',
        'description' => 'Dashboard utama dengan informasi keuangan'
    ],
    'profile' => [
        'title' => 'Profil Pengguna',
        'description' => 'Halaman profil dan pengaturan akun'
    ],
    'simpanan' => [
        'title' => 'Data Simpanan',
        'description' => 'Tampilan kartu simpanan'
    ],
    'transaksi-simpanan' => [
        'title' => 'Transaksi Simpanan',
        'description' => 'Riwayat transaksi simpanan'
    ],
    'pembiayaan' => [
        'title' => 'Status Pembiayaan',
        'description' => 'Daftar pembiayaan aktif'
    ],
    'pengajuan-pembiayaan' => [
        'title' => 'Pengajuan Pembiayaan',
        'description' => 'Form pengajuan pembiayaan baru'
    ],
    'laporan' => [
        'title' => 'Laporan Keuangan',
        'description' => 'Laporan keuangan pribadi'
    ]
];

foreach ($screenshots as $filename => $info) {
    $width = 1200;
    $height = 800;

    // Create image
    $image = imagecreatetruecolor($width, $height);

    // Background gradient
    $color1 = imagecolorallocate($image, 5, 150, 105); // #059669
    $color2 = imagecolorallocate($image, 16, 185, 129); // #10b981
    $textColor = imagecolorallocate($image, 255, 255, 255);
    $borderColor = imagecolorallocate($image, 200, 200, 200);

    // Create gradient background
    for ($y = 0; $y < $height; $y++) {
        $ratio = $y / $height;
        $r1 = ($color1 >> 16) & 0xFF;
        $g1 = ($color1 >> 8) & 0xFF;
        $b1 = $color1 & 0xFF;
        $r2 = ($color2 >> 16) & 0xFF;
        $g2 = ($color2 >> 8) & 0xFF;
        $b2 = $color2 & 0xFF;

        $r = intval($r1 + ($r2 - $r1) * $ratio);
        $g = intval($g1 + ($g2 - $g1) * $ratio);
        $b = intval($b1 + ($b2 - $b1) * $ratio);
        imageline($image, 0, $y, $width, $y, imagecolorallocate($image, $r, $g, $b));
    }

    // Add border
    imagerectangle($image, 10, 10, $width - 20, $height - 20, $borderColor);

    // Logo placeholder
    $logoSize = 80;
    $logoX = ($width - $logoSize) / 2;
    $logoY = 60;
    imagefilledellipse($image, $logoX + $logoSize/2, $logoY + $logoSize/2, $logoSize/2, $logoSize/2, $textColor);

    // Title text - using built-in fonts for simplicity
    $title = $info['title'];
    $titleWidth = imagefontwidth(5) * strlen($title);
    $titleX = ($width - $titleWidth) / 2;
    imagestring($image, 5, $titleX, 180, $title, $textColor);

    // Description text
    $description = wordwrap($info['description'], 50, "\n", true);
    $lines = explode("\n", $description);
    $yPos = 230;
    foreach ($lines as $line) {
        $descWidth = imagefontwidth(3) * strlen($line);
        $descX = ($width - $descWidth) / 2;
        imagestring($image, 3, $descX, $yPos, $line, $textColor);
        $yPos += 25;
    }

    // URL text
    $url = 'https://koperasi.domain.com';
    $urlWidth = imagefontwidth(2) * strlen($url);
    $urlX = ($width - $urlWidth) / 2;
    imagestring($image, 2, $urlX, $height - 80, $url, $textColor);

    // Save image
    $outputPath = __DIR__ . '/screenshots/' . $filename . '.png';
    imagepng($image, $outputPath);
    imagedestroy($image);

    echo "Created screenshot: $filename.png\n";
}

echo "All screenshots created successfully!\n";
echo "Location: " . __DIR__ . "/screenshots/\n";