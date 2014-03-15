<?php
/**
 * Created by JetBrains PhpStorm.
 * User: a2system
 * Date: 14/03/14
 * Time: 21:37
 * To change this template use File | Settings | File Templates.
 */

namespace CashMachine;


class Machine {

    protected $noteValues;

    /**
     * @param mixed $noteValues
     */
    public function setNoteValues($noteValues)
    {
        $this->noteValues = $noteValues;
    }

    /**
     * @return mixed
     */
    public function getNoteValues()
    {
        return $this->noteValues;
    }
}