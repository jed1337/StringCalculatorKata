<?php

class StringCalculatorTest extends \PHPUnit\Framework\TestCase{
    public $calc;

    function __construct(){
        parent::__construct();
        $this->calc = new StringCalculator();
    }

    public function setUp():void {
        $this->calc = new StringCalculator;
    }

	public function testAddNumbers(){
        $this->assertEquals(0, $this->calc->add(""));

        $this->assertEquals(1, $this->calc->add("1"));

        $this->assertEquals(3, $this->calc->add("1,2"));

        $this->assertEquals(10, $this->calc->add("1,2,3,4"));
        $this->assertEquals(15, $this->calc->add("5,4,3,2,1,0"));
	}

	public function testAddNumbersWithNewline(){
        $this->assertEquals(2, $this->calc->add("1\n1"));
        $this->assertEquals(9, $this->calc->add("1\n3\n5"));
    }

    public function testAddNumbersWithNewlineAndComma(){
        $this->assertEquals(6, $this->calc->add("1\n2,3"));
    }

    public function testCustomDelimiter(){
        $this->assertEquals(10, $this->calc->add("//;\n4;6"));
        $this->assertEquals(15, $this->calc->add("//[\n10[5"));
    }

    public function testExceptionForSingleNegativeNumbers(){
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Negative numbers are not allowed: -1");

        $this->calc->add("-1");
    }

    public function testExceptionForMultipleNegativeNumbers(){
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Negative numbers are not allowed: -10,-2,-03");

        $this->calc->add("2,-10,3,-2,15,-03");
    }

    public function testIgnoreNumbersBiggerThan1000() {
        $this->assertEquals(1000, $this->calc->add("1000"));
        $this->assertEquals(2, $this->calc->add("2,1001"));
        $this->assertEquals(1010, $this->calc->add("1000,7,1001,1,9876,2"));
    }

    public function testsMultipleCharacterDelimiter(){
        $this->assertEquals(6, $this->calc->add("//[***]\n1***2***3"));
        $this->assertEquals(11, $this->calc->add("//[_ZA_WARUDO_]\n4_ZA_WARUDO_2_ZA_WARUDO_3_ZA_WARUDO_2"));
        $this->assertEquals(5, $this->calc->add("//[時よ止まれ]\n0時よ止まれ2時よ止まれ3"));
    }
}