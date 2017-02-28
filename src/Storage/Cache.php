<?php

namespace Cosmos\Storage;

use Cosmos\Content;

class Cache{

  private $dir, $file;
  private $refreshRate;
  private static $cache;
  private $contents = array();

  private function __construct($dir)
  {
    $this->dir = rtrim($dir, "/");
    $this->refreshRate  = 60 * 60 * 24;
    if(!is_dir($this->dir)){
      mkdir($this->dir);
    }
    $this->file = @scandir($this->dir)[2];
    if($this->file){
      $this->contents = json_decode(file_get_contents($this->dir.'/'.$this->file), true);
    }
  }

  public function addContent(Content $content)
  {
    $c["body"] = $content->body();
    $c["name"] = $content->name();
    $c["type"] = $content->type();
    $c["time"] = time();

    $this->contents[$content->api_name()] = $c;
    return $this;
  }

  public function has($api_name)
  {
    if(array_key_exists($api_name, $this->contents) && time() - $this->contents[$api_name]["time"] > $this->refreshRate){
      return false;
    }
    return array_key_exists($api_name, $this->contents);
  }

  public function get($api_name)
  {
    return (object) $this->contents[$api_name];
  }

  public function save()
  {
    foreach(scandir($this->dir) as $file){
      if($file != '.' && $file != ".."){
        unlink($this->dir.'/'.$file);
      }
    }

    file_put_contents($this->dir."/".time(), json_encode($this->contents));
  }

  public function setRefreshRate($secs)
  {
    $this->refreshRate = $secs;
  }

  public static function getInstance($path = null)
  {
    if(!self::$cache){
      self::$cache = new Cache($path);
    }
    return self::$cache;
  }

}
