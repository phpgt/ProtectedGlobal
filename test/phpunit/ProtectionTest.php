<?php
namespace Gt\ProtectedGlobal\Test;

use Gt\ProtectedGlobal\ProtectedGlobal;
use Gt\ProtectedGlobal\Protection;
use Gt\ProtectedGlobal\ProtectedGlobalException;
use PHPUnit\Framework\TestCase;

class ProtectionTest extends TestCase {
	public function testOverride() {
		$env = ["somekey" => "somevalue"];
		$server = [];
		$get = [];
		$post = [];
		$files = [];
		$cookie = [];
		$session = [];
		$globals = [
			"_ENV" => $env,
		];

		self::assertEquals(
			"somevalue",
			$globals["_ENV"]["somekey"]
		);

		self::assertEquals("somevalue", $env["somekey"]);

		Protection::overrideInternals(
			$globals,
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
		$globals = [
			"_ENV" => $env,
		];
		Protection::removeGlobals(
			$globals,
			[
				"_ENV" => [
					"anotherkey",
				],
			]
		);
		Protection::overrideInternals(
			$globals,
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
		$globals = [
			"_ENV" => $env,
			"_SERVER" => $server,
			"_GET" => $get,
			"_POST" => $post,
		];

		Protection::removeGlobals($env);
		Protection::removeGlobals($server);
		$fixedGlobals = Protection::removeGlobals(
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

		Protection::overrideInternals(
			$fixedGlobals,
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
