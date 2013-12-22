<?php

namespace Wikibot\Database;

use InvalidArgumentException;

class ConnectionFactory {

	public static function newConnection( $dbType, $dbInfo ) {
		switch( $dbType ) {
			case 'mysql':
				return MysqlConnection::newFromDbInfo(
					'Wikibot\Database\MysqlConnection',
					$dbInfo
				);
			case 'pgsql':
				return PgsqlConnection::newFromDbInfo(
					'Wikibot\Database\PgsqlConnection',
					$dbInfo
				);
			default:
				throw new InvalidArgumentException( 'dbType is not supported.' );
		}
	}

}
