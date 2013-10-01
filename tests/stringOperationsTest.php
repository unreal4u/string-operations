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
        $this->stringOperations = new u4u\stringOperations();
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