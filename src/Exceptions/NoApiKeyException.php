<?php

namespace Cosmos\Exceptions;

use \Exception;

class NoApiKeyException extends Exception{

  protected $message = "Please provide an API-Key before getting contents.";

}
