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

class ApiContext
{
    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var string
     */
    protected $kdtId;

    public function __construct($clientId, $clientSecret, $kdtId)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->kdtId = $kdtId;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     *
     * @return ApiContext
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     *
     * @return ApiContext
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * @return string
     */
    public function getKdtId()
    {
        return $this->kdtId;
    }

    /**
     * @param string $kdtId
     *
     * @return ApiContext
     */
    public function setKdtId($kdtId)
    {
        $this->kdtId = $kdtId;

        return $this;
    }
}