<?php
namespace Gt\ProtectedGlobal\Test;

use Gt\ProtectedGlobal\ProtectedGlobal;
use PHPUnit\Framework\TestCase;

class ProtectedGlobalTest extends TestCase {
	public function testToString() {
		$sut = new ProtectedGlobal();
		self::assertEquals(
			ProtectedGlobal::WARNING_MESSAGE,
			$sut
		);
	}

	public function testDebugInfo() {
		$globals = [
			"_GET" => [
				"name" => "test"
			]
		];
		$sut = new ProtectedGlobal($globals);
		$expectedWarning = array_merge([
			"WARNING" => (string)$sut,
		], $globals);
		self::assertEquals(
			$expectedWarning,
			$sut->__debugInfo()
		);
	}
}