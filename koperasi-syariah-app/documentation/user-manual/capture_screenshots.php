<?php
/**
 * Script untuk capture screenshot dashboard Koperasi Syariah
 * Gunakan dengan: php capture_screenshots.php
 */

// Credentials untuk setiap role
$credentials = [
    'anggota' => [
        'username' => '2521.00001',
        'password' => '22222222',
        'dashboard_url' => 'http://127.0.0.1:8010/dashboard/anggota',
        'screenshots' => [
            'dashboard' => 'Dashboard Anggota',
            'profil' => 'http://127.0.0.1:8010/profil',
            'simpanan' => 'http://127.0.0.1:8010/simpanan',
            'pinjaman' => 'http://127.0.0.1:8010/pinjaman',
            'angsuran' => 'http://127.0.0.1:8010/angsuran',
            'shu' => 'http://127.0.0.1:8010/shu'
        ]
    ],
    'ketua_pengurus' => [
        'username' => 'yogi@gmail.com',
        'password' => '22222222',
        'dashboard_url' => 'http://127.0.0.1:8010/dashboard/pengurus',
        'screenshots' => [
            'dashboard' => 'Dashboard Ketua Pengurus',
            'data_anggota' => 'http://127.0.0.1:8010/admin/data-anggota',
            'simpanan' => 'http://127.0.0.1:8010/admin/simpanan',
            'pinjaman' => 'http://127.0.0.1:8010/admin/pinjaman',
            'keuangan' => 'http://127.0.0.1:8010/admin/keuangan',
            'shu' => 'http://127.0.0.1:8010/admin/shu'
        ]
    ],
    'bendahara' => [
        'username' => 'fitri@gmail.com',
        'password' => '33333333',
        'dashboard_url' => 'http://127.0.0.1:8010/dashboard/pengurus',
        'screenshots' => [
            'dashboard' => 'Dashboard Bendahara',
            'verifikasi_pinjaman' => 'http://127.0.0.1:8010/admin/verifikasi-pinjaman',
            'pencairan' => 'http://127.0.0.1:8010/admin/pencairan',
            'tunggakan' => 'http://127.0.0.1:8010/admin/tunggakan',
            'jurnal' => 'http://127.0.0.1:8010/admin/jurnal',
            'laporan' => 'http://127.0.0.1:8010/admin/laporan'
        ]
    ],
    'administrator' => [
        'username' => 'admin@admin.com',
        'password' => 'password',
        'dashboard_url' => 'http://127.0.0.1:8010/dashboard/admin',
        'screenshots' => [
            'dashboard' => 'Dashboard Administrator',
            'user_management' => 'http://127.0.0.1:8010/admin/users',
            'system_settings' => 'http://127.0.0.1:8010/admin/settings',
            'database' => 'http://127.0.0.1:8010/admin/database',
            'security' => 'http://127.0.0.1:8010/admin/security',
            'logs' => 'http://127.0.0.1:8010/admin/logs'
        ]
    ]
];

echo "=== SCREENSHOT CAPTURE TOOL ===\n";
echo "Koperasi Syariah Application Documentation\n\n";

// Fungsi untuk capture screenshot menggunakan curl dan save sebagai HTML
function capturePage($url, $filename, $credentials = null) {
    echo "Capturing: $url\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

    // Jika ada credentials untuk login
    if ($credentials) {
        // Pertama login dulu
        $login_data = http_build_query([
            'username' => $credentials['username'],
            'password' => $credentials['password']
        ]);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $login_data);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');

        // Login request
        curl_exec($ch);

        // Request ke dashboard setelah login
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_URL, $url);
    }

    $html = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        // Save HTML file
        file_put_contents($filename, $html);
        echo "✅ Saved: $filename (HTTP: $http_code)\n";
        return true;
    } else {
        echo "❌ Failed: $url (HTTP: $http_code)\n";
        return false;
    }
}

// Buat direktori untuk screenshots
$screenshot_dir = 'screenshots';
if (!is_dir($screenshot_dir)) {
    mkdir($screenshot_dir, 0777, true);
    echo "Created directory: $screenshot_dir\n";
}

