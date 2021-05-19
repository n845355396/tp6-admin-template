<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 17:14
 * @Description: 阿里发起支付参数类
 * 
 * @return 

 */

namespace app\common\utils\payment\vo;


use JsonSerializable;

class AliPayParamsVo implements JsonSerializable
{
    /**
     *支付body
     * @var
     */
    private $body;
    /**
     * 支付标题
     * @var
     */
    private $subject;
    /**
     * 本地系统订单号
     * @var
     */
    private $out_trade_no;
    /**
     * 支付金额
     * @var
     */
    private $total_amount;

    /**
     * 支付金额
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->total_amount;
    }

    /**
     * 设置支付金额
     * @param mixed $total_amount
     */
    public function setTotalAmount($total_amount): void
    {
        $this->total_amount = $total_amount;
    }

    /**
     * 本地系统订单号
     * @return mixed
     */
    public function getOutTradeNo()
    {
        return $this->out_trade_no;
    }

    /**
     * 设置本地系统订单号
     * @param mixed $out_trade_no
     */
    public function setOutTradeNo($out_trade_no): void
    {
        $this->out_trade_no = $out_trade_no;
    }

    /**
     * 支付body
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * 支付body
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * 支付标题
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * 支付标题
     * @param mixed $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize()
    {
        $data = [];
        foreach ($this as $key => $val) {
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }
}