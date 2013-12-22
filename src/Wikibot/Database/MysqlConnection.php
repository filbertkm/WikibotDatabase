<?php

namespace Wikibot\Database;

use PDO;
use PDOException;

class MysqlConnection extends AbstractConnection {

	public function connect() {
		try {
			$this->conn = new PDO(
				"mysql:host={$this->dbhost};dbname={$this->dbname};charset=utf8",
				$this->user,
				$this->password,
				array(
					PDO::ATTR_PERSISTENT => true
				)
			);

			$this->conn->query( "SET CHARACTER SET utf8" );
			$this->conn->query( "SET NAMES utf8" );
		} catch ( PDOException $ex ) {
			return false;
		}

		return true;
	}

}
