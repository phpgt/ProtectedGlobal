<?php

namespace Gt\ProtectedGlobal;

use Exception;
use Throwable;

class ProtectedGlobalException extends Exception {
	public function __construct(
		string $message = "",
		int $code = 0,
		Throwable $previous = null
	) {
		parent::__construct($message, $code, $previous);
	}
}