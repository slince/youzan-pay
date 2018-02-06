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

class Token
{
    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var int
     */
    protected $expiresAt;

    public function __construct($accessToken, \DateTime $expiresAt = null)
    {
        $this->accessToken = $accessToken;
        $this->expiresAt = $expiresAt;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * 是否是有效的token.
     *
     * @return bool
     */
    public function isValid()
    {
        if (!$this->expiresAt || $this->expiresAt->getTimestamp() > time()) {
            return true;
        }

        return false;
    }
}