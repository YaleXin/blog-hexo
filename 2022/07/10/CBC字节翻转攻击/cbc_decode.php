<?php 
// cbc_decode.php
// 密钥
define("SECRET_KEY", 23);

// 解密运算
function decode($data){
	$tmp = "";
	// 和密钥异或
	for ($i = 0;$i < strlen($data);$i++) {
        $tmp .= chr(ord($data[$i]) ^ SECRET_KEY);
    }
    return $tmp;
}

// CBC 解密
function cbc_decode($cipher, $iv, $len){
	$cipher = base64_decode($cipher);
	$iv     = base64_decode($iv);
	// 分段
	$cipher_tmp = array();
	for($i = 0;$i < $len;$i++){
		$cipher_tmp[$i] = substr($cipher, $i * 16, 16);
	}
	$plain = "";
	// 对于每一段密文都要进行解密
	$last_cipher = $iv;
	for($i = 0;$i < $len;$i++){
		// 先进行解密
		$plain_i_tmp = decode($cipher_tmp[$i]);

		// 再和上一段密文做异或运算
		$str_tmp = "";
		for ($j = 0; $j < 16; $j++){
			$str_tmp .= chr(ord($last_cipher[$j]) ^ ord($plain_i_tmp[$j]));
		}
		$plain .= $str_tmp;
		$last_cipher = $cipher_tmp[$i];
	}
	if (substr($plain, 0, 16) !== "I               "){
		die("消息被篡改！" . base64_encode($plain) . "是错误的！");
	}
	return array(
		'plain' => $plain
	);
}


$info = array();
$info['iv'] = $argv[1];
$info['cipher'] = $argv[2];
$data = cbc_decode($info['cipher'], $info['iv'], 3);
var_dump($data);
?>