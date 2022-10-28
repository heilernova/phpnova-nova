<?php

require __DIR__ . '/../vendor/autoload.php';

use Phpnova\Nova\Database\DB;
use Phpnova\Nova\Database\DBConfig;

try {
    
    
    // $pdo = DBConfig::connect()->mysql('root', '', 'ftc_assosiations');
    // $res = DB::table('tb_associations_affiliates')->get('1 = 1');
    // $res = DB::table('tb_associations_affiliates')->getAll();
    // $res = DB::table('tb_associations_questions')->insert(['association' => 1, 'question' => 'hola'], '*');


    header("content-type: application/json");
    echo json_encode($res);

} catch (\Throwable $th) {
    //throw $th;
    header("content-type: text/plain");
    echo "Error\n";
    echo "Message: " . $th->getMessage() . "\n";
    echo "File: " . $th->getFile() . "\n";
    echo "Line: " . $th->getLine();
}