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

use GuzzleHttp\Client;
use Slince\YouzanPay\Api\QRCode;
use Slince\YouzanPay\Api\Token;
use Slince\YouzanPay\Api\Trade;

class Requestor
{
    /**
     * @var ApiContext
     */
    protected static $apiContext;

    /**
     * @var Client
     */
    protected static $httpClient;

    /**
     * @var Token
     */
    protected static $token;

    /**
     * 获取 access token.
     *
     * @var string
     */
    const GET_TOKEN_ENDPOINT = 'https://open.youzan.com/oauth/token';

    /**
     * 创建支付二维码
     *
     * @var string
     */
    const CREATE_QR_ENDPOINT = 'https://open.youzan.com/api/oauthentry/youzan.pay.qrcode/3.0.0/create';

    /**
     * 获取支付二维码
     *
     * @var string
     */
    const GET_QR_ENDPOINT = 'https://open.youzan.com/api/oauthentry/youzan.pay.qrcode/3.0.0/get';

    /**
     * 获取qr对应的交易.
     *
     * @var string
     */
    const GET_TRADES_ENDPOINT = 'https://open.youzan.com/api/oauthentry/youzan.trades.qr/3.0.0/get';

    /**
     * 获取交易.
     *
     * @var string
     */
    const GET_TRADE_ENDPOINT = 'https://open.youzan.com/api/oauthentry/youzan.trade/3.0.0/get';

    /**
     * 获取access token.
     *
     * @return Token
     */
    public static function getAccessToken()
    {
        if (static::$token) {
            return static::$token;
        }
        $response = static::$httpClient->post(static::GET_TOKEN_ENDPOINT, [
            'form_params' => [
                'client_id' => static::$apiContext->getClientId(),
                'client_secret' => static::$apiContext->getClientSecret(),
                'grant_type' => 'silent',
                'kdt_id' => static::$apiContext->getKdtId(),
            ]
        ]);
        $json = \GuzzleHttp\json_decode((string) $response->getBody(), true);
        $expiresAt = new \DateTime($json['expires_in'] . ' seconds');

        return static::$token = new Token($json['access_token'], $expiresAt);
    }

    /**
     * 持久化qrcode.
     *
     * @param QRCode $qrCode
     */
    public static function persistQrCode(QRCode $qrCode)
    {
        $json = static::setRequest('post', static::CREATE_QR_ENDPOINT, [
            'qr_name' => $qrCode->getName(),
            'qr_price' => $qrCode->getPrice(),
            'qr_type' => $qrCode->getType(),
        ])['response'];
        $qrCode
            ->setId($json['qr_id'])
            ->setCode($json['qr_code'])
            ->setUrl($json['qr_url']);
    }

    /**
     * 检查QRCode支付结果.
     *
     * @param int|QRCode $qrCode
     *
     * @return bool
     */
    public static function checkQRCodePayResult($qrCode)
    {
        if ($qrCode instanceof QRCode) {
            $qrID = $qrCode->getId();
        } else {
            $qrID = $qrCode;
        }
        $json = static::setRequest('get', static::GET_TRADES_ENDPOINT, [
            'qr_id' => $qrID,
            'status' => 'TRADE_RECEIVED',
        ])['response'];

        return $json['total_results'] > 0;
    }

    /**
     * 获取交易.
     *
     * @param int $id
     *
     * @return Trade
     */
    public static function getTrade($id)
    {
        $json = static::setRequest('get', static::GET_TRADE_ENDPOINT, [
            'tid' => $id,
        ])['response'];
        $trade = new Trade();
        $trade->setId($id)
            ->setCreated(new \DateTime($json['created']))
            ->setQrId($json['qr_id'])
            ->setStatus($json['status']);

        return $trade;
    }

    protected static function setRequest($method, $uri, $parameters)
    {
        $token = static::getAccessToken();
        if (!$token->isValid()) {
            throw new \InvalidArgumentException(sprintf('The token "%s" is invalid', $token->getAccessToken()));
        }
        $parameters['access_token'] = $token->getAccessToken();

        if (strcasecmp($method, 'post') === 0) {
            $options['form_params'] = $parameters;
        } else {
            $options['query'] = $parameters;
        }

        $response = static::$httpClient->request($method, $uri, $options);

        $json = \GuzzleHttp\json_decode((string) $response->getBody(), true);

        if (isset($json['error_response'])) {
            throw new \RuntimeException(sprintf('Error request, "%d": "%s"',
                $json['error_response']['code'],
                $json['error_response']['msg']
            ));
        }

        return $json;
    }

    /**
     * @param ApiContext $apiContext
     */
    public static function setApiContext($apiContext)
    {
        self::$apiContext = $apiContext;
    }

    /**
     * @param Client $httpClient
     */
    public static function setHttpClient($httpClient)
    {
        self::$httpClient = $httpClient;
    }

    /**
     * @param Token $token
     */
    public static function setToken($token)
    {
        self::$token = $token;
    }
}