<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/25 13:20
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\queue\rabbit;


use AMQPChannel;
use AMQPConnection;
use AMQPConnectionException;
use AMQPEnvelope;
use AMQPExchange;
use AMQPExchangeException;
use AMQPQueue;
use AMQPQueueException;
use app\common\utils\queue\QueueBase;
use Exception;

class RabbitBase extends QueueBase
{
    /** MQ Channel
     * @var AMQPChannel
     */
    public $AMQPChannel;

    /** MQ Link
     * @var AMQPConnection
     */
    public $AMQPConnection;

    /** MQ Envelope
     * @var AMQPEnvelope
     */
    public $AMQPEnvelope;

    /** MQ Exchange
     * @var AMQPExchange
     */
    public $AMQPExchange;

    /** MQ Queue
     * @var AMQPQueue
     */
    public $AMQPQueue;

    /** conf
     * @var
     */
    public $conf;

    /** exchange
     * @var
     */
    public $exchange;

    /** link
     * BaseMQ constructor.
     * @throws AMQPConnectionException
     */
    public function __construct()
    {
        parent::__construct();
        $config     = $this->getConfig();
        $this->conf = $config['host'];
        $this->exchange       = $config['exchange'];

        $this->AMQPConnection = new AMQPConnection($this->conf);
        if (!$this->AMQPConnection->connect()) {
            throw new AMQPConnectionException('无法连接到代理');
        }
    }

    /**
     * close link
     */
    public function close()
    {
        $this->AMQPConnection->disconnect();
    }

    /** Channel
     * @return AMQPChannel
     * @throws AMQPConnectionException
     */
    public function channel()
    {
        if (!$this->AMQPChannel) {
            $this->AMQPChannel = new AMQPChannel($this->AMQPConnection);
        }
        return $this->AMQPChannel;
    }

    /** Exchange
     * @return AMQPExchange
     * @throws AMQPConnectionException
     * @throws AMQPExchangeException
     */
    public function exchange()
    {
        if (!$this->AMQPExchange) {
            $this->AMQPExchange = new AMQPExchange($this->channel());
            $this->AMQPExchange->setName($this->exchange);
        }
        return $this->AMQPExchange;
    }

    /** queue
     * @return AMQPQueue
     * @throws AMQPConnectionException
     * @throws AMQPQueueException
     */
    public function queue()
    {
        if (!$this->AMQPQueue) {
            $this->AMQPQueue = new AMQPQueue($this->channel());
        }
        return $this->AMQPQueue;
    }

    /** Envelope
     * @return AMQPEnvelope
     */
    public function envelope()
    {
        if (!$this->AMQPEnvelope) {
            $this->AMQPEnvelope = new AMQPEnvelope();
        }
        return $this->AMQPEnvelope;
    }
}