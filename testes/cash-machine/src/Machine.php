<?php

namespace CashMachine;

use CashMachine\Exception\NoteUnavailableException;

/**
 * Simulation of a Cash Machine when an user withdraw cash from its account.
 * @package CashMachine
 */
class Machine
{

    protected $availableNotes;

    /**
     * @var AccountInterface
     */
    protected $account;

    public function __construct(AccountInterface $account, array $availableNotes = array())
    {
        $this->setAccount($account);
        $this->setAvailableNotes($availableNotes);
    }

    /**
     * @param AccountInterface $account
     */
    public function setAccount(AccountInterface $account)
    {
        $this->account = $account;
    }

    /**
     * @return AccountInterface
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param array $availableNotes
     */
    public function setAvailableNotes(array $availableNotes)
    {
        $this->availableNotes = new \ArrayObject($availableNotes);
    }

    /**
     * @return \ArrayObject
     */
    public function getAvailableNotes()
    {
        return $this->availableNotes;
    }

    /**
     * Get the notes based on value.
     *
     * @param float $value
     *
     * @throws
     * @return array Return the array with notes.
     */
    public function withdraw($value)
    {
        if (is_null($value)) {
            return array();
        }

        $this->isValid($value);

        $this->hasAvailableNotes($value);

        $this->getAccount()->withdraw($value);

        return $this->convertValueToNotes($value);
    }

    /**
     * Get the minimum note and calculate availability for given value.
     *
     * @param float $value
     *
     * @throws NoteUnavailableException
     */
    protected function hasAvailableNotes($value)
    {
        $noteIterator = new \ArrayIterator($this->getAvailableNotes()->getArrayCopy());

        $noteIterator->uksort(function ($a, $b) {
            return ($a === $b ? 0 : ($a > $b ? 1 : -1));
        });

        $noteIterator->rewind();
        $note = $noteIterator->key();
        $available = $noteIterator->current();

        $result = $value % $note;
        if ($result !== 0 or !$available) {
            throw new NoteUnavailableException(
                sprintf('There are no notes available for this value (%01.2f).', $value)
            );
        }
    }

    /**
     * Allow positive values.
     *
     * @param float $value
     *
     * @throws \InvalidArgumentException
     */
    protected function isValid($value)
    {
        if (is_numeric($value) and $value < 0) {
            throw new \InvalidArgumentException('The value is not allowed.');
        }
    }

    /**
     * Get all notes based on given value.
     *
     * @param float $value
     *
     * @return array Return array with all notes.
     */
    protected function convertValueToNotes($value)
    {
        $noteIterator = $this->getAvailableNotes()->getIterator();

        $noteIterator->uksort(function ($a, $b) {
            return ($a === $b ? 0 : ($a < $b ? 1 : -1));
        });

        $notes = new \ArrayObject();

        while ($value > 0 and $noteIterator->valid()) {
            $note = $noteIterator->key();
            $noteAvailable = $noteIterator->current();
            if ($noteAvailable and $value >= $note) {
                $notes->append($note);
                $value -= $note;
                continue;
            }
            $noteIterator->next();
        }

        return $notes->getArrayCopy();
    }
}
