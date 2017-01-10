<?php

namespace Cosmos\Security;

class Encryption{

  public static function decrypt($enc_body, $enc_cipher, $cosmos)
  {
    $apiKey = $cosmos->getApiKey();
    $privateKey = $cosmos->getPrivateKey();

    $enc_cipher = hex2bin($enc_cipher);
    openssl_private_decrypt($enc_cipher, $cipher, $privateKey);

    $enc_body = hex2bin($enc_body);
    $body = openssl_decrypt($enc_body, 'AES-128-CBC', $apiKey, OPENSSL_RAW_DATA, $cipher);

    return $body;
  }

}
