<?php

namespace Wikibase\QueryEngine\Tests\SQLStore;

use Wikibase\QueryEngine\SQLStore\DataValueHandlers;

/**
 * @covers Wikibase\QueryEngine\SQLStore\DataValueHandlers
 *
 * @file
 * @since 0.1
 *
 * @ingroup WikibaseQueryEngineTest
 *
 * @group Wikibase
 * @group WikibaseQueryEngine
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DataValueHandlersTest extends \PHPUnit_Framework_TestCase {

	public function testGetHandlersReturnType() {
		$defaultHandlers = new DataValueHandlers();
		$defaultHandlers = $defaultHandlers->getHandlers();

		$this->assertInternalType( 'array', $defaultHandlers );
		$this->assertContainsOnlyInstancesOf( 'Wikibase\QueryEngine\SQLStore\DataValueHandler', $defaultHandlers );
	}

}
