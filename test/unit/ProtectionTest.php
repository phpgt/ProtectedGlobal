<?php
namespace Gt\ProtectedGlobal\Test;

use Gt\ProtectedGlobal\ProtectedGlobal;
use Gt\ProtectedGlobal\Protection;
use Gt\ProtectedGlobal\ProtectedGlobalException;
use PHPUnit\Framework\TestCase;

class ProtectionTest extends TestCase {
	public function testRemoveGlobals() {
		$testGlobals = [
			"_ENV" => [
				"somekey" => "somevalue",
			]
		];
		self::assertArrayHasKey("somekey", $testGlobals["_ENV"]);
		Protection::removeGlobals($testGlobals);
		self::assertArrayNotHasKey("_ENV", $testGlobals);
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

	public function testWhitelist() {
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
		Protection::removeGlobals(
			$env,
			"anotherkey"
		);
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
		$variable = $env["somevalue"];
	}

	public function testWhitelistMany() {
		$env = ["somekey" => "somevalue", "anotherkey" => "anothervalue"];
		$server = ["serverkey1" => "servervalue1"];
		$get = ["date" => "1999-12-31", "bug" => "millennium", "name" => "Y2K"];
		$post = ["postkey1" => "postvalue1", "postkey2" => "postvalue2"];
		$files = [];
		$cookie = [];
		$session = [];
		$testGlobals = [
			"_ENV" => $env,
			"_SERVER" => $server,
			"_GET" => $get,
			"_POST" => $post,
		];

		Protection::removeGlobals($env);
		Protection::removeGlobals($server);
		$getToKeep = Protection::removeGlobals(
			$get,
			"date",
			"name"
		);
		$postToKeep = Protection::removeGlobals(
			$post,
			"postkey2",
			"this-does-not-exist"
		);

		Protection::overrideInternals(
			[
				"_GET" => $getToKeep,
				"_POST" => $postToKeep,
			],
			$env,
			$server,
			$get,
			$post,
			$files,
			$cookie,
			$session
		);

		self::assertEquals("Y2K", $get["name"]);
		self::assertEquals("postvalue2", $post["postkey2"]);
		self::expectException(ProtectedGlobalException::class);
		$variable = $post["postkey1"];
	}
}