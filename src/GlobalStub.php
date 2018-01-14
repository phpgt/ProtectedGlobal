<?php
namespace Gt\ProtectedGlobal;

use ArrayAccess;

class GlobalStub implements ArrayAccess {
	const ERROR_MESSAGE = "Global variables are disabled - see https://php.gt/globals";

	public function __toString():string {
		return self::ERROR_MESSAGE;
	}

	public function __debugInfo():array {
		return ["ERROR" => (string)$this];
	}

	public function offsetExists($offset):bool {
		// TODO: Implement offsetExists() method.
	}

	public function offsetGet($offset) {
		// TODO: Implement offsetGet() method.
	}

	public function offsetSet($offset, $value):void {
		// TODO: Implement offsetSet() method.
	}

	public function offsetUnset($offset):void {
		// TODO: Implement offsetUnset() method.
	}
}