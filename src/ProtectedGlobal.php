<?php
namespace GT\ProtectedGlobal;

use ArrayAccess;

/** @implements ArrayAccess<string, mixed> */
class ProtectedGlobal implements ArrayAccess {
	const WARNING_MESSAGE = "Global variables are protected - see https://php.gt/globals";

	/** @var array<string, mixed> */
	protected array $whiteListData;

	/** @param array<string, mixed> $whiteListData */
	public function __construct(array $whiteListData = []) {
		$this->whiteListData = $whiteListData;
	}

	public function __toString():string {
		return self::WARNING_MESSAGE;
	}

	/** @return array<string, mixed> */
	public function __debugInfo():array {
		return array_merge([
			"WARNING" => (string)$this,
		], $this->whiteListData);
	}

	public function offsetExists($offset):bool {
		if(array_key_exists($offset, $this->whiteListData)) {
			return true;
		}

		$this->throwException($offset);
		/** @noinspection PhpUnreachableStatementInspection */
		return false;
	}

	public function offsetGet($offset):mixed {
		if(array_key_exists($offset, $this->whiteListData)) {
			return $this->whiteListData[$offset];
		}

		$this->throwException($offset);
		/** @noinspection PhpUnreachableStatementInspection */
		return null;
	}

	public function offsetSet($offset, $value):void {
		if(array_key_exists($offset, $this->whiteListData)) {
			$this->whiteListData[$offset] = $value;
			return;
		}

		$this->throwException($offset);
	}

	public function offsetUnset($offset):void {
		if(array_key_exists($offset, $this->whiteListData)) {
			unset($this->whiteListData[$offset]);
			return;
		}

		$this->throwException();
	}

	protected function throwException(string $offset = ""):void {
		$message = "";
		if($offset) {
			$message .= "Attempt to read offset '$offset' - ";
		}

		$message .= self::WARNING_MESSAGE;
		throw new ProtectedGlobalException($message);
	}
}
