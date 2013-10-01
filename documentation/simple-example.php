<?php

$contentString = file_get_contents('lorem-ipsum.txt');

include('../stringOperations.class.php');

$stringOperations = new u4u\stringOperations();

$text = $stringOperations->truncate($contentString);

var_dump($text);


$string = 'hello bye';

$text = $stringOperations->truncate($string, 8);

var_dump($text);
