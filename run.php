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

$output = [
    'start-with' => [],
    'end-with'   => [],
    'words-with' => [],
];


foreach ($inputFiles as $filename) {
    $ifh = new SplFileObject($filename);
    while ($ifh->valid()) {
        $line = trim($ifh->fgets());

        if (empty($line))
            continue;

        $len = strlen($line);
        $firstChar = $line[0];
        $lastChar = $line[$len - 1];

        $output['start-with'][$firstChar][$len][] = $line;
        $output['end-with'][$lastChar][$len][] = $line;

        foreach(str_split($line) as $c) {
            $output['words-with'][$c][$len][] = $line;
        }
    }
    $ifh = null;

    // Store output
    foreach ($output as $set => $data) {

        foreach($data as $char => $subdata){

            $dir = "./output/$set/";
            $filename = "./output/$set/$char.json";

            if(!is_dir($dir)){
                mkdir($dir,0777,true);
            }

            $ofh = new SplFileObject($filename, 'w');
            $ofh->fwrite(json_encode($subdata));
            $ofh = null;

        }
    }
}
