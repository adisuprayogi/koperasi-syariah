<?php

// Create realistic screenshots for each role following actual application flow

function createScreenshot($filename, $title, $description, $role = 'general') {
    $width = 1200;
    $height = 800;

    $image = imagecreatetruecolor($width, $height);

    // Define role-based colors
    $colors = [
        'admin' => [
            'header' => imagecolorallocate($image, 220, 38, 38),    // Red
            'sidebar' => imagecolorallocate($image, 239, 68, 68),   // Light red
            'card' => imagecolorallocate($image, 254, 226, 226),   // Light red bg
            'text' => imagecolorallocate($image, 127, 29, 29),     // Dark red
            'accent' => imagecolorallocate($image, 252, 165, 165)   // Accent red
        ],
        'pengurus' => [
            'header' => imagecolorallocate($image, 124, 58, 237),   // Purple
            'sidebar' => imagecolorallocate($image, 139, 92, 246),  // Light purple
            'card' => imagecolorallocate($image, 243, 232, 255),   // Light purple bg
            'text' => imagecolorallocate($image, 67, 56, 202),     // Dark purple
            'accent' => imagecolorallocate($image, 196, 181, 253)   // Accent purple
        ],
        'anggota' => [
            'header' => imagecolorallocate($image, 5, 150, 105),    // Green
            'sidebar' => imagecolorallocate($image, 16, 185, 129),  // Light green
            'card' => imagecolorallocate($image, 220, 252, 231),    // Light green bg
            'text' => imagecolorallocate($image, 4, 120, 87),       // Dark green
            'accent' => imagecolorallocate($image, 134, 239, 172)    // Accent green
        ],
        'general' => [
            'header' => imagecolorallocate($image, 59, 130, 246),   // Blue
            'sidebar' => imagecolorallocate($image, 96, 165, 250),  // Light blue
            'card' => imagecolorallocate($image, 219, 234, 254),    // Light blue bg
            'text' => imagecolorallocate($image, 29, 78, 216),      // Dark blue
            'accent' => imagecolorallocate($image, 147, 197, 253)    // Accent blue
        ]
    ];

    $c = $colors[$role];
    $white = imagecolorallocate($image, 255, 255, 255);
    $gray = imagecolorallocate($image, 243, 244, 246);
    $dark = imagecolorallocate($image, 31, 41, 55);

    // Background
    imagefill($image, 0, 0, $gray);

    // Header
    imagefilledrectangle($image, 0, 0, $width, 80, $c['header']);

    // Title in header
    $font = 5;
    imagestring($image, $font, 20, 30, $title, $white);

    // Sidebar
    imagefilledrectangle($image, 0, 80, 250, $height, $c['sidebar']);

    // Menu items in sidebar
    $menuItems = [
        'admin' => ['Dashboard', 'User Management', 'System Config', 'Security', 'Reports'],
        'pengurus' => ['Dashboard', 'Anggota', 'Simpanan', 'Pembiayaan', 'Laporan'],
        'anggota' => ['Dashboard', 'Profile', 'Simpanan', 'Pembiayaan', 'Riwayat'],
        'general' => ['Dashboard', 'Profile', 'Simpanan', 'Pembiayaan', 'Settings']
    ];

    $y = 100;
    foreach ($menuItems[$role] as $item) {
        imagestring($image, 2, 20, $y, "• " . $item, $white);
        $y += 30;
    }

    // Main content area
    $contentX = 270;
    $contentY = 100;

    // Content cards
    for ($i = 0; $i < 3; $i++) {
        $cardY = $contentY + ($i * 200);
        imagefilledrectangle($image, $contentX, $cardY, $width - 30, $cardY + 150, $white);
        imagerectangle($image, $contentX, $cardY, $width - 30, $cardY + 150, $c['accent']);

        // Card header
        imagefilledrectangle($image, $contentX, $cardY, $width - 30, $cardY + 40, $c['card']);

        // Sample content in cards
        imagestring($image, 2, $contentX + 10, $cardY + 10, "Card " . ($i + 1) . " Content", $c['text']);

        // Sample data rows
        for ($j = 0; $j < 4; $j++) {
            $rowY = $cardY + 50 + ($j * 20);
            imagestring($image, 1, $contentX + 10, $rowY, "Data row " . ($j + 1), $dark);
            imagestring($image, 1, $contentX + 200, $rowY, "Value " . ($j + 1), $dark);
        }
    }

    // Action buttons
    $buttonY = $height - 100;
    imagefilledrectangle($image, $contentX, $buttonY, $contentX + 120, $buttonY + 40, $c['header']);
    imagestring($image, 2, $contentX + 20, $buttonY + 10, "Action 1", $white);

    imagefilledrectangle($image, $contentX + 140, $buttonY, $contentX + 260, $buttonY + 40, $c['accent']);
    imagestring($image, 2, $contentX + 160, $buttonY + 10, "Action 2", $c['text']);

    // Footer with description
    imagefilledrectangle($image, 0, $height - 50, $width, $height, $white);
    imagestring($image, 2, 20, $height - 40, $description, $dark);

    imagepng($image, $filename);
    imagedestroy($image);
}

