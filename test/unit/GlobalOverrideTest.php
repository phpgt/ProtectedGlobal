<?php
namespace Gt\ProtectedGlobal\Test;

use Gt\ProtectedGlobal\GlobalOverride;
use Gt\ProtectedGlobal\ProtectedGlobalException;
use PHPUnit\Framework\TestCase;

class GlobalOverrideTest extends TestCase {
	public function testDeregister() {
		$testGlobals = [
			"global1" => [
				"somekey" => "somevalue",
			]
		];
		self::assertArrayHasKey("somekey", $testGlobals["global1"]);
		GlobalOverride::deregister($testGlobals);
		self::assertArrayNotHasKey("somekey", $testGlobals["global1"]);
		self::assertNotNull($testGlobals);
	}

	public function testOverride() {
		$testGlobals = [
			"global1" => [
				"somekey" => "somevalue",
			]
		];
		self::assertEquals("somevalue", $testGlobals["global1"]["somekey"]);
		GlobalOverride::override($testGlobals);
		self::expectException(ProtectedGlobalException::class);
		echo $testGlobals["global1"]["somekey"];
	}

	public function testDeregisterWithWhiteList() {
		$time = time();
		$testGlobals = [
			"global1" => [
				"time" => $time,
				"version" => PHP_VERSION,
			],
			"global2" => [
				"directory_separator" => DIRECTORY_SEPARATOR,
				"test_dir" => __DIR__,
			]
		];

		self::assertEquals($time, $testGlobals["global1"]["time"]);

		GlobalOverride::override($testGlobals, [
			"global1" => ["version"],
			"global2" => ["directory_separator"],
		]);

		self::assertEquals(
			PHP_VERSION,
			$testGlobals["global1"]["version"]
		);
		self::assertEquals(
			DIRECTORY_SEPARATOR,
			$testGlobals["global1"]["directory_separator"]
		);
	}

	public function testDeregisterWithWhiteListThrows() {
		$time = time();
		$testGlobals = [
			"global1" => [
				"time" => $time,
				"version" => PHP_VERSION,
			],
			"global2" => [
				"directory_separator" => DIRECTORY_SEPARATOR,
				"test_dir" => __DIR__,
			]
		];

		GlobalOverride::override($testGlobals, [
			"global1" => ["version"],
			"global2" => ["directory_separator"],
		]);

		self::expectException(ProtectedGlobalException::class);
		echo $testGlobals["global1"]["time"];
	}
}