// Function untuk create menu documentation
function createMenuDocs($role, $data) {
    $filename = "menu_documentation_{$role}.md";
    $content = "# Menu Documentation - " . ucfirst($role) . "\n\n";

    if (isset($data['screenshots'])) {
        $content .= "## Available Screenshots:\n\n";
        foreach ($data['screenshots'] as $key => $url) {
            $content .= "- **$key**: $url\n";
        }
        $content .= "\n## Login Credentials:\n";
        $content .= "- **Username:** " . $data['username'] . "\n";
        $content .= "- **Password:** " . $data['password'] . "\n\n";
    }

    file_put_contents($filename, $content);
    echo "✅ Created: $filename\n";
}

// Capture untuk setiap role
foreach ($credentials as $role => $data) {
    echo "\n--- Processing Role: " . ucfirst($role) . " ---\n";

    // Create role directory
    $role_dir = $screenshot_dir . '/' . $role;
    if (!is_dir($role_dir)) {
        mkdir($role_dir, 0777, true);
    }

    // Create menu documentation
    createMenuDocs($role, $data);

    // Capture dashboard
    $dashboard_file = $role_dir . '/dashboard.html';
    capturePage($data['dashboard_url'], $dashboard_file, $data);

    // Capture other pages
    if (isset($data['screenshots'])) {
        foreach ($data['screenshots'] as $page_name => $page_url) {
            if ($page_name !== 'dashboard' && filter_var($page_url, FILTER_VALIDATE_URL)) {
                $filename = $role_dir . '/' . $page_name . '.html';
                capturePage($page_url, $filename, $data);
            }
        }
    }
}

echo "\n=== CAPTURE COMPLETE ===\n";
echo "Screenshots saved in: $screenshot_dir/\n";
echo "Open index.html to view documentation\n";

// Create index.html untuk viewing
$index_content = '<!DOCTYPE html>
<html>
<head>
    <title>Koperasi Syariah - Screenshots Documentation</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .role-section { margin-bottom: 40px; border: 1px solid #ddd; padding: 20px; }
        .screenshot-list { list-style: none; padding: 0; }
        .screenshot-list li { margin: 10px 0; }
        .screenshot-list a { text-decoration: none; color: #0066cc; }
        .screenshot-list a:hover { text-decoration: underline; }
        .credentials { background: #f5f5f5; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Koperasi Syariah Application Documentation</h1>
    <p>Aplikasi berjalan di: <a href="http://127.0.0.1:8010" target="_blank">http://127.0.0.1:8010</a></p>';

foreach ($credentials as $role => $data) {
    $index_content .= '
    <div class="role-section">
        <h2>Role: ' . ucfirst($role) . '</h2>
        <div class="credentials">
            <strong>Login Credentials:</strong><br>
            Username: ' . $data['username'] . '<br>
            Password: ' . $data['password'] . '
        </div>
        <h3>Screenshots:</h3>
        <ul class="screenshot-list">
            <li><a href="screenshots/' . $role . '/dashboard.html" target="_blank">Dashboard</a></li>';

    if (isset($data['screenshots'])) {
        foreach ($data['screenshots'] as $page_name => $page_url) {
            if ($page_name !== 'dashboard') {
                $index_content .= '<li><a href="screenshots/' . $role . '/' . $page_name . '.html" target="_blank">' . ucfirst(str_replace('_', ' ', $page_name)) . '</a></li>';
            }
        }
    }

    $index_content .= '
        </ul>
    </div>';
}

$index_content .= '
</body>
</html>';

file_put_contents('index.html', $index_content);
echo "✅ Created: index.html\n";

// Cleanup
if (file_exists('cookies.txt')) {
    unlink('cookies.txt');
}

echo "\nNext steps:\n";
echo "1. Open screenshots/ directories to view captured pages\n";
echo "2. Open index.html for navigation\n";
echo "3. Review DOKUMENTASI_KOPERASI_SYARIAH.md for complete documentation\n";
?>