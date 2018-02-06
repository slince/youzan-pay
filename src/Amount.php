<?php
/*
 * This file is part of the slince/youzan-pay package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\YouzanPay;

class Amount
{
    /**
     * @var int
     */
    protected $number;

    public function __construct($number)
    {
        $this->number = $number;
    }

    /**
     * 获取交易价格（分）.
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param float $price
     *
     * @return static
     */
    public static function Yuan($price)
    {
        return new static($price * 100);
    }
}