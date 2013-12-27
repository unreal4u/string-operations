<?php

include('../src/unreal4u/stringOperations.php');

$stringOperations = new unreal4u\stringOperations();

$result = $stringOperations->createSlug(__FILE__);
var_dump($result);

$result = $stringOperations->createSlug(__FILE__, false);
var_dump($result);

$result = $stringOperations->createSlug('th3/$3 áin\'t n0 vá/lïd char$...');
var_dump($result);

$result = $stringOperations->createSlug('Th3/$3 ÁiN\'t n0 Vá/lÏd ch4R$...', false);
var_dump($result);

echo $stringOperations;
