<?php

namespace Cosmos;

use Cosmos\Storage\Cache;
use Cosmos\Security\Encryption;
use Cosmos\Exceptions\NotFoundInApiException;

class Content{

  private $cosmos, $api_name, $type, $name, $body;

  public function __construct($cosmos, $api_name)
  {
    $this->cosmos = $cosmos;
    $this->api_name = $api_name;
  }

  public function fetch()
  {
    $cache = Cache::getInstance();

    // Check if in cache
    if($cache->has($this->api_name)){
      $content = $cache->get($this->api_name);
      $this->body = $content->body;
      $this->name = $content->name;
      $this->type = $content->type;
      return;
    }

    // If not, fetch from api
    if(!$response = json_decode(@file_get_contents($this->url()))){
      throw new NotFoundInApiException;
    }
    $content = (object) $response->contents;

    $this->type = $content->type;
    $this->name = $content->name;
    $this->body = Encryption::decrypt($content->body, $content->cipher, $this->cosmos);

    Cache::getInstance()->addContent($this)->save();
  }

  public function body()
  {
    return $this->body;
  }
  public function name()
  {
    return $this->name;
  }
  public function api_name()
  {
    return $this->api_name;
  }
  public function type()
  {
    return $this->type;
  }

  private function url()
  {
    return 'https://cosmos-cms.com/api/'. $this->cosmos->getApiKey() .'/'. $this->api_name;
  }

  public function __toString()
  {
    return $this->body();
  }

}
