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
		$this->throwException();
	}

	public function offsetGet($offset) {
		$this->throwException();
	}

	public function offsetSet($offset, $value):void {
		$this->throwException();
	}

	public function offsetUnset($offset):void {
		$this->throwException();
	}

	protected function throwException():void {
		throw new ProtectedGlobalException(self::ERROR_MESSAGE);
	}
}