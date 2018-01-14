<?php
namespace Gt\ProtectedGlobal;

class GlobalOverride {
	protected static $whitelist = [];

	/**
	 * Pass in an optional whitelist to allow the specified globals to remain set. This is
	 * useful for tools like XDebug which require access to the $_COOKIE superglobal.
	 */
	public static function override(array $whiteList = []) {
		foreach($GLOBALS as $globalKey => $globalValue) {
			if(is_array($globalValue)) {
				foreach($globalValue as $key => $value) {
					self::unsetGlobalIfNotWhitelisted($globalKey, $key);
//					unset($GLOBALS[$globalKey][$key]);
				}
			}
			else {
				self::unsetGlobalIfNotWhitelisted($globalKey);
//				unset($GLOBALS[$globalKey]);
			}
		}
		unset($GLOBALS);
	}

	protected static function unsetGlobalIfNotWhitelisted(
		string $globalKey,
		string $key = null
	) {
	}
}