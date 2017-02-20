<?php
/**
 * User: qianjin
 * Date: 2017/2/18 0018
 * Time: 14:02
 */
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

    protected function auth($sign, $data)
    {
        unset($data['sign']);
        if(is_array($data)){
            ksort($data);
            $data = json_encode($data, JSON_NUMERIC_CHECK);
        }
        $appid = $this->getConfig('appid');
        $key = $this->getConfig('key');
        return $sign === md5($data . $appid . $key);
    }

    public function createLink($post)
    {

    }

    public function notify(){

    }

    protected function gav($post, $key, $def = null){
        return isset($post[$key]) ? $post[$key] : $def;
    }
}