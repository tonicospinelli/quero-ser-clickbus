<?php

namespace CashMachine\Test\Unit;

use CashMachine\Account;
use CashMachine\Exception\NoteUnavailableException;
use CashMachine\Machine;
use CashMachine\NoteCollection;

class CashMachineTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Machine
     */
    private $machine;

    protected function setUp()
    {
        parent::setUp();
        $this->machine = new Machine(
            new Account(),
            new NoteCollection(array(
                100 => true,
                50  => true,
                20  => true,
                10  => true
            ))
        );
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->machine = null;
    }

    public function testGetAllAvailableNotes()
    {
        $availableNotes = $this->machine->getAvailableNotes()->getArrayCopy();

        $this->assertEquals(array(100 => true, 50 => true, 20 => true, 10 => true), $availableNotes);
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

    public function testIWithdraw30AndGetThreeNotesOf10()
    {
        $this->machine->setAvailableNotes(new NoteCollection(array(10 => true)));
        $received = $this->machine->withdraw(30.00);

        $this->assertEquals(array(10.00, 10.00, 10.00), $received);
    }

    public function testIWithdraw40AndGetTwoNotesOf20()
    {
        $this->machine->setAvailableNotes(new NoteCollection(array(20 => true)));
        $received = $this->machine->withdraw(40.00);

        $this->assertEquals(array(20.00, 20.00), $received);
    }

    public function testIWithdraw50AndGetOneNoteOf50()
    {
        $this->machine->setAvailableNotes(new NoteCollection(array(50 => true)));
        $received = $this->machine->withdraw(50.00);

        $this->assertEquals(array(50), $received);
    }
    public function testIWithdraw150AndGetThreeNotesOf50()
    {
        $this->machine->setAvailableNotes(new NoteCollection(array(50 => true)));
        $received = $this->machine->withdraw(150);

        $this->assertEquals(array(50, 50, 50), $received);
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
        $this->machine->setAvailableNotes(new NoteCollection(array(100 => true, 50 => false, 20 => true)));
        $this->machine->withdraw(50.00);
    }

    public function testMachineHasNotes100And10IWithdraw10AndGetNote10()
    {
        $this->machine->setAvailableNotes(new NoteCollection(array(100 => true, 10 => true)));
        $received = $this->machine->withdraw(10.00);

        $this->assertEquals(array(10.00), $received);
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
