<?php

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
        $this->isValid($value);

        $this->hasNoteAvailableFor($value);

        $noteIterator = $this->getNoteValues()->getIterator();

        $noteIterator->uasort(function ($a, $b) {
            return ($a === $b ? 0 : ($a < $b ? 1 : -1));
        });

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
            return ($a === $b ? 0 : ($a > $b ? 1 : -1));
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

    protected function isValid($value)
    {
        if (!is_numeric($value) or $value < 0) {
            throw new \InvalidArgumentException('The value is not allowed.');
        }
    }
}