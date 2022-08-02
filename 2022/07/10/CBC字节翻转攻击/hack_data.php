<?php
// hack_data.php
$origin_iv = base64_decode("bFQYFlR5BVdfHChTUGspWA==");
$origin_cipher = base64_decode("MmMvIWNOMmBoKx9kZ1web0kbTlNUeQVXXxwoU1BrKVh+dTYxbUA8bmYlEWppUhBh");
$hack_msg = "hate            ";
$plain = "I               love             you.............";
for($i = 0; $i < 16; $i++){
	// x = Plain[2][1] ⊕ cipher[1][1] ⊕ 'a'
	$origin_cipher[$i] = chr(ord($plain[$i + 16])  ^ ord($origin_cipher[$i]) ^ ord($hack_msg[$i]));
}
$new_cipher = base64_encode($origin_cipher);
echo $new_cipher;
?>