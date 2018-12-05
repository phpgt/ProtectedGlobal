<?php
namespace Gt\ProtectedGlobal;

use ArrayAccess;

class ProtectedGlobal implements ArrayAccess {
	const WARNING_MESSAGE = "Global variables are protected - see https://php.gt/globals";

	public $whiteListData = [];

	public function __construct(array $whiteListData = []) {
		$this->whiteListData = $whiteListData;
	}

	public function __toString():string {
		return self::WARNING_MESSAGE;
	}

	public function __debugInfo():array {
		return array_merge([
			"WARNING" => (string)$this,
		], $this->whiteListData);
	}

	public function offsetExists($offset):bool {
		if(array_key_exists($offset, $this->whiteListData)) {
			return true;
		}

		$this->throwException();
	}

	public function offsetGet($offset) {
		if(array_key_exists($offset, $this->whiteListData)) {
			return $this->whiteListData[$offset];
		}

		$this->throwException();
	}

	public function offsetSet($offset, $value):void {
		if(array_key_exists($offset, $this->whiteListData)) {
			$this->whiteListData[$offset] = $value;
		}

		$this->throwException();
	}

	public function offsetUnset($offset):void {
		if(array_key_exists($offset, $this->whiteListData)) {
			unset($this->whiteListData);
		}

		$this->throwException();
	}

	protected function throwException():void {
		throw new ProtectedGlobalException(self::WARNING_MESSAGE);
	}
}