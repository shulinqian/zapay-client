<?php
namespace thinkweb\zapay;

class Client{

    public function __construct($config = [])
    {
        if($config){
            $this->setConfig($config);
        }
    }

    protected $config = [];

    /**
     * @return mixed
     */
    public function getConfig($key = null)
    {
        if($key === null){
            return $this->config;
        }
        return isset($this->config[$key]) ? $this->config[$key] : null;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function bind($post){
        $sign = $this->gav($post, 'sign');
        $return_str = $this->gav($post, 'return_str');
        if(!$sign || !$return_str){
            return false;
        }
        return $this->auth($sign, $post);
    }

    public function auth($sign, $data)
    {
        if(isset($data['sign'])){
            unset($data['sign']);
        }
        $signAuth = $this->getSign($data);
        return $sign === $signAuth;
    }

    public function genData($api,  $args = []){
        $args['appid'] = $this->getConfig('appid');
        $args['api'] = $api;
        $args['timestamp'] = time();
        $args['sign'] = $this->getSign($args);
        return $args;
    }

    protected function getSign($data){
        if(is_array($data)){
            ksort($data);
            $data = json_encode($data, JSON_NUMERIC_CHECK);
        }
        $appid = $this->getConfig('appid');
        $key = $this->getConfig('key');
        return md5($data . $appid . $key);
    }

    public function createButton($post, $attr ='', $title = '立即支付'){
        $attr = $attr ? $attr : 'class="btn btn-primary"';
        $gateway = $this->getConfig('gateway');
        $post['appid'] = $this->getConfig('appid');

        $post['sign'] = $this->getSign($post);
        $element = '';
        foreach ($post as $key => $item) {
            $element .= "<input type=\"hidden\" name=\"{$key}\" value=\"{$item}\" />";
        }
        return <<<EOF
<form action="{$gateway}" method="post" target="_blank">
    {$element}
    <input type="submit" {$attr} value="{$title}" />
</form>
EOF;

    }

    public function notify(){

    }

    protected function gav($post, $key, $def = null){
        return isset($post[$key]) ? $post[$key] : $def;
    }
}