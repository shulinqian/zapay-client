# zapay-client
支付中心客户端类
###站点接入代码

需要实现notify 和 back,2个api接口,可以参照 Base.php


1.notify用于异步通知
    
    必须实现 bind和callback接口,

  
2.back用于反回结果展示


```
//请修改为自己的配置
$config = [
    'gateway' => "http://***.com/pay/index/index",
    'apiurl' => "http://***.com/api/payment/index",
    'appid' => '**site',
    'key' => '01e7891ea2d7914ssb3e9430254f6257',
];
$client = new Client();
$client->setConfig($config);


```
3.到支付中心填入相应的地址