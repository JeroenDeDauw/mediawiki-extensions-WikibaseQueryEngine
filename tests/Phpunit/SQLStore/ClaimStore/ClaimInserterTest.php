<?php

namespace Wikibase\QueryEngine\Tests\Phpunit\SQLStore\ClaimStore;

use DataValues\StringValue;
use Wikibase\Claim;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\PropertyNoValueSnak;
use Wikibase\PropertyValueSnak;
use Wikibase\QueryEngine\SQLStore\ClaimStore\ClaimInserter;
use Wikibase\QueryEngine\SQLStore\ClaimStore\ClaimRowBuilder;
use Wikibase\Reference;
use Wikibase\ReferenceList;
use Wikibase\SnakList;
use Wikibase\Statement;

/**
 * @covers Wikibase\QueryEngine\SQLStore\ClaimStore\ClaimInserter
 *
 * @ingroup WikibaseQueryEngineTest
 *
 * @group Wikibase
 * @group WikibaseQueryEngine
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ClaimInserterTest extends \PHPUnit_Framework_TestCase {

	public function claimProvider() {
		/**
		 * @var Claim[] $claims
		 */
		$claims = array();

		$claims[] = new Claim(
			new PropertyValueSnak( 42, new StringValue( 'NyanData' ) )
		);

		$claims[] = new Claim(
			new PropertyNoValueSnak( 23 ),
			new SnakList( array(
				new PropertyValueSnak( 1337, new StringValue( 'NyanData' ) ),
				new PropertyNoValueSnak( 9001 )
			) )
		);

		$claims[] = new Statement(
			new PropertyNoValueSnak( 1 ),
			new SnakList( array(
				new PropertyValueSnak( 2, new StringValue( 'NyanData' ) ),
				new PropertyNoValueSnak( 3 )
			) ),
			new ReferenceList( array(
				new Reference( new SnakList( array(
					new PropertyValueSnak( 3, new StringValue( 'NyanData' ) ),
				) ) ),
				new Reference( new SnakList( array(
					new PropertyValueSnak( 4, new StringValue( 'NyanData' ) ),
					new PropertyValueSnak( 5, new StringValue( 'NyanData' ) ),
				) ) )
			) )
		);

		$argLists = array();

		foreach ( $claims as $claim ) {
			$claim->setGuid( 'some-claim-guid' );
			$argLists[] = array( $claim );
		}

		return $argLists;
	}

	/**
	 * @dataProvider claimProvider
	 */
	public function testInsertClaim( Claim $claim ) {
		$snakInserter = $this->getMockBuilder( 'Wikibase\QueryEngine\SQLStore\SnakStore\SnakInserter' )
			->disableOriginalConstructor()->getMock();

		$snakInserter->expects( $this->exactly( $this->countClaimSnaks( $claim ) ) )->method( 'insertSnak' );

		$claimRowBuilder = new ClaimRowBuilder();

		$claimInserter = new ClaimInserter( $snakInserter, $claimRowBuilder );

		$claimInserter->insertClaim( $claim, new ItemId( 'Q1' ) );
	}

	private function countClaimSnaks( Claim $claim ) {
		$snakCount = 1;

		$snakCount += $claim->getQualifiers()->count();

		// References are ignored as these are not inserted into the store at this point.

		return $snakCount;
	}

}
