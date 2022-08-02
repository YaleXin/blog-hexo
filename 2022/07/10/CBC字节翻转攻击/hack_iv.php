<?php
// hack_iv.php
$origin_iv = base64_decode("bFQYFlR5BVdfHChTUGspWA==");
$plain = "I               love             you.............";
$plain_erro = base64_decode("TS4iICAgICAgICAgICAgIGhhdGUgICAgICAgICAgICAgeW91Li4uLi4uLi4uLi4u");
for ($i = 0; $i < 16; $i++){
	// iv_new = plain ⊕ plain_erro ⊕ iv_old
	$origin_iv[$i] = chr(ord($plain[$i]) ^ ord($plain_erro[$i]) ^ ord($origin_iv[$i]));
}
echo base64_encode($origin_iv);
?>