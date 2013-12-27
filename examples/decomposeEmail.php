<?php

include('../src/unreal4u/stringOperations.php');

$testEmails = array(
    'my@name.com',
    '+%22My%22+%3Cmy%40name.com%3E',
    '=?utf-8?B?5L2p5ae/?= <my@name.com.tw>',
    'My Name <my@name.com>',
);

$stringOperations = new unreal4u\stringOperations();

foreach ($testEmails as $testEmail) {
    $output[] = $stringOperations->decomposeCompleteEmail($testEmail);
}

var_dump($output);
