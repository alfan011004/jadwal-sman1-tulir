<?php

$pngToDataUri = static function (string $filename): string {
    $path = dirname(__DIR__).DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.$filename;
    $contents = is_file($path) ? file_get_contents($path) : false;

    if (! is_string($contents) || ! str_starts_with($contents, "\x89PNG\r\n\x1a\n")) {
        return '';
    }

    return 'data:image/png;base64,'.base64_encode($contents);
};

return [
    'sman1' => $pngToDataUri('logo-sman1.png'),
    'sumasel' => $pngToDataUri('logo-sumasel.png'),
];