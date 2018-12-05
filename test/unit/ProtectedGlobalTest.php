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
}