<?php

include('../src/unreal4u/stringOperations.php');

$testEmails = array(
	'my@name.com',
	'+%22My%22+%3Cmy%40name.com%3E',
	'=?utf-8?B?5L2p5ae/?= <my@name.com.tw>',
	'My Name <my@name.com>',
	'=?ISO-8859-1?Q?B=F8lla?=, med =?ISO-8859-1?Q?=F8l?= i baggen',
);

$stringOperations = new unreal4u\stringOperations();

$output = array();
foreach ($testEmails as $testEmail) {
    $output[] = $stringOperations->mimeHeaderDecode($testEmail);
}

var_dump($output);
