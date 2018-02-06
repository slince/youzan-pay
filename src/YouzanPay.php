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
use Psr\Http\Message\ServerRequestInterface as PSR7Request;
use Slince\YouzanPay\Api\QRCode;
use Slince\YouzanPay\Api\Token;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class YouzanPay
{
    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var ApiContext
     */
    protected $apiContext;

    public function __construct(ApiContext $apiContext, $options = [])
    {
        $this->apiContext = $apiContext;
        $this->applyOptions($options);
        Requestor::setApiContext($this->apiContext);
        Requestor::setHttpClient($this->httpClient);
    }

    /**
     * 发起交易.
     *
     * @param array $data
     *
     * @return QRCode
     */
    public function charge(array $data)
    {
        $qrCode = QRCode::fromArray($data);
        Requestor::persistQrCode($qrCode);

        return $qrCode;
    }

    /**
     * 检查支付结果.
     *
     * @param QRCode|int $qrCodeId
     *
     * @return bool
     */
    public function checkQRStatus($qrCodeId)
    {
        return Requestor::checkQRCodePayResult($qrCodeId);
    }

    /**
     * 获取trade.
     *
     * @param int $id
     *
     * @return Api\Trade
     */
    public function getTrade($id)
    {
        return Requestor::getTrade($id);
    }

    /**
     * 验证推送
     *
     * @param SymfonyRequest|PSR7Request|array|null $request
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function verifyWebhook($request = null)
    {
        if ($request instanceof SymfonyRequest) {
            $data = \GuzzleHttp\json_decode($request->getContent(), true);
        } elseif ($request instanceof PSR7Request) {
            $data = $request->getParsedBody();
        } elseif(is_array($request)) {
            $data = $request;
        } else {
            $data = \GuzzleHttp\json_decode(file_get_contents('php://input'), true);
        }
        if (!$this->verifySign($data)) {
            throw new \InvalidArgumentException('Bad Youzan message');
        }

        return $data;
    }

    protected function verifySign($data)
    {
        if (!isset($data['sign'])) {
            return false;
        }

        return $data['sign'] === md5($this->apiContext->getClientId()
                .$data['msg']
                .$this->apiContext->getClientSecret());
    }

    /**
     * 设置访问 token.
     *
     * @param string|Token $token
     */
    public function setAccessToken($token)
    {
        if (is_string($token)) {
            $token = new Token($token);
        }
        Requestor::setToken($token);
    }

    /**
     * 获取access token.
     *
     * @return Token
     */
    public function getAccessToken()
    {
        return Requestor::getAccessToken();
    }

    protected function applyOptions($options)
    {
        if (isset($options['httpClient'])) {
            $this->httpClient = $options['httpClient'];
        } else {
            $this->httpClient = new Client([
                'verify' => false,
            ]);
        }
    }
}