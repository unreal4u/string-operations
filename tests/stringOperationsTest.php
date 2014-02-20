<?php

require 'vendor/autoload.php';
require 'src/unreal4u/stringOperations.php';

/**
 * stringOperations test case.
 */
class stringOperationsTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var localization
     */
    private $stringOperations;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp();
        try {
            $this->stringOperations = new unreal4u\stringOperations();
        } catch (\Exception $e) {
            $this->markTestSkipped('mbstring not installed');
        }
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        $this->stringOperations = null;
        parent::tearDown();
    }

    /**
     * Data provider for test_truncate
     * @return array
     */
    public function provider_truncate() {
        // Max 10 chars, with 5% deviation
        $mapValues[1] = array('hellohello', 10, ' ', '...', 5, 'hellohello');
        // Max 8 chars, with 5% deviation
        $mapValues[2] = array('hello bye', 8, ' ', '...', 5, 'hello bye');
        // Special case: truncate after 6 chars, with 5% deviation
        $mapValues[3] = array('hello bye', 6, ' ', '...', 5, 'hello b...');
        // Max 6 chars, with 100% deviation (which is in fact 12 chars)
        $mapValues[4] = array('hello bye', 6, ' ', '...', 100, 'hello bye');
        // Max 10 chars, with 25% deviation
        $mapValues[5] = array('hello bye-cruel world', 10, ' ', '...', 25, 'hello bye-cru...');
        // Max 9 chars, 0% deviation, no appending string
        $mapValues[6] = array('hello bye cruel world', 9, ' ', '', 0, 'hello bye');
        // Max 12 chars, 0% deviation, no appending string
        $mapValues[7] = array('hello bye cruel world', 12, ' ', '', 0, 'hello bye cr');
        // Max 10 chars, 25% deviation, separator is a dash and appending string is a colon
        $mapValues[8] = array('hello-bye-cruel-world', 10, '-', ':', 25, 'hello-bye-cru:');
        // "Normal" case
        $mapValues[9] = array('this is a bigger text with a lot of spaces in it', 15, ' ', '...', 10, 'this is a bigger...');
        // Setting the delimiter just after a space but without enough deviation to fit until the next word
        $mapValues[10] = array('this is a bigger text with a lot of spaces in it', 17, ' ', '...', 10, 'this is a bigger te...');
        // Special UTF-8 chars testing, set all these tests to cut at exactly one point in the string
        $mapValues[11] = array('NormalText', 3, '', '', 0, 'Nor');
        $mapValues[12] = array('Canñete', 3, '', '', 0, 'Can');
        $mapValues[13] = array('e', 3, '', '', 0, 'e');
        $mapValues[14] = array('', 3, '', '', 0, '');
        // 2 bytes chars
        $mapValues[15] = array('Cañete', 3, '', '', 0, 'Cañ');
        $mapValues[16] = array('Föllinge', 3, '', '', 0, 'Föl');
        $mapValues[17] = array('ÑÖÑÚ', 3, '', '', 0, 'ÑÖÑ');
        // 3 bytes chars
        $mapValues[18] = array('漢A字BC', 3, '', '', 0, '漢A字');
        $mapValues[19] = array('汉A字BC', 3, '', '', 0, '汉A字');
        // 4 bytes chars
        $mapValues[20] = array('𠜎𠜱𠝹𠱓', 3, '', '', 0, '𠜎𠜱𠝹');

        return $mapValues;
    }

    /**
     * @dataProvider provider_truncate
     */
    public function test_truncate($string, $limit, $delimiter, $append, $deviation, $expected) {
        $this->stringOperations->maximumDeviation = $deviation;
        $result = $this->stringOperations->truncate($string, $limit, $delimiter, $append);
        $this->assertEquals($result, $expected);
    }

    /**
     * Data provider for test_decomposeCompleteEMail
     *
     * @return array
     */
    public function provider_decomposeCompleteEmail() {
        // Invalid data
        $mapValues[1]  = array(null, array('name' => '', 'email' => ''));
        $mapValues[2]  = array(true, array('name' => '', 'email' => ''));
        $mapValues[3]  = array(false, array('name' => '', 'email' => ''));
        $mapValues[4]  = array(null, array('name' => '', 'email' => ''));
        $mapValues[5]  = array(1, array('name' => '', 'email' => ''));
        $mapValues[6]  = array(3.1415, array('name' => '', 'email' => ''));
        $mapValues[7]  = array('Hello world', array('name' => '', 'email' => ''));

        // "Valid" data
        $mapValues[8]  = array('my@name.com', array('name' => '', 'email' => 'my@name.com'));
        $mapValues[9]  = array('my%40name.com', array('name' => '', 'email' => 'my@name.com'));
        $mapValues[10] = array('My+Name+%3Cmy%40name.com%3E', array('name' => 'My Name', 'email' => 'my@name.com'));
        $mapValues[11] = array('+%22My%22+%3Cmy%40name.com%3E', array('name' => 'My', 'email' => 'my@name.com'));
        $mapValues[12] = array('=?utf-8?B?5L2p5ae/?= <my@name.com.tw>', array('name' => '佩姿', 'email' => 'my@name.com.tw'));
        $mapValues[13] = array('=?ISO-8859-1?Q?B=F8lla?=, med =?ISO-8859-1?Q?=F8l?= i baggen <my@name.com.tw>', array('name' => 'Bølla , med øl i baggen', 'email' => 'my@name.com.tw'));
        $mapValues[14] = array('=?iso-8859-1?Q?B=F8lla?=, med =?iso-8859-1?Q?=F8l?= i baggen <my@name.com.tw>', array('name' => 'Bølla , med øl i baggen', 'email' => 'my@name.com.tw'));
        $mapValues[15] = array('=?US-ASCII?Q?Keith_Moore?= <moore@cs.utk.edu>', array('name' => 'Keith Moore', 'email' => 'moore@cs.utk.edu'));
        $mapValues[16] = array('=?ISO-8859-1?Q?Andr=E9?= Pirard <PIRARD@vm1.ulg.ac.be>', array('name' => 'André Pirard', 'email' => 'PIRARD@vm1.ulg.ac.be'));
        $mapValues[17] = array('My Name <my@name.com>', array('name' => 'My Name', 'email' => 'my@name.com'));
        $mapValues[18] = array('"My Name" <my@name.com>', array('name' => 'My Name', 'email' => 'my@name.com'));
        $mapValues[19] = array(' "My" <my@name.com.ar>', array('name' => 'My', 'email' => 'my@name.com.ar'));
        $mapValues[20] = array('"My" < my@name.com> ', array('name' => 'My', 'email' => 'my@name.com'));
        $mapValues[21] = array(' "My"    <  my@name.com >   ', array('name' => 'My', 'email' => 'my@name.com'));

        return $mapValues;
    }

    /**
     * @dataProvider provider_decomposeCompleteEmail
     */
    public function test_decomposeCompleteEmail($email, $expected) {
        $result = $this->stringOperations->decomposeCompleteEmail($email);
        $this->assertEquals($result, $expected);
    }

    /**
     * Data provider for test_createSlug
     *
     * @return array
     */
    public function provider_createSlug() {
        $mapValues[1]  = array('hello', false, 'hello');
        $mapValues[2]  = array('hello', true, 'hello');
        $mapValues[3]  = array('/hello/', false, '/hello/');
        $mapValues[4]  = array('/hello/', true, 'hello');
        $mapValues[5]  = array('hello/world', false, 'hello/world');
        $mapValues[6]  = array('hello/world', true, 'hello-world');
        $mapValues[7]  = array('hélló wórld', false, 'hello-world');
        $mapValues[8]  = array('hélló wórld', true, 'hello-world');
        $mapValues[9]  = array('hello-----world', false, 'hello-world');
        $mapValues[10] = array('hello-----world', true, 'hello-world');
        $mapValues[11] = array('hello/////world', false, 'hello/world');
        $mapValues[12] = array('hello/////world', true, 'hello-world');

        return $mapValues;
    }

    /**
     * @dataProvider provider_createSlug
     */
    public function test_createSlug($string, $convertSlash, $expected) {
        $result = $this->stringOperations->createSlug($string, $convertSlash);
        $this->assertEquals($result, $expected);
    }

    /**
     * Tests magic toString method
     */
    public function test___toString() {
        $output = sprintf($this->stringOperations);
        $this->assertStringStartsWith('stringOperations', $output);
    }
}