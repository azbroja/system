<?php

$start = microtime(true);

$requiredArgs = ['source path', 'destination path'];

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

[$sourcePath, $destinationPath] = $args;

if (!file_exists($sourcePath)) {
    echo 'Source file not found.';
    echo PHP_EOL;
    exit(1);
}

if ($sourcePath === $destinationPath) {
    echo 'Cannot overwrite the source file.';
    echo PHP_EOL;
    exit(1);
}

$force = in_array('f', $options);

if (!$force && file_exists($destinationPath)) {
    echo 'Destination file already exists.';
    echo PHP_EOL;
    exit(1);
}

$sourceFile = fopen($sourcePath, 'r');
$destinationFile = fopen($destinationPath, 'w');

$replacements = [
    ' CHARACTER SET latin1' => '',
    ' DEFAULT CHARSET=latin1' => '',
    ' DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci' => '',
    '¹' => 'ą',
    'æ' => 'ć',
    'ê' => 'ę',
    '³' => 'ł',
    'ñ' => 'ń',
    'œ' => 'ś',
    'Ÿ' => 'ź',
    '¿' => 'ż',
    '¥' => 'Ą',
    'Æ' => 'Ć',
    'Ê' => 'Ę',
    '£' => 'Ł',
    'Ñ' => 'Ń',
    'Œ' => 'Ś',
    "\u{8f}" => 'Ź',
    '¯' => 'Ż'
];

$from = array_keys($replacements);
$to = array_values($replacements);

while (($sourceLine = fgets($sourceFile)) !== false) {
    $destinationLine = str_replace($from, $to, $sourceLine);
    fwrite($destinationFile, $destinationLine);
}

printf('Done in %fs.', microtime(true) - $start);
echo PHP_EOL;
