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

    public function testIWithdraw30AndGetNotes20And10()
    {
        $received = $this->machine->withdraw(30.00);

        $this->assertEquals(array(20.00, 10.00), $received);
    }

    public function testIWithdraw80AndGetNotes50And20And10()
    {
        $received = $this->machine->withdraw(80.00);

        $this->assertEquals(array(50.00, 20.00, 10.00), $received);
    }

    /**
     * @expectedException \CashMachine\Exception\NoteUnavailableException
     * @expectedExceptionMessage There are no notes available for this value (125.00).
     */
    public function testITryWithdraw125AndGetANoteUnavailableException()
    {
        $this->machine->withdraw(125.00);
    }

    /**
     * @expectedException \CashMachine\Exception\NoteUnavailableException
     * @expectedExceptionMessage There are no notes available for this value (50.00).
     */
    public function testMachineHasNotes100And20ButITryWithdraw50SoGetANoteUnavailableException()
    {
        $this->machine->setNoteValues(array(100.00, 20.00));
        $this->machine->withdraw(50.00);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The value is not allowed.
     */
    public function testITryWithdrawNegative130AndGetAInvalidArgumentException()
    {
        $this->machine->withdraw(-130.00);
    }


    public function testITryWithdrawNullAndIReceiveAnEmptyArray()
    {
        $received = $this->machine->withdraw(null);

        $this->assertEquals(array(), $received);
    }
}
