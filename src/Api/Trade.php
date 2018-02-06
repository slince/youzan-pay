<?php
/*
 * This file is part of the slince/youzan-pay package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\YouzanPay\Api;

class Trade
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $qrId;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var \DateTime
     */
    protected $created;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Trade
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getQrId()
    {
        return $this->qrId;
    }

    /**
     * @param int $qrId
     *
     * @return Trade
     */
    public function setQrId($qrId)
    {
        $this->qrId = $qrId;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return Trade
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     *
     * @return Trade
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }
}