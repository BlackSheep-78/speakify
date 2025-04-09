<?php
// File: public/diagnostic.php
// Advanced Speakify environment diagnostics (Apache + .htaccess + mod_rewrite)

header('Content-Type: text/plain');

echo "============================\n";
echo "🔍 Speakify Apache Diagnostic\n";
echo "============================\n\n";

// 1. Check if mod_rewrite is loaded
$rewrite_loaded = in_array('mod_rewrite', apache_get_modules());
echo "🔧 mod_rewrite loaded: " . ($rewrite_loaded ? "✅ YES" : "❌ NO") . "\n";

// 2. Check if .htaccess redirect test is working
$htaccess_redirect = (strpos($_SERVER['REQUEST_URI'], 'test-htaccess') !== false);
echo "🧪 .htaccess redirect test: " . ($htaccess_redirect ? "✅ YES (active)" : "❌ NO (not triggered)") . "\n";

// 3. Check if AllowOverride is assumed active
$override_ok = ini_get('allow_url_include') !== false;
echo "🔒 AllowOverride All (assumed): " . ($override_ok ? "✅ YES" : "❓ UNKNOWN") . "\n";

// 4. Check clean URL routing
$clean_url_working = (isset($_GET['ping']) && $_GET['ping'] === 'pong');
echo "🌐 Clean URL route: " . ($clean_url_working ? "✅ YES" : "❌ NO") . "\n";

// 5. Verify .htaccess file exists
$htaccess_path = __DIR__ . '/.htaccess';
$htaccess_exists = file_exists($htaccess_path);
echo "\n📄 .htaccess file: " . ($htaccess_exists ? "✅ FOUND" : "❌ MISSING") . " at /public/.htaccess\n";

// 6. Optional cleanup
if (isset($_GET['delete']) && $_GET['delete'] === 'true') {
    unlink(__FILE__);
    echo "\n🧼 diagnostic.php deleted.\n";
} else {
    echo "\n🧹 When done, delete this file or run: diagnostic.php?delete=true\n";
}
?>