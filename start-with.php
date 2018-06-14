<?php

$inputFiles = [
    __DIR__ . '/data/scrabble2.txt',
    __DIR__ . '/data/scrabble3.txt',
    __DIR__ . '/data/scrabble4.txt',
    __DIR__ . '/data/scrabble5.txt',
    __DIR__ . '/data/scrabble6.txt',
    __DIR__ . '/data/scrabble7.txt',
    __DIR__ . '/data/scrabble8.txt',
    __DIR__ . '/data/scrabble9.txt',
    __DIR__ . '/data/scrabble10.txt',
    __DIR__ . '/data/scrabble11.txt',
    __DIR__ . '/data/scrabble12.txt',
];

$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

$start = microtime(true);
foreach ($inputFiles as $filename) {
    $file = new SplFileObject($filename);
    while ($file->valid()) {
        $line = $file->fgets();
    }
    $file = null;
}
var_dump(microtime(true) - $start);

function lineGenerator($file) {
    $fh = fopen($file, 'r');

    if (!$fh)
        die('File not found or cannot be opened');

    try {
        while ($line = fgets($fh) !== false)
            yield $line;
    } finally {
        fclose($fh);
    }
}

$start = microtime(true);
foreach ($inputFiles as $filename) {
    foreach (lineGenerator($filename) as $line) {
        //noop
    }
}
var_dump(microtime(true) - $start);
