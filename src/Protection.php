<?php
namespace Gt\ProtectedGlobal;

class Protection {
	const GLOBAL_KEYS = [
		"_ENV",
		"_SERVER",
		"_GET",
		"_POST",
		"_FILES",
		"_COOKIE",
		"_SESSION",
	];
	/**
	 * Pass in an optional whitelist to allow the specified globals to remain set. This is
	 * useful for tools like XDebug which require access to the $_COOKIE superglobal.
	 *
	 * The first parameter is the contents of the $GLOBALS superglobal.
	 *
	 * The second parameter is a 2D array describing which keys to whitelist
	 * within each GLOBAL. For example: ["_ENV" => ["keepThis", "andKeepThis"]]
	 *
	 * @param array<string, mixed> $globalsToDeregister
	 * @param array<string, mixed> $whiteList
	 * @return array<string, mixed>
	 */
	public static function removeGlobals(
		array $globalsToDeregister,
		array $whiteList = []
	):array {
		$keep = [];

		foreach($whiteList as $globalName => $keysToKeep) {
			if(!isset($globalsToDeregister[$globalName])) {
				continue;
			}

			$keep[$globalName] = [];
			$thisGlobal = $globalsToDeregister[$globalName];

			foreach($keysToKeep as $key) {
				if(!isset($thisGlobal[$key])) {
					$thisGlobal[$key] = null;
				}

				$keep[$globalName][$key] = $thisGlobal[$key];
			}
		}

// This is necessary after PHP 8.1, as it's impossible to pass $GLOBALS by
// reference, and copies of the $GLOBALS array cannot modify the original.
		foreach($keep as $key => $kvp) {
			foreach($kvp as $k => $value) {
				$GLOBALS[$key][$k] = $value;
			}
		}
		return $keep;
	}

	/** @param array<string, mixed> $whitelistedGlobals */
	public static function overrideInternals(array $whitelistedGlobals):void {
		foreach(self::GLOBAL_KEYS as $key) {
			$GLOBALS[$key] = new ProtectedGlobal($whitelistedGlobals[$key] ?? []);
		}
	}
}
