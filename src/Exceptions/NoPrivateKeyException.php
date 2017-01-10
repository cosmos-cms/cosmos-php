<?php

namespace Cosmos\Exceptions;

use \Exception;

class NoPrivateKeyException extends Exception{

  protected $message = "Please provide a Private-Key before getting contents.";

}
