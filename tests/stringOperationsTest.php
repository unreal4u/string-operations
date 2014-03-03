<?php

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
        $mapValues[4] = array('hello bye', 6, ' ', '...', 100, 'hello...');
        // Max 10 chars, with 25% deviation
        $mapValues[5] = array('hello bye-cruel world', 10, ' ', '...', 25, 'hello bye-cru...');
        // Max 9 chars, 0% deviation, no appending string
        $mapValues[6] = array('hello bye cruel world', 9, ' ', '', 0, 'hello bye');
        // Max 12 chars, 0% deviation, no appending string
        $mapValues[7] = array('hello bye cruel world', 12, ' ', '', 0, 'hello bye cr');
        // Max 10 chars, 25% deviation, separator is a dash and appending string is a colon
        $mapValues[8] = array('hello-bye-cruel-world', 10, '-', ':', 25, 'hello-bye:');
        // "Normal" case
        $mapValues[9] = array('this is a bigger text with a lot of spaces in it', 15, ' ', '...', 10, 'this is a bigger...');
        // Setting the delimiter just after a space but without enough deviation to fit until the next word
        $mapValues[10] = array('this is a bigger text with a lot of spaces in it', 17, ' ', '...', 10, 'this is a bigger...');
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

        // Added on 2014-02-24, multiple delimiters
        $mapValues[21] = array('Hello, this must be some spectacular -a: test-', 37, array('-', ':', ' '), '...', 10, 'Hello, this must be some spectacular ...');
        $mapValues[22] = array('Hello, this must be some spectacular -a: test-', 37, array(':', '-', ' '), '...', 10, 'Hello, this must be some spectacular -a...');
        $mapValues[23] = array('Hello, this must be some spectacular a: test-',  37, array('-', ' ', ':'), '...', 10, 'Hello, this must be some spectacular...');

        $mapValues[24] = array('Youwillnotfindanythinghere', 10, array(' ', ',', '.', '_'), '...', 0, 'Youwillnot...');
        $mapValues[25] = array('Youwillnotfindanythinghere', 10, array(' ', ',', '.', '_'), '...', 10, 'Youwillnot...');

        return $mapValues;
    }

    /**
     * Tests truncate function
     *
     * @dataProvider provider_truncate
     * @group truncate
     */
    public function test_truncate($string, $limit, $delimiter, $append, $deviation, $expected) {
        $this->stringOperations->maximumDeviation = $deviation;
        $result = $this->stringOperations->truncate($string, $limit, $delimiter, $append);
        $this->assertEquals($expected, $result);
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
     * Tests decomposeCompleteEmail function
     *
     * @dataProvider provider_decomposeCompleteEmail
     * @group decomposeCompleteEmail
     */
    public function test_decomposeCompleteEmail($email, $expected) {
        $result = $this->stringOperations->decomposeCompleteEmail($email);
        $this->assertEquals($expected, $result);
    }

    /**
     * Data provider for test_mimeHeaderDecode
     */
    public function provider_mimeHeaderDecode() {
        $mapValues[] = array('=?ISO-8859-1?Q?B=F8lla?=, med =?ISO-8859-1?Q?=F8l?= i baggen', array(
            0 => createStdClass::__set_state(array('charset' => 'ISO-8859-1', 'text' => 'Bølla',)),
            1 => createStdClass::__set_state(array('charset' => 'default', 'text' => ', med ',)),
            2 => createStdClass::__set_state(array('charset' => 'ISO-8859-1', 'text' => 'øl',)),
            3 => createStdClass::__set_state(array('charset' => 'default', 'text' => ' i baggen',)),
        ));

        $mapValues[] = array('=?utf-8?B?5L2p5ae/?=', array(
            0 => createStdClass::__set_state(array('charset' => 'utf-8', 'text' => '佩姿',)),
        ));

        $mapValues[] = array('=?iso-8859-1?Q?B=F8lla?=, med =?ISO-8859-1?Q?=F8l?= i baggen', array(
            0 => createStdClass::__set_state(array('charset' => 'iso-8859-1', 'text' => 'Bølla',)),
            1 => createStdClass::__set_state(array('charset' => 'default', 'text' => ', med ',)),
            2 => createStdClass::__set_state(array('charset' => 'ISO-8859-1', 'text' => 'øl',)),
            3 => createStdClass::__set_state(array('charset' => 'default', 'text' => ' i baggen',)),
        ));

        $mapValues[] = array('=?US-ASCII?Q?Keith_Moore?=', array(
            0 => createStdClass::__set_state(array('charset' => 'US-ASCII', 'text' => 'Keith Moore',)),
        ));

        $mapValues[] = array('=?ISO-8859-1?Q?Andr=E9?= Pirard', array(
            0 => createStdClass::__set_state(array('charset' => 'ISO-8859-1', 'text' => 'André',)),
            1 => createStdClass::__set_state(array('charset' => 'default', 'text' => ' Pirard',)),
        ));

        // Copied from PHP source code, this is the only test in the whole codebase that tests imap_mime_header_decode()
        $s = '=?UTF-8?Q?=E2=82=AC?=';
        $header = "$s\n $s\n\t$s";
        $mapValues[] = array($header, array(
            0 => createStdClass::__set_state(array('charset' => 'UTF-8', 'text' => '€')),
            1 => createStdClass::__set_state(array('charset' => 'UTF-8', 'text' => '€')),
            2 => createStdClass::__set_state(array('charset' => 'UTF-8', 'text' => '€')),
        ));

        // From php.net, was problematic case 11 years ago
        $mapValues[] = array('=?utf-7?Q?Petra_M+APw-ller?=', array(
            0 => createStdClass::__set_state(array('charset' => 'utf-7', 'text' => 'Petra Müller')),
        ));

        $mapValues[] = array('=?utf-8?Q?Petra_M=C3=BCller?=', array(
            0 => createStdClass::__set_state(array('charset' => 'utf-8', 'text' => 'Petra Müller')),
        ));

        // Don't forget the simplest of cases
        $mapValues[] = array("=?ISO-8859-1?Q?Keld_J=F8rn_Simonsen?= <keld@example.com>", array(
            0 => createStdClass::__set_state(array('charset' => 'ISO-8859-1', 'text' => 'Keld Jørn Simonsen')),
            1 => createStdClass::__set_state(array('charset' => 'default', 'text' => ' <keld@example.com>')),
        ));

        $mapValues[] = array('Hello world', array(
            0 => createStdClass::__set_state(array('charset' => 'default', 'text' => 'Hello world')),
        ));

        return $mapValues;
    }

    /**
     * Tests mimeHeaderDecode
     *
     * @group mimeHeaderDecode
     * @dataProvider provider_mimeHeaderDecode
     * @param string $text
     * @param stdClass $expected
     */
    public function test_mimeHeaderDecode($text, $expected) {
        $result = $this->stringOperations->mimeHeaderDecode($text);
        $this->assertEquals($expected, $result);
    }

    /**
     * Data provider for test_createSlug
     *
     * @return array
     */
    public function provider_createSlug() {
        // Testing invalid data
        $mapValues[]  = array('', true, '');
        $mapValues[]  = array('', false, '');
        $mapValues[]  = array(true, true, '');
        $mapValues[]  = array(false, true, '');
        $mapValues[]  = array(null, true, '');
        $mapValues[]  = array(array(), true, '');

        // Testing normal usage cases
        $mapValues[]  = array('hello', false, 'hello');
        $mapValues[]  = array('hello', true, 'hello');
        $mapValues[]  = array('/hello/', false, '/hello/');
        $mapValues[] = array('/hello/', true, 'hello');
        $mapValues[] = array('hello/world', false, 'hello/world');
        $mapValues[] = array('hello/world', true, 'hello-world');
        $mapValues[] = array('hélló wórld', false, 'hello-world');
        $mapValues[] = array('hélló wórld', true, 'hello-world');
        $mapValues[] = array('hello-----world', false, 'hello-world');
        $mapValues[] = array('hello-----world', true, 'hello-world');
        $mapValues[] = array('hello/////world', false, 'hello/world');
        $mapValues[] = array('hello/////world', true, 'hello-world');
        $mapValues[] = array('   hello__world   ', false, 'hello-world');
        $mapValues[] = array('   hello__world   ', true, 'hello-world');

        // Testing edge-cases
        $mapValues[] = array('𠜎𠜱𠝹𠱓', false, '');
        $mapValues[] = array('مرحبا cruel العالم', false, 'cruel');
        $mapValues[] = array('.,/a<\\!"#$%&/()=?¡+{}[]b>-_', true, 'a-b');
        $mapValues[] = array('.,/a<\\!"#$%&/()=?¡+{}[]b>-_', false, '/a/b');
        $mapValues[] = array('////a b//-/cd////', true, 'a-b-cd');
        $mapValues[] = array('////a b//-/cd////e/f///', false, '/a-b/cd/e/f/');

        return $mapValues;
    }

    /**
     * Tests createSlug function
     *
     * @dataProvider provider_createSlug
     * @group createSlug
     */
    public function test_createSlug($string, $convertSlash, $expected) {
        $result = $this->stringOperations->createSlug($string, $convertSlash);
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests magic toString method
     */
    public function test___toString() {
        $output = sprintf($this->stringOperations);
        $this->assertStringStartsWith('stringOperations', $output);
    }
}
