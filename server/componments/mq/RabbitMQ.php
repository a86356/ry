<?php
namespace app\componments\mq;

use yii\base\Component;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use yii\base\ErrorException;

class RabbitMQ extends Component
{
    const EXCHANGE_TYPE_DIRECT = 'direct';

    const EXCHANGE_TYPE_FANOUT = 'fanout';

    const EXCHANGE_TYPE_TOPIC = 'topic';

    const EXCHANGE_TYPE_HEADER = 'header';

    const MESSAGE_DURABLE_YES = 2;

    const MESSAGE_DURABLE_NO = 1;

    private $_host = '127.0.0.1';

    private $_port = 5672;

    private $_user = 'rabbitmq_user';

    private $_passwd = 'eE123456';

    private $_vHost = '/';

    private $_connection = null;

    private $_queue = '';

    private $_exchange = '';

    /**
     * 组件初始化
     */
    public function init(){
        parent::init();
        //脚本退出前，关闭连接
        register_shutdown_function([$this,'close']);
    }

    /**
     * 连接
     */
    public function connect(){
        $this->getConnect();
    }

    /**
     * 关闭连接
     */
    public function close(){
        if($this->_isConnect()){
            $this->_connection->close();
        }
    }

    /**
     * 设置默认 queue
     * @param $queue
     */
    public function setDefaultQueue($queue){
        $this->_queue = $queue;

    }

    /**
     * 设置默认 exchange
     * @param $exchange
     */
    public function setDefaultExchange($exchange){
        $this->_exchange = $exchange;
    }

    /**
     * 发布消息
     * @param $message
     * @param $queue
     * @param $exchange
     * @param bool $passive 为true表示交换机没创建则报错,为false表示交换机没创建直接创建并投递
     * @param bool $durable 是否持久化
     * @param bool $exclusive 一个新的空的queue，将exclusive置为True，这样在consumer从RabbitMQ断开后会删除该queue
     * @param string $type 交换机类型
     * @param bool $auto_delete 当消费者获取消息后，是否自动移除该消息，如果为FALSE，需要消费者发送一个应答码来表示消费成功
     * @return bool
     */
    public function publishMessage($message,$queue,$exchange,$passive=false,$durable=true,$exclusive=false,$type=self::EXCHANGE_TYPE_DIRECT,$auto_delete=false){

      //Json::encode($send_msg),"create","business",false,true,false,RabbitMQ::EXCHANGE_TYPE_DIRECT
        $newChannel = $this->getChannel();
        $newQueue = isset($queue)?$queue:$this->_queue;
        $newExchange = isset($exchange)?$exchange:$this->_exchange;

        if($this->_prepare($newChannel,$newQueue,$newExchange,$passive,$durable,$exclusive,$type,$auto_delete)){
            $delivery_mode = ($durable)?self::MESSAGE_DURABLE_YES:self::MESSAGE_DURABLE_NO;
            $msg = new AMQPMessage($message, array('content_type' => 'text/plain', 'delivery_mode' => $delivery_mode));
            $newChannel->basic_publish($msg,$exchange);
            $newChannel->close();
            return true;
        }
        $newChannel->close();
        return false;
    }

    /**
     * 拉取消息
     * @param $queue
     * @param $exchange
     * @param bool $passive
     * @param bool $durable
     * @param bool $exclusive
     * @param string $type
     * @param bool $auto_delete
     * @return bool
     */
    public function getMessage($queue,$exchange,$passive=false,$durable=true,$exclusive=false,$type=self::EXCHANGE_TYPE_DIRECT,$auto_delete=false){
        $newChannel = $this->getChannel();
        $newQueue = isset($queue)?$queue:$this->_queue;
        $newExchange = isset($exchange)?$exchange:$this->_exchange;
        $mix = false;

        if($this->_prepare($newChannel,$newQueue,$newExchange,$passive,$durable,$exclusive,$type,$auto_delete)){
            $msg = $newChannel->basic_get($queue);
            if($msg){
                $newChannel->basic_ack($msg->delivery_info['delivery_tag']);
                $mix = $msg->body;
            }
        }
        $newChannel->close();
        return $mix;
    }

    /**
     * 订阅消息
     * @param $queue
     * @param $exchange
     * @param bool $passive
     * @param bool $durable
     * @param bool $exclusive
     * @param string $type
     * @param bool $auto_delete
     * @param bool $auto_ack
     * @param null $callback
     */
    public function consumeMessage($queue,$exchange,$passive=false,$durable=true,$exclusive=false,$type=self::EXCHANGE_TYPE_DIRECT,$auto_delete=false,$auto_ack=false,$callback=null)
    {
        $newChannel = $this->getChannel();
        $newQueue = isset($queue)?$queue:$this->_queue;
        $newExchange = isset($exchange)?$exchange:$this->_exchange;

        if($this->_prepare($newChannel,$newQueue,$newExchange,$passive,$durable,$exclusive,$type,$auto_delete)){

            $newChannel->basic_qos(0,1,false);
            $newChannel->basic_consume($queue,'',false,$auto_ack,$exclusive,false,$callback);
            while(count($newChannel->callbacks))
            {
                $newChannel->wait();
            }
        }
    }

    /**
     * @return bool
     */
    private function _isConnect(){
        if($this->_connection && $this->_connection->isConnected()){
            return true;
        }
        return false;
    }

    /**
     * @param $channel
     * @param $queue
     * @param $exchange
     * @param bool $passive
     * @param bool $durable
     * @param bool $exclusive
     * @param string $type
     * @param bool $auto_delete
     * @return bool
     */
    public function _prepare($channel,$queue,$exchange,$passive=false,$durable=true,$exclusive=false,$type=self::EXCHANGE_TYPE_DIRECT,$auto_delete=false){

        if($channel && is_a($channel,'\PhpAmqpLib\Channel\AMQPChannel')){
            $channel->queue_declare($queue,$passive,$durable,$exclusive,$auto_delete);
            $channel->exchange_declare($exchange,$type,$passive,$durable,$auto_delete);
            $channel->queue_bind($queue, $exchange);
            return true;
        }
        return false;
    }

    /**
     * @param $host
     */
    public function setHost($host){
        $this->_host = $host;
    }

    /**
     * @param $port
     */
    public function setPort($port){
        $this->_port = $port;
    }

    /**
     * @param $user
     */
    public function setUser($user){
        $this->_user = $user;
    }

    /**
     * @param $passwd
     */
    public function setPasswd($passwd){
        $this->_passwd = $passwd;
    }

    /**
     * @param $vHost
     */
    public function setVHost($vHost){
        $this->_vHost = $vHost;
    }

    /**
     * @return AMQPChannel
     * @throws ErrorException
     */
    public function getChannel(){
        return $this->getConnect()->channel();
    }

    /**
     * @return null|AMQPConnection
     * @throws ErrorException
     * @throws \yii\base\ExitException
     */
    public function getConnect(){
        if(!$this->_isConnect()){
            try{
                $this->_connection = new AMQPConnection($this->_host, $this->_port, $this->_user, $this->_passwd, $this->_vHost);
            } catch (\PhpAmqpLib\Exception\AMQPRuntimeException $e){
                throw new ErrorException('rabbitMQ server connect error',500,1);
            }
        }
        return $this->_connection;
    }
}
?>