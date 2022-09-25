<?php
namespace Gt\ProtectedGlobal\Test;

use Gt\ProtectedGlobal\ProtectedGlobal;
use Gt\ProtectedGlobal\Protection;
use Gt\ProtectedGlobal\ProtectedGlobalException;
use PHPUnit\Framework\TestCase;

class ProtectionTest extends TestCase {
	public function testRemoveGlobals() {
		$globals = [
			"_ENV" => [
				"somekey" => "somevalue",
			]
		];

		self::assertArrayHasKey("somekey", $globals["_ENV"]);
		$updated = Protection::removeGlobals($globals);
		self::assertArrayNotHasKey("_ENV", $updated);
		self::assertNotNull($globals);
	}

	public function testOverride() {
		$env = ["somekey" => "somevalue"];
		$globals = [
			"_ENV" => $env,
		];

		self::assertEquals(
			"somevalue",
			$globals["_ENV"]["somekey"]
		);

		self::assertEquals("somevalue", $env["somekey"]);

		Protection::overrideInternals($globals);

		self::assertInstanceOf(ProtectedGlobal::class, $_ENV);
		self::assertEquals("somevalue", $env["somekey"]);
	}

	public function testWhitelist() {
		$env = ["somekey" => "somevalue", "anotherkey" => "anothervalue"];
		$globals = [
			"_ENV" => $env,
		];
		$whitelist = Protection::removeGlobals(
			$globals,
			[
				"_ENV" => [
					"anotherkey",
				],
			]
		);
		Protection::overrideInternals($whitelist);

		self::assertEquals("anothervalue", $_ENV["anotherkey"]);
		self::expectException(ProtectedGlobalException::class);
		$value = $_ENV["somevalue"];
	}

	public function testWhitelistMany() {
		$env = ["somekey" => "somevalue", "anotherkey" => "anothervalue"];
		$server = ["serverkey1" => "servervalue1"];
		$get = ["date" => "1999-12-31", "bug" => "millennium", "name" => "Y2K"];
		$post = ["postkey1" => "postvalue1", "postkey2" => "postvalue2"];
		$files = [];
		$cookie = [];
		$session = [];
		$globals = [
			"_ENV" => $env,
			"_SERVER" => $server,
			"_GET" => $get,
			"_POST" => $post,
		];

		Protection::removeGlobals($env);
		Protection::removeGlobals($server);
		$whitelisted = Protection::removeGlobals(
			$globals,
			[
				"_GET" => [
					"date",
					"name"
				],
				"_POST" => [
					"postkey2",
					"this-does-not-exist"
				],
			]

		);

		Protection::overrideInternals($whitelisted);

		self::assertEquals("Y2K", $_GET["name"]);
		self::assertEquals("postvalue2", $_POST["postkey2"]);
		self::expectException(ProtectedGlobalException::class);
		$variable = $_POST["postkey1"];
		var_dump($variable);
	}
}
