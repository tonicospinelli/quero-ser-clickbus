<?php
/**
 * Created by JetBrains PhpStorm.
 * User: a2system
 * Date: 15/03/14
 * Time: 00:03
 * To change this template use File | Settings | File Templates.
 */

namespace CashMachine;


interface AccountInterface
{

    /**
     * @return bool
     */
    public function hasBalance();

    /**
     * @param float $value
     *
     * @return bool
     */
    public function withdraw($value);
}
