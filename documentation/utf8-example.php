<?php

$testArray = array('Cañete', 'Föllinge', 'ÑÖÑÚ', '漢A字BC', '汉A字BC', '𠜎𠜱𠝹𠱓');

include('../stringOperations.class.php');

$stringOperations = new u4u\stringOperations();
$stringOperations->maximumDeviation = 0;

foreach ($testArray as $testString) {
    $output[$testString] = $stringOperations->truncate($testString, 3, '', '');
}

var_dump($output);