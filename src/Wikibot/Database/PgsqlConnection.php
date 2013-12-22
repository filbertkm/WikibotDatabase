<?php

namespace Wikibot\Database;

use PDO;
use PDOException;

class PgsqlConnection extends AbstractConnection {

	public function connect() {
		$connString = "pgsql:dbname={$this->dbname};host={$this->dbhost};";

		try {
			$this->conn = new PDO(
				$connString,
				$this->user,
				$this->password,
				array(
					PDO::ATTR_PERSISTENT => true
				)
			);
		} catch ( PDOException $ex ) {
			return false;
		}

		return true;
	}

}
