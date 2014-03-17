<?php

namespace CashMachine;

/**
 * Simulation of a Cash Machine when an user withdraw cash from its account.
 * @package CashMachine
 */
class Machine
{

    /**
     * @var NoteCollection
     */
    protected $availableNotes;

    /**
     * @var AccountInterface
     */
    protected $account;

    public function __construct(AccountInterface $account, NoteCollection $availableNotes = null)
    {
        $this->setAccount($account);
        !is_null($availableNotes) && $this->setAvailableNotes($availableNotes);
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
     * @param NoteCollection $availableNotes
     */
    public function setAvailableNotes(NoteCollection $availableNotes)
    {
        $this->availableNotes = $availableNotes;
    }

    /**
     * @return NoteCollection
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

        $this->getAvailableNotes()->isAllowedValue($value);

        $this->getAccount()->withdraw($value);

        return $this->getNotesFromValue($value);
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
    public function getNotesFromValue($value)
    {
        $this->getAvailableNotes()->descSort();

        $noteIterator = $this->getAvailableNotes()->getIterator();

        $notes = array();
        while ($value > 0 and $noteIterator->valid()) {
            $note = $noteIterator->key();
            $noteAvailable = $noteIterator->current();
            if ((int)$noteAvailable > 0 and $value >= $note) {
                $quantity = intval($value / $note);
                $notes = array_merge($notes, array_pad(array(), $quantity, $note));
                $value -= ($note * $quantity);
                continue;
            }
            $noteIterator->next();
        }

        return $notes;
    }
}
