<?php

/**
 * Require all the autoload :-)
 */
require __DIR__ . '/../bootstrap/autoload.php';

if (!isset($_GET['q'])) {
    return false;
}

$query = [];

$q = $_GET['q'];
$q = decrypt($q);
$q = explode('&', $q);

foreach ($q as $p) {
    $parts = explode('=', $p);
    $query[$parts[0]] = $parts[1];
}

// TODO-PR3: add appropriate headers

$file = ROOT_DIR . $query['file'];

if (!file_exists($file)) {
    return false;
}

switch (strtolower($query['type'])) {
    case 'img':
        $image = imagecreatefromstring(file_get_contents($file));
        header('Content-type: image/png');
        imagepng($image);
        imagedestroy($image);
        break;
    default:
        $data = file_get_contents($file);
        echo $data;
        break;
}