<?php
namespace Gt\ProtectedGlobal;

class Protection {
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
		foreach($globalsToOverride as $globalKey => $globalValue) {
			if(is_array($globalValue)) {
				$globalsToOverride[$globalKey] = new ProtectedGlobal(
					$globalValue,
					$whiteList[$globalKey] ?? []
				);
			}
			else {
// Sometimes there are stray variables on the $GLOBALS superglobal. These are not arrays themselves,
// but it's safe to treat them in the same way.
				$globalsToOverride[$globalKey] = new ProtectedGlobal();
			}
		}
	}

	public static function isKeyOnWhitelist(
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