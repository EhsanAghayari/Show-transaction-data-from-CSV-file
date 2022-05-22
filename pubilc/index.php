<?php

declare(strict_types=1);

$Path = dirname(__DIR__) . DIRECTORY_SEPARATOR;

define('APP_PATH', $Path . 'app' . DIRECTORY_SEPARATOR);
define('TRANS_PATH', $Path .'transactions' . DIRECTORY_SEPARATOR);
define('VIEW_PATH', $Path .'view' . DIRECTORY_SEPARATOR);

require APP_PATH . "App.php";

$files = getDataFiles(TRANS_PATH);


$transactions = [];

foreach($files as $file){
    $transactions = array_merge($transactions, getTransactions($file, 'extractTransaction'));
}

$total = calculate($transactions);

require VIEW_PATH . 'view.php';