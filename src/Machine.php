<?php
/**
 * Created by JetBrains PhpStorm.
 * User: a2system
 * Date: 14/03/14
 * Time: 21:37
 * To change this template use File | Settings | File Templates.
 */

namespace CashMachine;


class Machine
{

    protected $noteValues;

    /**
     * @param mixed $noteValues
     */
    public function setNoteValues($noteValues)
    {
        $this->noteValues = new \ArrayObject($noteValues);
    }

    /**
     * @return \ArrayObject
     */
    public function getNoteValues()
    {
        return $this->noteValues;
    }

    public function withdraw($value)
    {
        $noteIterator = $this->getNoteValues()->getIterator();

        $dispenser = new \ArrayObject();

        while ($value > 0 and $noteIterator->valid()) {
            $note = $noteIterator->current();
            if($value >= $note){
                $dispenser->append($note);
                $value -= $note;
                continue;
            }
            $noteIterator->next();
        }

        return $dispenser->getArrayCopy();
    }
}