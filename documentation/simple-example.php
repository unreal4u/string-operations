<?php

include('../stringOperations.class.php');
$stringOperations = new u4u\stringOperations();

echo '<pre>';

$contentString = file_get_contents('lorem-ipsum.txt');
$text = $stringOperations->truncate($contentString);
print_r($text);
echo '<br />'.str_repeat('-', 80).'<br />';

$string = 'hello bye';
$text = $stringOperations->truncate($string, 8);
print_r($text);
echo '<br />'.str_repeat('-', 80).'<br />';

$text = $stringOperations->truncate($contentString, 650, ' ');
print_r($text);
echo '<br />'.str_repeat('-', 80).'<br />';

echo '</pre>';