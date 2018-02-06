# 有赞支付解决方案

利用有赞云和有赞微小店实现个人收款解决方案

[![Build Status](https://img.shields.io/travis/slince/youzan-pay/master.svg?style=flat-square)](https://travis-ci.org/slince/youzan-pay)
[![Latest Stable Version](https://img.shields.io/packagist/v/slince/youzan-pay.svg?style=flat-square&label=stable)](https://packagist.org/packages/slince/youzan-pay)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/slince/youzan-pay.svg?style=flat-square)](https://scrutinizer-ci.com/g/slince/youzan-pay/?branch=master)


## Installation

使用 Composer 安装

```bash
$ composer require slince/youzan-pay
```

## Basic Usage

- 创建 api 上下文和服务对象

```php
$apiContext = new Slince\YouzanPay\ApiContext(CLIENT_ID, CLIENTD_SECRET, KDT_ID);

$youzanPay = new Slince\YouzanPay\YouzanPay($apiContext);
```

- 发起付款

```php
$qrCode = $youzanPay->charge([
    'name' => '测试收款',
    'price' => 1, //单位是分
    'source' => '自身系统的订单id'
]);

echo  "<img src=\"{$qrCode->getCode()}\">"; //用户可以使用微信或者支付宝扫描付款
```
方法返回 [`Slince\YouzanPay\QRCode`](./src/Api/QRCode.php) 对象。

- 验证付款

```php
$qrCodeId = ...
$result = $youzanPay->checkQRStatus($qrCodeId);

var_dump($result); //布尔类型
```

- 处理推送

怎么设置推送网上有太多帖子这里不再赘述，假设你已经设置好了推送：

```php

$data = $youzanPay->verifyWebhook($request); 

var_dump($data);
```
`$request` 对象可以是 `Symfony\Component\HttpFoundation\Request`， `Psr\Http\Message\ServerRequestInterface`,
这表示 symfony ， laravel， Sliex, CakePHP ，Slim 等框架用户可以直接将 request对象传给该方法；
`$request` 也可以为null或者数组； 所以如果你不使用任何框架也可以很方便的集成；

如果是合法的有赞推送消息，`$data` 是一个数组，结构如下(注：下面是个json结构)

```json
{
    "client_id":"6cd25b3f99727975b5",
    "id":"E20170807181905034500002",
    "kdt_id":63077,
    "kdt_name":"Qi码运动馆",
    "mode":1,
    "msg":"%7B%22update_time%22:%222017-08-07%2018:19:05%22,%22payment%22:%2211.00%22,%22tid%22:%22E20170807181905034500002%22,%22status%22:%22TRADE_CLOSED%22%7D",
    "sendCount":0,
    "sign":"5c15274ca4c079197c89154f44b20307",
    "status":"TRADE_CLOSED",
    "test":false,
    "type":"TRADE_ORDER_STATE",
    "version":1502101273
}
```
关于推送相关的文档可以参考 [这里](https://www.youzanyun.com/docs/guide/3401/3448)


接收到推送数据以后，你可以得到响应的 交易的id，但并不是你的订单id；用交易 id获取对应的二维码的id:

```php
$trade = $youzanPay->getTrade($data['id']); //上一步获取的id

echo $trade->getQrId();  //获取到二维码id后去你的数据库查询到订单，做相应的处理；
```

`$trade` 是 [`Slince\YouzanPay\Trade`](./src/Api/Trade.php) 的实例；注意，为了聚焦业务，该对象只保留了原生数据的部分字段；


## License
   
采用 [MIT](https://opensource.org/licenses/MIT) 开源许可证
