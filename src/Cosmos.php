<?php

namespace Cosmos;

use Cosmos\Content;
use Cosmos\Storage\Cache;
use Cosmos\Exceptions\NoApiKeyException;
use Cosmos\Exceptions\NoPrivateKeyException;

class Cosmos{

  private $apiKey, $privateKey;
  private $contents = array();

  public function __construct($apiKey = null)
  {
    if($apiKey){
      $this->apiKey = $apiKey;
    }
    return $this;
  }

  public function setApiKey($key)
  {
    $this->apiKey = $key;
  }
  public function getApiKey()
  {
    return $this->apiKey;
  }

  public function setPrivateKey($key)
  {
    if(is_file($key)){
      $key = file_get_contents($key);
    }
    $this->privateKey = openssl_pkey_get_private(base64_decode($key));
  }

  public function getPrivateKey()
  {
    return $this->privateKey;
  }

  public function setCachePath($path)
  {
    Cache::getInstance($path);
  }
  public function saveCache()
  {
    Cache::getInstance()->save();
  }

  public function setRefreshRate($secs)
  {
    Cache::getInstance()->setRefreshRate($secs);
  }

  public function get($api_name)
  {
    if(!$this->apiKey){
      throw new NoApiKeyException();
    }
    if(!$this->privateKey){
      throw new NoPrivateKeyException();
    }
    if(in_array($api_name, $this->contents)){
      return $this->contents[$api_name];
    }

    $content = new Content($this, $api_name);
    $content->fetch();
    $this->contents[$api_name] = $content;

    return $content;
  }

}
