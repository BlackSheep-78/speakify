<?php
echo "<h2>mod_rewrite Diagnostic</h2>";

if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "<p style='color: green;'>✅ mod_rewrite is ENABLED.</p>";
    } else {
        echo "<p style='color: red;'>❌ mod_rewrite is NOT enabled.</p>";
    }
} else {
    echo "<p>⚠️ Cannot determine Apache modules — <code>apache_get_modules()</code> is not available on this server.</p>";
    echo "<p>Try creating a test .htaccess rewrite rule to verify manually.</p>";
}
