<?php
namespace Gt\ProtectedGlobal\Test;

use Gt\ProtectedGlobal\GlobalOverride;
use Gt\ProtectedGlobal\ProtectedGlobalException;
use PHPUnit\Framework\TestCase;

class GlobalOverrideTest extends TestCase {
	public function testDeregister() {
		$myArray = [
			"global1" => [
				"somekey" => "somevalue",
			]
		];
		self::assertArrayHasKey("somekey", $myArray["global1"]);
		GlobalOverride::deregister($myArray);
		self::assertArrayNotHasKey("somekey", $myArray["global1"]);
		self::assertNotNull($myArray);
	}

	public function testOverride() {
		$myArray = [
			"global1" => [
				"somekey" => "somevalue",
			]
		];
		self::assertEquals("somevalue", $myArray["global1"]["somekey"]);
		GlobalOverride::override($myArray);
		self::expectException(ProtectedGlobalException::class);
		echo $myArray["global1"]["somekey"];
	}
}