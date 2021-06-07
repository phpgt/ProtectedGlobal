<?php
namespace Gt\ProtectedGlobal\Test;

use Gt\ProtectedGlobal\ProtectedGlobal;
use Gt\ProtectedGlobal\ProtectedGlobalException;
use PHPUnit\Framework\TestCase;

class ProtectedGlobalTest extends TestCase {
	public function testToString() {
		$sut = new ProtectedGlobal();
		self::assertSame(
			ProtectedGlobal::WARNING_MESSAGE,
			(string)$sut
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

	public function testOffsetSet() {
		$whiteList = [
			"name" => "test",
		];
		$sut = new ProtectedGlobal($whiteList);
		$sut["name"] = "changed";

		self::assertEquals("changed", $sut["name"]);
	}

	public function testOffsetUnsetThrowsException() {
		$whiteList = [
			"name" => "test",
		];
		$sut = new ProtectedGlobal($whiteList);
		self::expectException(ProtectedGlobalException::class);
		unset($sut["something"]);
	}

	public function testOffsetUnset() {
		$whiteList = [
			"name" => "test",
			"country" => "United Kingdom",
		];
		$sut = new ProtectedGlobal($whiteList);

		$exception = null;

		try {
			unset($sut["name"]);
		}
		catch(\Exception $exception) {}

		self::assertNull($exception);
	}
}
