<?php

namespace CashMachine\Test\Unit;

use CashMachine\Exception\NoteUnavailableException;
use CashMachine\Machine;

class CashMachineTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Machine
     */
    private $machine;


    protected function setUp()
    {
        parent::setUp();
        $this->machine = new Machine();
        $this->machine->setNoteValues(array(100.00, 50.00, 20.00, 10.00));
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->machine = null;
    }

    public function testSetsANoteValues()
    {
        $this->assertCount(4, $this->machine->getNoteValues());
        $this->assertEquals(100, $this->machine->getNoteValues()->offsetGet(0));
        $this->assertEquals(50, $this->machine->getNoteValues()->offsetGet(1));
        $this->assertEquals(20, $this->machine->getNoteValues()->offsetGet(2));
        $this->assertEquals(10, $this->machine->getNoteValues()->offsetGet(3));
    }

    public function testWithdraw30AndReceive20And10Notes()
    {
        $received = $this->machine->withdraw(30.00);

        $this->assertEquals(array(20.00, 10.00), $received);
    }

    public function testWithdraw80AndReceive50And20And10Notes()
    {
        $received = $this->machine->withdraw(80.00);

        $this->assertEquals(array(50.00, 20.00, 10.00), $received);
    }

    /**
     * @expectedException \CashMachine\Exception\NoteUnavailableException
     * @expectedExceptionMessage There are no notes available for this value (125.00).
     */
    public function testWithdraw125AndCatchANoteUnavailableException()
    {
        $this->machine->withdraw(125.00);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The value is not allowed.
     */
    public function testWithdrawNegative130AndCatchAInvalidArgumentException()
    {
        $this->machine->withdraw(-130.00);
    }


    public function testWithdrawNullAndReceiveEmptyArray()
    {
        $received = $this->machine->withdraw(null);

        $this->assertEquals(array(), $received);
    }
}
