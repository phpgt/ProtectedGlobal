<?php
namespace Gt\ProtectedGlobal;

use ArrayAccess;

class ProtectedGlobal implements ArrayAccess {
	const ERROR_MESSAGE = "Global variables are disabled - see https://php.gt/globals";

	protected $whiteListedKeyValues = [];

	public function __construct(array $originalArray = [], array $whiteList = []) {
		$this->storeWhiteListedValues($originalArray, $whiteList);
	}

	public function __toString():string {
		return self::ERROR_MESSAGE;
	}

	public function __debugInfo():array {
		return ["ERROR" => (string)$this];
	}

	public function storeWhiteListedValues(array $array, array $whiteList):void {
		foreach($array as $key => $value) {
			if(in_array($key, $whiteList)) {
				$this->whiteListedKeyValues[$key] = $value;
			}
		}
	}

	public function offsetExists($offset):bool {
		if(isset($this->whiteListedKeyValues[$offset])) {
			return true;
		}

		$this->throwException();
	}

	public function offsetGet($offset) {
		if(isset($this->whiteListedKeyValues[$offset])) {
			return $this->whiteListedKeyValues[$offset];
		}

		$this->throwException();
	}

	public function offsetSet($offset, $value):void {
		if(isset($this->whiteListedKeyValues[$offset])) {
			$this->whiteListedKeyValues[$offset] = $value;
		}

		$this->throwException();
	}

	public function offsetUnset($offset):void {
		if(isset($this->whiteListedKeyValues[$offset])) {
			unset($this->whiteListedKeyValues);
		}

		$this->throwException();
	}

	protected function throwException():void {
		throw new ProtectedGlobalException(self::ERROR_MESSAGE);
	}
}