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
					if(!self::isKeyOnWhitelist(
						$whiteList,
						$globalKey,
						$key
					)) {
						unset($globalsToDeregister[$globalKey][$key]);
					}
				}
			}
			else {
				if(!self::isKeyOnWhitelist(
					$whiteList,
					$globalKey
				)) {
					unset($globalsToDeregister[$globalKey]);
				}
			}
		}
	}

	public static function override(array &$globalsToOverride, array $whiteList = []):void {
		$globalsToOverride = new GlobalStub();
		foreach($globalsToOverride as $globalKey => $globalValue) {
			$globalsToOverride[$globalKey] = new GlobalStub();
		}
	}

	protected static function isKeyOnWhitelist(
		array $whiteList,
		string $outerKey,
		string $innerKey = null
	):bool {
		$whiteListed = false;

		if(array_key_exists($outerKey, $whiteList)) {
			if(is_null($innerKey)) {
				$whiteListed = true;
			}
			else {
				if(in_array($innerKey, $whiteList[$outerKey])) {
					$whiteListed = true;
				}
			}
		}

		return $whiteListed;
	}
}