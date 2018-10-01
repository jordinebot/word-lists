<?php
ini_set('max_execution_time', 300);

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
    'word-length' => [],
    'consonants' => [],
    'vowels' => [],
    'ing' => [],
    'qnou' => []
];

$scrabble_points = [
    'a' => 1,
    'c' => 3,
    'b' => 3,
    'e' => 1,
    'd' => 2,
    'g' => 2,
    'f' => 4,
    'i' => 1,
    'h' => 4,
    'k' => 5,
    'j' => 8,
    'm' => 3,
    'l' => 1,
    'o' => 1,
    'n' => 1,
    'q' => 10,
    'p' => 3,
    's' => 1,
    'r' => 1,
    'u' => 1,
    't' => 1,
    'w' => 4,
    'v' => 4,
    'y' => 4,
    'x' => 8,
    'z' => 10,
];

$wwf_points = [
    'a' => 1,
    'c' => 4,
    'b' => 4,
    'e' => 1,
    'd' => 2,
    'g' => 3,
    'f' => 4,
    'i' => 1,
    'h' => 3,
    'k' => 5,
    'j' => 10,
    'm' => 4,
    'l' => 2,
    'o' => 1,
    'n' => 2,
    'q' => 10,
    'p' => 4,
    's' => 1,
    'r' => 1,
    'u' => 2,
    't' => 1,
    'w' => 4,
    'v' => 5,
    'y' => 3,
    'x' => 8,
    'z' => 10,
];

function get_score($word, $letters_points_array){
  $total = 0;
  for($i = strlen($word) - 1; $i >= 0; $i--){
    $char = strtolower(substr($word, $i,1));
    $total += $letters_points_array[$char];
  }
  return $total;
}

foreach ($inputFiles as $filename) {
    $ifh = new SplFileObject($filename);
    while ($ifh->valid()) {
        $line = trim($ifh->fgets());

        if (empty($line))
            continue;

        $len = strlen($line);
        $firstChar = $line[0];
        $lastChar = $line[$len - 1];

        $entry = array(
            'word' => $line,
            'scrabble_score' => get_score($line,$scrabble_points),
            'wwf_score' => get_score($line,$wwf_points)
        );

        if(isset($output['word-length'])){
            $output['word-length'][$len][] = $entry;
        }

        if ($len > 7)
            continue;

        if(isset($output['start-with'])){
            $output['start-with'][$firstChar][$len][] = $entry;
        }

        if(isset($output['end-with'])){
            $output['end-with'][$lastChar][$len][] = $entry;
        }

        if(isset($output['words-with'])){
            foreach(str_split($line) as $c) {
                $output['words-with'][$c][$len][] = $entry;
            }
        }

        if(isset($output['consonants'])){
            $match = preg_match('/^[^AEIOU]+$/', $line);
            if($match === 1){
                $output['consonants'][$len][] = $entry;
            }
        }

        if(isset($output['vowels'])){
            $match = preg_match('/^[AEIOU]+$/', $line);
            if($match === 1){
                $output['vowels'][$len][] = $entry;
            }
        }

        if(isset($output['ing'])){
            $match = preg_match('/ING$/',$line);
            if($match === 1){
                $output['ing'][$len][] = $entry;
            }
        }

        if(isset($output['qnou'])){
            $match = preg_match('/Q[^U]/',$line);
            if($match === 1){
                $output['qnou'][$len][] = $entry;
            }
        }
    }
    $ifh = null;

    // Store output
    foreach ($output as $set => $data) {

        switch($set){
            case 'end-with':
            case 'start-with':
            case 'words-with':
            case 'word-length':

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


                break;

            default:
                $dir = "./output/";
                $filename = "./output/$set.json";

                if(!is_dir($dir)){
                    mkdir($dir,0777,true);
                }

                $ofh = new SplFileObject($filename, 'w');
                $ofh->fwrite(json_encode($data));
                $ofh = null;

                break;
        }



    }
}

echo 'done';
