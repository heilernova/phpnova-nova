<?php

$_ENV['nvx']['db']['pdo'] = null;

$_ENV['nvx']['config'] = []; # La configuración del app.json

$_ENV['nvx']['directories']['upload'] = "files";
$_ENV['nvx']['directories']['project'] = "";

$_ENV['nvx']['db']['timezone'] = null; # PDO por defautl
$_ENV['nvx']['db']['pdo'] = null; # PDO por defautl
$_ENV['nvx']['db']['client'] = null;

$_ENV['nvx']['database']['handles']['query']['parce-writing-style'] = null;
$_ENV['nvx']['database']['handles']['result']['parce-writing-style'] = null;
$_ENV['nvx']['database']['list'] = []; # Conexiones a las bases de datos

# Router
$_ENV['nvx']['router']['routes'] = [];

# Handles
$_ENV['nvx']['handles']['response'] = null;
$_ENV['nvx']['handles']['error'] = null;

# Información request
$_ENV['nvx']['request']['url'] = "";
$_ENV['nvx']['request']['ip'] = "";
$_ENV['nvx']['request']['method'] = "";
$_ENV['nvx']['request']['body'] = null;
$_ENV['nvx']['request']['files'] = [];
$_ENV['nvx']['request']['params'] = [];
$_ENV['nvx']['request']['query-params'] = $_GET;
$_ENV['nvx']['request']['platform'] = '';
$_ENV['nvx']['request']['device'] = '';


$_ENV['nvx']['response'][''] = "";

require __DIR__ . '/../Database/_env.php';