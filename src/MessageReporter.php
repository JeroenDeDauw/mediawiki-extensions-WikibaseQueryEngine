<?php

namespace Wikibase\QueryEngine;

/**
 * @since 0.1
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface MessageReporter {

	/**
	 * @param string $message
	 */
	public function reportMessage( $message );

}
