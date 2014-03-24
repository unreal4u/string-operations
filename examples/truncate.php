<?php

include('../src/unreal4u/stringOperations.php');
$stringOperations = new unreal4u\stringOperations();

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


$testArray = array('Cañete', 'Föllinge', 'ÑÖÑÚ', '漢A字BC', '汉A字BC', '𠜎𠜱𠝹𠱓');
$stringOperations->maximumDeviation = 0;

$output = array();
foreach ($testArray as $testString) {
    $output[$testString] = $stringOperations->truncate($testString, 3, '', '');
}

var_dump($output);

$input = 'Hello, this must be some spectacular -a: test-';
var_dump(mb_strlen($input));
$stringOperations->maximumDeviation = 10;

$firstOutput = $stringOperations->truncate($input, 37, array('-', ':', ' '));
$secondOutput = $stringOperations->truncate($input, 37, array(':', '-', ' '));
var_dump($firstOutput);
// Should give: Hello, this must be some spectacular ...
var_dump($secondOutput);
// Should give: Hello, this must be some spectacular -a...

var_dump('all done');