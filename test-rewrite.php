<?php
// Test file to check if .htaccess rewrite is working
// Access this file directly: http://rgorganicmart.expensi.in/test-rewrite.php
// If you see this message, PHP is working
// If you get redirected or see Laravel, rewrite is working

echo "PHP is working!<br>";
echo "Current URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";

// Check if mod_rewrite is available
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "mod_rewrite is ENABLED<br>";
    } else {
        echo "mod_rewrite is DISABLED<br>";
    }
} else {
    echo "Cannot check mod_rewrite status (function not available)<br>";
}
?>

