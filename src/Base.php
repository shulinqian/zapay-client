<?php
namespace thinkweb\zapay;

use think\Controller;
use thinkweb\zapay\Client;

abstract class Base extends Controller
{

    protected function getClient (){
        $config = [
            'gateway' => "",
            'apiurl' => "",
            'appid' => '',
            'key' => '',
        ];
        $client = new Client();
        $client->setConfig($config);
        return $client;
    }

    public function notify(){
        $do = $this->request->post('do');
        switch ($do){
            case 'bind':
                return $this->bind();
            case 'notify':
                return $this->notifyCallbak();
        }
    }

    public function back(){
        $post = $this->request->get();
        echo '<pre>';
        print_r($post);
        echo '<pre>';exit;
    }

    protected function notifyCallbak(){
        $post = $this->getPost();
        if(!isset($post['order_sn'])){
            abort('500', '参数错误');
        }
        $client = $this->getClient();
        //验证签名
        $check = $client->auth($post['sign'], $post);
        if(!$check){
            abort('500', '签名错误');
        }
        $order_sn = $post['order_sn'];
        $status = $post['status'];
        $order_no = $post['order_no'];
        cache('order_no_notify_callback', $post);

        //根据 order_sn status 执行 业务   输出success 后，服务端不再发送通知。除非手动通知
        echo 'success';exit;
    }

    protected function getPost(){
        $post = $this->request->post();
        if(!isset($post['sign'])){
            abort('500', '参数错误');
        }
        if(isset($post['do'])){
            unset($post['do']);
        }
        return $post;
    }

    protected function bind(){

        $client = $this->getClient();
        $post = $this->getPost();
        if($client->bind($post)){//参照微信，验证通过了就输出返回字段
            echo $post['return_str'];exit;
        }
        return null;
    }

}
