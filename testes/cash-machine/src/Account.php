<?php
/**
 * Created by JetBrains PhpStorm.
 * User: a2system
 * Date: 15/03/14
 * Time: 00:03
 * To change this template use File | Settings | File Templates.
 */

namespace CashMachine;


use CashMachine\Exception\AccountWithoutBalanceException;

class Account implements AccountInterface
{

    /**
     * @return bool
     */
    public function hasBalance()
    {
        return true;
    }

    /**
     * @param float $value
     *
     * @throws Exception\AccountWithoutBalanceException
     * @return bool
     */
    public function withdraw($value)
    {
        if (!$this->hasBalance()) {
            throw new AccountWithoutBalanceException('There are no balance in account');
        }

        return true;
    }
}