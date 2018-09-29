<?php
/**
 * Created by PhpStorm.
 * User: Winter
 * Date: 2018/9/29
 * Time: 14:58
 */
include '../config/config.php';

$orderAmount = $_POST['orderAmount'];
$prdOrdNo = $_POST['prdOrdNo'];
$notifyUrl = $_POST['notifyUrl'];

$data = [
    'merParam'      => "book",
    'signType'      => "MD5",
    'merId'         => $merchantId,
    'orderStatus'   => "01",
    'payTime'       => "20180929144450",
    'payId'         => "1045927116287336448",
    'transType'     => "008",
    'prdOrdNo'      => $prdOrdNo,
    'orderAmount'   => $orderAmount,
    'versionId'     => "1.1",
    'synNotifyUrl'  => "http://default.com/api/pay/h5",
    'asynNotifyUrl' => $notifyUrl,
];

//生成签名
ksort($data);
$string='';
foreach ($data as $key => $value){
    if($value != ''){
        $string .= $key.'='.$value.'&';
    }
}
$string .= 'key='. $signKey;
$data['signData'] = strtoupper(md5($string));

//发送回调
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$data['asynNotifyUrl']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

echo $response;