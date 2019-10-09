<?php

$start = microtime(true);

$requiredArgs = ['source path'];

$params = array_slice($argv, 1);

$args = array_values(array_filter($params, function (string $param) {
    return substr($param, 0, 1) !== '-';
}));

$options = array_values(array_map(function (string $arg) {
    return substr($arg, 1);
}, array_diff($params, $args)));

if (count($args) !== count($requiredArgs)) {
    echo 'Required arguments: ' . implode(', ', $requiredArgs);
    echo PHP_EOL;
    exit(1);
}

[$sourcePath] = $args;

if (!file_exists($sourcePath)) {
    echo 'Source file not found.';
    echo PHP_EOL;
    exit(1);
}

$knownCharacters = ['ó', 'Ó', '¹', 'æ', 'ê', '³', 'ñ', 'œ', 'Ÿ', '¿', '¥', 'Æ', 'Ê', '£', 'Ñ', 'Œ', "\u{8f}", '¯'];

$sourceFile = fopen($sourcePath, 'r');

$result = [];

for ($lineNumber = 1; ($sourceLine = fgets($sourceFile)) !== false; $lineNumber += 1) {
    $unknownCharacters = array_unique(array_diff(array_filter(
        preg_split('//u', $sourceLine, -1, PREG_SPLIT_NO_EMPTY),
        function (string $character) {
            return strlen($character) >= 2;
        }
    ), $knownCharacters));

    foreach ($unknownCharacters as $unknownCharacter) {
        if (!isset($result[$unknownCharacter])) {
            $result[$unknownCharacter] = [];
        }

        $result[$unknownCharacter][] = $lineNumber;
    }
}

arsort($result);

printf('%d unknown characters found.', count($result));
echo PHP_EOL;
echo PHP_EOL;

foreach ($result as $character => $lineNumbers) {
    printf('%s - lines: %s (%d times)', $character, implode(', ', $lineNumbers), count($lineNumbers));
    echo PHP_EOL;
}

if (!empty($result)) {
    echo PHP_EOL;
}

printf('Done in %fs.', microtime(true) - $start);
echo PHP_EOL;
