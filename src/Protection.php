<?php
namespace Gt\ProtectedGlobal;

class Protection {
	/**
	 * Pass in an optional whitelist to allow the specified globals to remain set. This is
	 * useful for tools like XDebug which require access to the $_COOKIE superglobal.
	 */
	public static function removeGlobals(
		array &$globalsToDeregister,
		string...$whiteList
	):array {
		$keep = [];

		foreach($whiteList as $whiteListKey) {
			if(!isset($globalsToDeregister[$whiteListKey])) {
				continue;
			}

			$keep[$whiteListKey] = $globalsToDeregister[$whiteListKey];
		}

		$globalsToDeregister = $keep;
		return $keep;
	}

	public static function overrideInternals(
		array $globals,
		array &$env,
		array &$server,
		array &$get,
		array &$post,
		array &$files,
		array &$cookie,
		array &$session
	):void {
		$env = new ProtectedGlobal($globals["_ENV"] ?? []);
		$server = new ProtectedGlobal($globals["_SERVER"] ?? []);
		$get = new ProtectedGlobal($globals["_GET"] ?? []);
		$post = new ProtectedGlobal($globals["_POST"] ?? []);
		$files = new ProtectedGlobal($globals["_FILES"] ?? []);
		$cookie = new ProtectedGlobal($globals["_COOKIE"] ?? []);
		$session = new ProtectedGlobal($globals["_SESSION"] ?? []);
	}
}