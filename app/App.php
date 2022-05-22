<?php

declare(strict_types=1);


function getDataFiles(string $filePath): array{
    
    $files = [];

    foreach(scandir($filePath) as $subFile){
        if (is_dir($subFile)){
            continue;
        }

        $files[] = $filePath . $subFile;
    }
    return $files;
}


function getTransactions(string $fileName, ?callable $handler = null): array{
        if(! file_exists($fileName)){
            trigger_error("File did not Found",E_USER_ERROR);
        }

        $files = fopen($fileName, 'r');

        fgetcsv($files);

        $transactions = [];

        while(($transaction = fgetcsv($files)) !== false){

            if ($handler !== null){
                $transaction = $handler($transaction);
            }

            $transactions[] = $transaction;
        }
        
        return $transactions;
}


function extractTransaction(array $transactionRow): array{
    
    [$date, $checkNumber, $description, $amount] = $transactionRow;
    
    $amount = (float) str_replace(['$',','],"",$amount);
    
    return ['date' => $date, 'checkNumber' => $checkNumber, 'description' => $description, 'amount' => $amount];
}

function calculate(array $transactions){
    $transactionTotal = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];

    foreach($transactions as $SingleRow){
        $transactionTotal['netTotal'] += $SingleRow['amount'];

    if($SingleRow['amount'] >= 0){
        $transactionTotal['totalIncome'] += $SingleRow['amount'];
    }else{
        $transactionTotal['totalExpense'] += $SingleRow['amount'];}}

    return $transactionTotal;
}

function formatDollar(float $money): string{

    $isNegative = $money < 0;

    return ($isNegative ? '-' : '') . '$' . number_format(abs($money), 2);

}

function formatDate(string $date): string{
    return date('M j, Y', strtotime($date));
}