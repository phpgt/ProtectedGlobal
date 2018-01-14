<?php
namespace Gt\ProtectedGlobal\Test;

use PHPUnit\Framework\TestCase;

class GlobalOverrideTest extends TestCase {
	public function testFalseIsNotTrue() {
		self::assertNotTrue(false);
	}
}