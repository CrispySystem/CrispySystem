<?php

use Hashids\Hashids;

/**
 * @param $data
 */
function pr($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

/**
 * @param $data
 */
function vd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

function showPlainError(string $message, bool $exit = true)
{
    echo '<h3 style="color:darkred;">[ ERROR ]</h3>';
    echo $message;
    if ($exit) {
        exit;
    }
}

function encrypt(string $input)
{
    $hashids = new Hashids();
    $hash = $hashids->encode(rand(0, 9999));
    $ivHashids = new Hashids('', 16);
    $iv = $ivHashids->encode(rand(0, 9999));
    $encrypted = openssl_encrypt($input, 'aes-256-ctr', $hash, 0, $iv);
    $return = $encrypted . '.' . $hash . '.' . $iv;
    $return = rawurlencode($return);
    return $return;
}

function decrypt(string $input)
{
    $input = rawurldecode($input);
    $input = explode('.', $input);
    if (sizeof($input) !== 3) {
        return false;
    }
    $return = openssl_decrypt($input[0], 'aes-256-ctr', $input[1], 0, $input[2]);
    return $return;
}