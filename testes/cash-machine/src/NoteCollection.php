<?php

namespace CashMachine;

use CashMachine\Exception\NoteUnavailableException;

/**
 * Simulation of a Cash Machine when an user withdraw cash from its account.
 * @package CashMachine
 */
class NoteCollection extends \ArrayObject
{

    /**
     * Get all available notes.
     * @return array
     */
    public function getNotes()
    {
        $notes = array_keys($this->getArrayCopy());

        return $notes;
    }

    /**
     * Get the lowest available note in collection
     * @return float
     */
    public function getLowestNote()
    {
        $notes = $this->getNotes();
        $lowest = min($notes);

        return $lowest;
    }

    /**
     * Verify availability of note.
     *
     * @param float $note
     *
     * @return bool
     */
    public function isAvailable($note)
    {

        if (!$this->offsetExists($note)) {
            return false;
        }

        return (int)$this->offsetGet($note) > 0;
    }

    /**
     * Get the minimum note and calculate availability for given value.
     *
     * @param float $value
     *
     * @throws NoteUnavailableException
     */
    public function isAllowedValue($value)
    {
        $lowest = $this->getLowestNote();
        $result = $value % $lowest;
        if ($result !== 0 or !$this->isAvailable($lowest)) {
            throw new NoteUnavailableException(
                sprintf('There are no notes available for this value (%01.2f).', $value)
            );
        }
    }

    /**
     * Descend sort the notes.
     * @return void
     */
    public function descSort()
    {
        $this->uksort(function ($a, $b) {
            return ($a === $b ? 0 : ($a < $b ? 1 : -1));
        });
    }

}
