<?php
namespace Gt\ProtectedGlobal\Test;

use Gt\ProtectedGlobal\ProtectedGlobal;
use Gt\ProtectedGlobal\ProtectedGlobalException;
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
		$whiteList = [
			"name" => "test",
		];
		$sut = new ProtectedGlobal($whiteList);
		$expectedWarning = array_merge([
			"WARNING" => (string)$sut,
		], $whiteList);
		self::assertEquals(
			$expectedWarning,
			$sut->__debugInfo()
		);
	}

	public function testOffsetExistsThrowsException() {
		$whiteList = [
			"name" => "test",
		];
		$sut = new ProtectedGlobal($whiteList);
		self::expectException(ProtectedGlobalException::class);
		$exists = isset($sut["not-exists"]);
	}

	public function testOffsetExists() {
		$whiteList = [
			"name" => "test",
		];
		$sut = new ProtectedGlobal($whiteList);
		$exists = isset($sut["name"]);
		self::assertTrue($exists);
	}

	public function testOffsetSetThrowsException() {
		$whiteList = [
			"name" => "test",
		];
		$sut = new ProtectedGlobal($whiteList);
		self::expectException(ProtectedGlobalException::class);
		$sut["something"] = "broken";
	}
}