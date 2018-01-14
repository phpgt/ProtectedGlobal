<?php
namespace Gt\ProtectedGlobal\Test;

use Gt\ProtectedGlobal\ProtectedGlobal;
use Gt\ProtectedGlobal\Protection;
use Gt\ProtectedGlobal\ProtectedGlobalException;
use PHPUnit\Framework\TestCase;

class GlobalOverrideTest extends TestCase {
	public function testRemoveGlobals() {
		$testGlobals = [
			"_ENV" => [
				"somekey" => "somevalue",
			]
		];
		self::assertArrayHasKey("somekey", $testGlobals["_ENV"]);
		Protection::removeGlobals($testGlobals);
		self::assertArrayNotHasKey("somekey", $testGlobals["_ENV"]);
		self::assertNotNull($testGlobals);
	}

	public function testOverride() {
		$env = ["somekey" => "somevalue"];
		$server = [];
		$get = [];
		$post = [];
		$files = [];
		$cookie = [];
		$session = [];
		$testGlobals = [
			"_ENV" => $env,
		];
		self::assertEquals("somevalue", $testGlobals["_ENV"]["somekey"]);
		self::assertEquals("somevalue", $env["somekey"]);
		Protection::overrideInternals(
			$testGlobals,
			$env,
			$server,
			$get,
			$post,
			$files,
			$cookie,
			$session
		);

		self::assertInstanceOf(ProtectedGlobal::class, $env);
		self::assertEquals("somevalue", $env["somekey"]);
	}

	public function testDeregisterOverride() {
		$env = ["somekey" => "somevalue", "anotherkey" => "anothervalue"];
		$server = [];
		$get = [];
		$post = [];
		$files = [];
		$cookie = [];
		$session = [];
		$testGlobals = [
			"_ENV" => $env,
		];
		Protection::removeGlobals($env, [
			"env" => "anotherkey",
		]);
		Protection::overrideInternals(
			$testGlobals,
			$env,
			$server,
			$get,
			$post,
			$files,
			$cookie,
			$session
		);

		self::assertEquals("anothervalue", $env["anotherkey"]);
		self::expectException(ProtectedGlobalException::class);
		echo $env["somevalue"];
	}
}