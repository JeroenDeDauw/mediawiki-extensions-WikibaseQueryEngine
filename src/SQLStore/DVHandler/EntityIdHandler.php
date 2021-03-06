<?php

namespace Wikibase\QueryEngine\SQLStore\DVHandler;

use DataValues\DataValue;
use InvalidArgumentException;
use Wikibase\DataModel\Entity\BasicEntityIdParser;
use Wikibase\DataModel\Entity\EntityIdValue;
use Wikibase\QueryEngine\SQLStore\DataValueHandler;

/**
 * Represents the mapping between Wikibase\EntityId and
 * the corresponding table in the store.
 *
 * @since 0.1
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntityIdHandler extends DataValueHandler {

	/**
	 * @see DataValueHandler::newDataValueFromValueField
	 *
	 * @since 0.1
	 *
	 * @param string $valueFieldValue
	 *
	 * @return DataValue
	 */
	public function newDataValueFromValueField( $valueFieldValue ) {
		$parser = new BasicEntityIdParser(); // TODO: inject
		return new EntityIdValue( $parser->parse( $valueFieldValue ) ); // TODO: handle parse exception
	}

	/**
	 * @see DataValueHandler::getWhereConditions
	 *
	 * @since 0.1
	 *
	 * @param DataValue $value
	 *
	 * @return array
	 * @throws InvalidArgumentException
	 */
	public function getWhereConditions( DataValue $value ) {
		if ( !( $value instanceof EntityIdValue ) ) {
			throw new InvalidArgumentException( '$value is not a EntityIdValue' );
		}

		return array(
			'id' => json_encode( $value->getEntityId()->getSerialization() ),
		);
	}

	/**
	 * @see DataValueHandler::getInsertValues
	 *
	 * @since 0.1
	 *
	 * @param DataValue $value
	 *
	 * @return array
	 * @throws InvalidArgumentException
	 */
	public function getInsertValues( DataValue $value ) {
		if ( !( $value instanceof EntityIdValue ) ) {
			throw new InvalidArgumentException( '$value is not a EntityIdValue' );
		}

		$values = array(
			'id' => $value->getEntityId()->getSerialization(),
			'type' => $value->getEntityId()->getEntityType(),
		);

		return $values;
	}

}