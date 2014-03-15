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
        $this->assertTrue(in_array(100, $this->machine->getNoteValues()));
        $this->assertTrue(in_array(50, $this->machine->getNoteValues()));
        $this->assertTrue(in_array(20, $this->machine->getNoteValues()));
        $this->assertTrue(in_array(10, $this->machine->getNoteValues()));
    }

}
