<?php

namespace Pronamic\WordPress\Pay\Extensions\Jigoshop;

use PHPUnit_Framework_TestCase;

class JigoshopTest extends PHPUnit_Framework_TestCase {
	/**
	 * Test class.
	 */
	public function test_class() {
		$this->assertTrue( class_exists( __NAMESPACE__ . '\Jigoshop' ) );
	}
}
