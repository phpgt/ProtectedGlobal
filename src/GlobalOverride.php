<?php
namespace Gt\ProtectedGlobal;

class GlobalOverride {
	/**
	 * Pass in an optional whitelist to allow the specified globals to remain set. This is
	 * useful for tools like XDebug which require access to the $_COOKIE superglobal.
	 */
	public static function deregister(array &$globalsToDeregister, array $whiteList = []):void {
		foreach($globalsToDeregister as $globalKey => $globalValue) {
			if(is_array($globalValue)) {
				foreach($globalValue as $key => $value) {
					self::unsetGlobalIfNotWhitelisted(
						$globalsToDeregister,
						$whiteList,
						$globalKey,
						$key
					);
//					unset($GLOBALS[$globalKey][$key]);
				}
			}
			else {
				self::unsetGlobalIfNotWhitelisted(
					$globalsToDeregister,
					$whiteList,
					$globalKey
				);
//				unset($globalsToDeregister[$globalKey]);
			}
		}
		unset($globalsToDeregister);
	}

	public static function override(array &$globalsToOverride, array $whiteList = []):void {
		$globalsToOverride = new GlobalStub();
		foreach($globalsToOverride as $globalKey => $globalValue) {
			$globalsToOverride[$globalKey] = new GlobalStub();
		}
	}

	protected static function unsetGlobalIfNotWhitelisted(
		array &$globalsToUnset,
		array $whiteList,
		string $globalKey,
		string $key = null
	):void {
		$whiteListed = false;

		if(array_key_exists($globalKey, $whiteList)) {
			if(is_null($key)) {
				$whiteListed = true;
			}
			else {
				if(in_array($key, $whiteList[$globalKey])) {
					$whiteListed = true;
				}
			}
		}

		if(!$whiteListed) {
			if(is_null($key)) {
				unset($globalsToUnset[$globalKey]);
			}
			else {
				unset($globalsToUnset[$globalKey][$key]);
			}
		}
	}
}