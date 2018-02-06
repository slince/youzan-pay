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

use Slince\YouzanPay\Amount;

/**
 *  WAIT_BUYER_PAY = "WAIT_BUYER_PAY",.
    // 待确认，包括（待成团：拼团订单、待接单：外卖订单）
    WAIT_CONFIRM = "WAIT_CONFIRM",
    // 等待卖家发货，即:买家已付款
    WAIT_SELLER_SEND_GOODS = "WAIT_SELLER_SEND_GOODS",
    // 等待买家确认收货,即:卖家已发货
    WAIT_BUYER_CONFIRM_GOODS = "WAIT_BUYER_CONFIRM_GOODS",
    // 买家已签收
    TRADE_BUYER_SIGNED = "TRADE_BUYER_SIGNED",
    // 交易成功
    TRADE_SUCCESS = "TRADE_SUCCESS",
    // 交易关闭
    TRADE_CLOSED = "TRADE_CLOSED"
 */
class QRCode
{
    protected $type = 'QR_TYPE_DYNAMIC';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $price;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $source;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $url;

    public function __construct($price = null, $name = null, $source = null)
    {
        $price && $this->setPrice($price);
        $this->name = $name;
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return QRCode
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * 获取价格
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * 设置收取的价格
     *
     * @param int|Amount $price
     *
     * @return QRCode
     */
    public function setPrice($price)
    {
        if ($price instanceof Amount) {
            $price = $price->getNumber();
        }
        $this->price = $price;

        return $this;
    }

    /**
     * 获取收款理由.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 设置收款理由.
     *
     * @param string $name
     *
     * @return QRCode
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     *
     * @return QRCode
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

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
     * @return QRCode
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return QRCode
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return QRCode
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * 从数组创建 charge 对象
     *
     * @param array $array
     *
     * @return QRCode
     */
    public static function fromArray($array)
    {
        if (array_diff_key(['name' => null, 'price' => null, 'source' => null], $array)) {
            throw new \InvalidArgumentException('You must provide an array of keys containing "name", "price" and "source"');
        }

        return new static($array['price'], $array['name'], $array['source']);
    }
}