// Create admin screenshots
echo "Creating Admin screenshots...\n";
createScreenshot('public/admin-dashboard.png', 'Admin Dashboard', 'Dashboard Administrator dengan monitoring sistem, user management, dan security alerts', 'admin');
createScreenshot('public/admin-user-management.png', 'User Management', 'Halaman management pengguna dengan CRUD operations dan role-based access control', 'admin');
createScreenshot('public/admin-system-config.png', 'System Configuration', 'Konfigurasi sistem, payment gateway, dan parameter operasional', 'admin');
createScreenshot('public/admin-security.png', 'Security Settings', 'Pengaturan keamanan, audit trails, dan monitoring aktivitas', 'admin');

// Create pengurus screenshots
echo "Creating Pengurus screenshots...\n";
createScreenshot('public/pengurus-dashboard.png', 'Dashboard Pengurus', 'Dashboard Pengurus dengan overview operasional dan pending approvals', 'pengurus');
createScreenshot('public/pengurus-anggota-list.png', 'Daftar Anggota', 'Halaman daftar anggota dengan fitur search, filter, dan bulk operations', 'pengurus');
createScreenshot('public/pengurus-simpanan-transaksi.png', 'Transaksi Simpanan', 'Form proses transaksi simpanan dengan validasi otomatis', 'pengurus');
createScreenshot('public/pengurus-approval-pembiayaan.png', 'Approval Pembiayaan', 'Halaman approval pengajuan pembiayaan dengan assessment matrix', 'pengurus');
createScreenshot('public/pengurus-laporan-keuangan.png', 'Laporan Keuangan', 'Generate laporan keuangan lengkap dengan export capabilities', 'pengurus');

// Create anggota screenshots
echo "Creating Anggota screenshots...\n";
createScreenshot('public/anggota-dashboard.png', 'Dashboard Anggota', 'Dashboard personal anggota dengan ringkasan keuangan dan quick actions', 'anggota');
createScreenshot('public/anggota-profile-edit.png', 'Edit Profile', 'Halaman edit profil anggota dengan upload foto dan validasi data', 'anggota');
createScreenshot('public/anggota-simpanan-detail.png', 'Detail Simpanan', 'Halaman detail simpanan anggota dengan riwayat transaksi', 'anggota');
createScreenshot('public/anggota-pengajuan-form.png', 'Form Pengajuan', 'Form pengajuan pembiayaan dengan upload dokumen dan kalkulasi otomatis', 'anggota');
createScreenshot('public/anggota-pembiayaan-status.png', 'Status Pembiayaan', 'Tracking status pembiayaan dengan jadwal angsuran dan payment history', 'anggota');
createScreenshot('public/anggota-riwayat-transaksi.png', 'Riwayat Transaksi', 'Halaman riwayat lengkap transaksi dengan filter dan export', 'anggota');

echo "Screenshots created successfully!\n";
?>