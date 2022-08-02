<?php

function get_random_init_vector() {
   $random_iv = '';
    for ($i = 0;$i < 16;$i++) {
        $random_iv .= chr(rand(1, 128));
    }
    return $random_iv;
}

$iv = get_random_init_vector();
$data = 'this is a test..';
$tmp = '';
for ($i = 0; $i < 16; $i++){
   $tmp .= chr(ord($data[$i]) ^ ord($iv[$i]));
}

echo $tmp . "\n";

$fin = '';

for ($i = 0; $i < 16; $i++){
   $fin .= chr(ord($tmp[$i]) ^ ord($iv[$i]));
}
echo $fin;
?>