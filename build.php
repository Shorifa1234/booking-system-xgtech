<?php
// Builds a static copy of the landing pages for GitHub Pages into ./docs
// Usage: php build.php
//
// GitHub Pages cannot run PHP or MySQL, so in the static copy the demo form
// opens an email draft to the business email instead of saving to the database.

$pages = [
    'index.php'       => 'index.html',
    'book-nboost.php' => 'book-nboost.html',
];

// Child mode: render one page to stdout (each page gets a fresh PHP process).
if (isset($argv[1]) && $argv[1] === '--render') {
    define('STATIC_BUILD', true);
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $src = basename($argv[2] ?? '');
    if (!isset($pages[$src])) {
        fwrite(STDERR, "Unknown page: $src\n");
        exit(1);
    }
    require __DIR__ . '/' . $src;
    exit(0);
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

$out = __DIR__ . '/docs';
if (!is_dir($out)) {
    mkdir($out, 0755, true);
}

$failed = false;
foreach ($pages as $src => $dest) {
    $cmd = escapeshellarg(PHP_BINARY) . ' -d display_errors=stderr ' . escapeshellarg(__FILE__) . ' --render ' . escapeshellarg($src);
    $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    $html = stream_get_contents($pipes[1]);
    $err  = stream_get_contents($pipes[2]);
    $code = proc_close($proc);
    if ($code !== 0 || trim($err) !== '') {
        fwrite(STDERR, "Failed to build $src:\n$err\n");
        $failed = true;
        continue;
    }
    file_put_contents("$out/$dest", $html);
    echo "Built docs/$dest\n";
}

copy_dir(__DIR__ . '/assets', "$out/assets");
file_put_contents("$out/.nojekyll", '');

exit($failed ? 1 : 0);
