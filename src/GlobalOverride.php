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
//					self::unsetGlobalIfNotWhitelisted($globalKey, $key);
					unset($GLOBALS[$globalKey][$key]);
				}
			}
			else {
//				self::unsetGlobalIfNotWhitelisted($globalKey);
				unset($globalsToDeregister[$globalKey]);
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
		string $globalKey,
		string $key = null
	) {
	}
}