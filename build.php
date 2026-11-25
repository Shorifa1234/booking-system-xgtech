<?php
// Builds a static copy of the landing page for GitHub Pages into ./docs
// Usage: php build.php
//
// GitHub Pages cannot run PHP or MySQL, so in the static copy the demo form
// opens an email draft to the business email instead of saving to the database.

define('STATIC_BUILD', true);
$_SERVER['REQUEST_METHOD'] = 'GET';
$out = __DIR__ . '/docs';

function render(string $file): string
{
    ob_start();
    include $file;
    return ob_get_clean();
}

function copy_dir(string $from, string $to): void
{
    if (!is_dir($to)) {
        mkdir($to, 0755, true);
    }
    foreach (scandir($from) as $f) {
        if ($f === '.' || $f === '..') continue;
        is_dir("$from/$f") ? copy_dir("$from/$f", "$to/$f") : copy("$from/$f", "$to/$f");
    }
}

if (!is_dir($out)) {
    mkdir($out, 0755, true);
}

file_put_contents("$out/index.html", render(__DIR__ . '/index.php'));
copy_dir(__DIR__ . '/assets', "$out/assets");
file_put_contents("$out/.nojekyll", '');

echo "Built docs/index.html\n";
