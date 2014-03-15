<?php

namespace CashMachine\Test\Unit;

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
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->machine = null;
    }

    public function testSetsANoteValues()
    {
        $this->machine->setNoteValues(array(100, 50, 20, 10));

        $this->assertCount(4, $this->machine->getNoteValues());
        $this->assertEquals(100, $this->machine->getNoteValues()->offsetGet(0));
        $this->assertEquals(50, $this->machine->getNoteValues()->offsetGet(1));
        $this->assertEquals(20, $this->machine->getNoteValues()->offsetGet(2));
        $this->assertEquals(10, $this->machine->getNoteValues()->offsetGet(3));
    }

    public function provider()
    {
        return array(
            array(array(100)),
            array(array(100, 50)),
            array(array(100, 50, 20)),
            array(array(100, 50, 20, 10)),
            array(array(50, 20, 10)),
            array(array(20, 10)),
            array(array(10)),
        );
    }

    public function testWithdraw30AndReceive20And10Notes()
    {
        $this->machine->setNoteValues(array(100, 50, 20, 10));

        $received = $this->machine->withdraw(30);

        $this->assertEquals(array(20.00, 10.00), $received);
    }
}
