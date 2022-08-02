<?php 
// 密钥
define("SECRET_KEY", 23);
// 产生长度为 16字节 的随机 iv 向量，一个字符在计算机内部使用8比特表示，即一个字节
function get_random_init_vector() {
	$random_iv = '';
    for ($i = 0;$i < 16;$i++) {
        $random_iv .= chr(rand(1, 128));
    }
    return $random_iv;
}

// 加密运算
function encode($data){
	$tmp = "";
	// 和密钥异或
	for ($i = 0;$i < strlen($data);$i++) {
        $tmp .= chr(ord($data[$i]) ^ SECRET_KEY);
    }
    return $tmp;
}


// 解密运算
function decode($data){
	$tmp = "";
	// 和密钥异或
	for ($i = 0;$i < strlen($data);$i++) {
        $tmp .= chr(ord($data[$i]) ^ SECRET_KEY);
    }
    return $tmp;
}

// CBC 加密
function cbc_encode($plain, $len){
	$iv = get_random_init_vector();
	// 分段
	$plain_tmp = array();
	for($i = 0;$i < $len;$i++){
		$plain_tmp[$i] = substr($plain, $i * 16, 16);
	}
	$cipher = "";
	// 对于每一段明文都要进行加密
	$last_cipher = $iv;
	for($i = 0;$i < $len;$i++){
		// 上一段密文先和本明文做异或运算
		$str_tmp = "";
		for ($j = 0; $j < 16; $j++){
			$str_tmp .= chr(ord($last_cipher[$j]) ^ ord($plain_tmp[$i][$j]));
		}

		// 再进行加密
		$cipher_i = encode($str_tmp);
		$cipher .= $cipher_i;
		$last_cipher = $cipher_i;
	}
	return array(
		'iv' => base64_encode($iv),
		'cipher' => base64_encode($cipher)
	);
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
		die("消息被篡改！" . substr($plain, 0, 16) . "是错误的！");
	}
	return array(
		'plain' => $plain
	);
}


$mydata = "I               love             you.............";
$info = cbc_encode($mydata, 3);
echo "数据是：\n";
echo $mydata . "\n";
echo "加密后：" . "\n";
var_dump($info);

echo "根据加密后得到的密文进行解密，得到的数据：" . "\n";

$data = cbc_decode($info['cipher'], $info['iv'], 3);
var_dump($data);
?>