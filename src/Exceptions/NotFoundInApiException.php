<?php

namespace Cosmos\Exceptions;

use \Exception;

class NotFoundInApiException extends Exception{

  protected $message = "API couldn't resolve anything. Please check your API-Key and API-Name.";

}
