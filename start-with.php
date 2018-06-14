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

foreach ($inputFiles as $filename) {
    $ifh = new SplFileObject($filename);
        while ($ifh->valid()) {
            $line = trim($ifh->fgets());
            if (empty($line))
                continue;
            $c = $line[0];
            $l = strlen($line);
            $output[$c][$l][] = $line;
        }
    $ofh = new SplFileObject('./output/start-with.json', 'w');
    $ofh->fwrite(json_encode($output));
    $ifh = null;
    $ofh = null;
}
