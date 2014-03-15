<?php
/**
 * Created by JetBrains PhpStorm.
 * User: a2system
 * Date: 14/03/14
 * Time: 21:37
 * To change this template use File | Settings | File Templates.
 */

namespace CashMachine;


use CashMachine\Exception\NoteUnavailableException;

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
        $this->hasNoteAvailableFor($value);

        $noteIterator = $this->getNoteValues()->getIterator();

        $dispenser = new \ArrayObject();

        while ($value > 0 and $noteIterator->valid()) {
            $note = $noteIterator->current();
            if ($value >= $note) {
                $dispenser->append($note);
                $value -= $note;
                continue;
            }
            $noteIterator->next();
        }

        return $dispenser->getArrayCopy();
    }

    protected function hasNoteAvailableFor($value)
    {
        $noteIterator = new \ArrayIterator($this->getNoteValues()->getArrayCopy());

        $noteIterator->uasort(function ($a, $b) {
            return $a > $b ? 1 : -1;
        });

        $noteIterator->rewind();
        $note = $noteIterator->current();
        $result = $value % $note;
        if ($result !== 0) {
            throw new NoteUnavailableException(
                sprintf('There are no notes available for this value (%01.2f).', $value)
            );
        }
    }
}