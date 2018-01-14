<?php
namespace Gt\ProtectedGlobal\Test;

use Gt\ProtectedGlobal\Protection;
use Gt\ProtectedGlobal\ProtectedGlobalException;
use PHPUnit\Framework\TestCase;

class GlobalOverrideTest extends TestCase {
	public function testDeregister() {
		$testGlobals = [
			"_ENV" => [
				"somekey" => "somevalue",
			]
		];
		self::assertArrayHasKey("somekey", $testGlobals["_ENV"]);
		Protection::deregister($testGlobals);
		self::assertArrayNotHasKey("somekey", $testGlobals["_ENV"]);
		self::assertNotNull($testGlobals);
	}

	public function testOverride() {
		$testGlobals = [
			"_ENV" => [
				"somekey" => "somevalue",
			]
		];
		self::assertEquals("somevalue", $testGlobals["_ENV"]["somekey"]);
		Protection::override($testGlobals);
		self::expectException(ProtectedGlobalException::class);
		echo $testGlobals["_ENV"]["somekey"];
	}

	public function testDeregisterWithWhiteList() {
		$time = time();
		$testGlobals = [
			"_ENV" => [
				"time" => $time,
				"version" => PHP_VERSION,
			],
			"_SERVER" => [
				"directory_separator" => DIRECTORY_SEPARATOR,
				"test_dir" => __DIR__,
			]
		];

		self::assertEquals($time, $testGlobals["_ENV"]["time"]);

		Protection::deregister($testGlobals, [
			"_ENV" => ["version"],
			"_SERVER" => ["directory_separator"],
		]);

		self::assertEquals(
			PHP_VERSION,
			$testGlobals["_ENV"]["version"]
		);
		self::assertEquals(
			DIRECTORY_SEPARATOR,
			$testGlobals["_SERVER"]["directory_separator"]
		);

		self::assertArrayNotHasKey("time", $testGlobals["_ENV"]);
		self::assertArrayNotHasKey("test_dir", $testGlobals["_SERVER"]);
	}

	public function testOverrideWithWhiteList() {
		$time = time();
		$testGlobals = [
			"_ENV" => [
				"time" => $time,
				"version" => PHP_VERSION,
			],
			"_SERVER" => [
				"directory_separator" => DIRECTORY_SEPARATOR,
				"test_dir" => __DIR__,
			]
		];

		Protection::override($testGlobals, [
			"_ENV" => ["version"],
			"_SERVER" => ["directory_separator"],
		]);

		self::assertEquals(
			PHP_VERSION,
			$testGlobals["_ENV"]["version"]
		);
		self::assertEquals(
			DIRECTORY_SEPARATOR,
			$testGlobals["_SERVER"]["directory_separator"]
		);

		self::expectException(ProtectedGlobalException::class);
		echo $testGlobals["_SERVER"]["test_dir"];
	}
}