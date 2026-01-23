<?php
/**
 * Laravel Cache Clearer for cPanel (No Terminal Access Required)
 *
 * INSTRUCTIONS:
 * 1. Upload this file to your server's public folder
 * 2. Access via browser: https://yourdomain.com/clear-cache.php
 * 3. Delete this file immediately after use for security!
 *
 * This script clears:
 * - Route cache
 * - Config cache
 * - Application cache
 * - View cache
 */

// Security: Add simple password protection (change 'secret123' to your own password)
$password = 'secret123';

// Check if password is provided
if (!isset($_GET['password']) || $_GET['password'] !== $password) {
    die('<html>
<head><title>Cache Clearer</title></head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px;">
    <h1 style="color: #c53030;">Access Denied</h1>
    <p>Invalid password. Please provide the correct password.</p>
</body>
</html>');
}

$startTime = microtime(true);
$results = [];

echo '<html>
<head>
    <title>Laravel Cache Clearer</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        h1 { color: #2b6cb0; }
        .success { background: #c6f6d5; border: 1px solid #48bb78; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .error { background: #fed7d7; border: 1px solid #f56565; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .info { background: #bee3f8; border: 1px solid #4299e1; padding: 10px; margin: 10px 0; border-radius: 5px; }
        code { background: #edf2f7; padding: 2px 6px; border-radius: 3px; }
        pre { background: #1a202c; color: #68d391; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Laravel Cache Clearer</h1>
    <div class="info">Clearing Laravel caches... Please wait.</div>';

// Function to delete files in a directory
function deleteFilesInDirectory($dir, $pattern = '*') {
    $count = 0;
    if (is_dir($dir)) {
        $files = glob($dir . '/' . $pattern);
        foreach ($files as $file) {
            if (is_file($file)) {
                if (unlink($file)) {
                    $count++;
                }
            }
        }
    }
    return $count;
}

// Clear Route Cache
echo '<div class="info">Clearing Route Cache...</div>';
$routeCacheFiles = [
    __DIR__ . '/../bootstrap/cache/routes-v7.php',
    __DIR__ . '/../bootstrap/cache/routes.php',
];
$routeCleared = 0;
foreach ($routeCacheFiles as $file) {
    if (file_exists($file)) {
        if (unlink($file)) {
            $routeCleared++;
        }
    }
}
$results['route_cache'] = $routeCleared > 0;

// Clear Config Cache
echo '<div class="info">Clearing Config Cache...</div>';
$configCacheFiles = [
    __DIR__ . '/../bootstrap/cache/config.php',
];
$configCleared = 0;
foreach ($configCacheFiles as $file) {
    if (file_exists($file)) {
        if (unlink($file)) {
            $configCleared++;
        }
    }
}
$results['config_cache'] = $configCleared > 0;

// Clear Application Cache (storage/framework/cache/data/*)
echo '<div class="info">Clearing Application Cache...</div>';
$cacheDir = __DIR__ . '/../storage/framework/cache/data';
$appCacheCleared = deleteFilesInDirectory($cacheDir, '*');
$results['app_cache'] = true;

// Clear View Cache
echo '<div class="info">Clearing View Cache...</div>';
$viewCacheCleared = deleteFilesInDirectory(__DIR__ . '/../storage/framework/views', '*');
$results['view_cache'] = $viewCacheCleared > 0;

// Clear Compiled Views
echo '<div class="info">Clearing Compiled Views...</div>';
$compiledViewsCleared = deleteFilesInDirectory(__DIR__ . '/../storage/framework/views', '*.php');
$results['compiled_views'] = $compiledViewsCleared > 0;

// Calculate execution time
$executionTime = round((microtime(true) - $startTime) * 1000, 2);

// Display results
echo '<h2 style="margin-top: 30px;">Results:</h2>';

foreach ($results as $cache => $success) {
    $label = str_replace('_', ' ', strtoupper($cache));
    if ($success) {
        echo '<div class="success">' . $label . ': Cleared</div>';
    } else {
        echo '<div class="error">' . $label . ': No files to clear</div>';
    }
}

echo '<div class="info" style="margin-top: 20px;">
    <strong>Execution Time:</strong> ' . $executionTime . ' ms
</div>';

echo '<div style="margin-top: 30px; padding: 15px; background: #fff5f5; border: 1px solid #fc8181; border-radius: 5px;">
    <h3 style="color: #c53030; margin-top: 0;">IMPORTANT: Delete this file now!</h3>
    <p>For security reasons, please delete this file from your server immediately:</p>
    <code>public/clear-cache.php</code>
    <p style="margin-top: 10px;">You can delete it via cPanel File Manager or FTP.</p>
</div>';

echo '<div style="margin-top: 20px; padding: 15px; background: #f7fafc; border: 1px solid #cbd5e0; border-radius: 5px;">
    <h3>Next Steps:</h3>
    <ol>
        <li>Delete this file from your server</li>
        <li>Try accessing your application again</li>
        <li>If the problem persists, check that all routes are properly defined in <code>routes/web.php</code></li>
    </ol>
</div>';

// List current routes for debugging
echo '<div style="margin-top: 20px;">
    <h3>Debugging Info:</h3>
    <p>Checking if routes file exists...</p>';
if (file_exists(__DIR__ . '/../routes/web.php')) {
    echo '<div class="success">Routes file exists: <code>routes/web.php</code></div>';
} else {
    echo '<div class="error">Routes file NOT found: <code>routes/web.php</code></div>';
}
echo '</div>';

echo '</body></html>';
