<?php
namespace Skar\Skfbalbums\Helper;

// https://www.codexworld.com/display-facebook-albums-photos-on-website-php-graph-api/

class CommunicationException extends \Exception
{
	private $http_response_header;

    public function __construct($message, $code = 0, \Exception $previous = null, $http_response_header = null) {
    	$this->http_response_header = $http_response_header+[];
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getResponseHeaders() {
    	return $this->http_response_header;
    }
}
