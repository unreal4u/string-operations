<?php
require_once '../stringOperations.class.php';
require_once 'PHPUnit/Framework/TestCase.php';

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
            $this->stringOperations = new u4u\stringOperations();
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
        $mapValues[] = array('hellohello', 10, ' ', '...', 5, 'hellohello');
        // Max 8 chars, with 5% deviation
        $mapValues[] = array('hello bye', 8, ' ', '...', 5, 'hello bye');
        // Special case: truncate after 6 chars, with 5% deviation
        $mapValues[] = array('hello bye', 6, ' ', '...', 5, 'hello b...');
        // Max 6 chars, with 100% deviation (which is in fact 12 chars)
        $mapValues[] = array('hello bye', 6, ' ', '...', 100, 'hello bye');
        // Max 10 chars, with 25% deviation
        $mapValues[] = array('hello bye-cruel world', 10, ' ', '...', 25, 'hello bye-cru...');
        // Max 9 chars, 0% deviation, no appending string
        $mapValues[] = array('hello bye cruel world', 9, ' ', '', 0, 'hello bye');
        // Max 12 chars, 0% deviation, no appending string
        $mapValues[] = array('hello bye cruel world', 12, ' ', '', 0, 'hello bye cr');
        // Max 10 chars, 25% deviation, separator is a dash and appending string is a colon
        $mapValues[] = array('hello-bye-cruel-world', 10, '-', ':', 25, 'hello-bye-cru:');
        // "Normal" case
        $mapValues[] = array('this is a bigger text with a lot of spaces in it', 15, ' ', '...', 10, 'this is a bigger...');
        // Setting the delimiter just after a space but without enough deviation to fit until the next word
        $mapValues[] = array('this is a bigger text with a lot of spaces in it', 17, ' ', '...', 10, 'this is a bigger te...');
        // Special UTF-8 chars testing, set all these tests to cut at exactly one point in the string
        $mapValues[] = array('NormalText', 3, '', '', 0, 'Nor');
        $mapValues[] = array('Canñete', 3, '', '', 0, 'Can');
        $mapValues[] = array('e', 3, '', '', 0, 'e');
        $mapValues[] = array('', 3, '', '', 0, '');
        // 2 bytes chars
        $mapValues[] = array('Cañete', 3, '', '', 0, 'Cañ');
        $mapValues[] = array('Föllinge', 3, '', '', 0, 'Föl');
        $mapValues[] = array('ÑÖÑÚ', 3, '', '', 0, 'ÑÖÑ');
        // 3 bytes chars
        $mapValues[] = array('漢A字BC', 3, '', '', 0, '漢A字');
        $mapValues[] = array('汉A字BC', 3, '', '', 0, '汉A字');
        // 4 bytes chars
        $mapValues[] = array('𠜎𠜱𠝹𠱓', 3, '', '', 0, '𠜎𠜱𠝹');

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
}