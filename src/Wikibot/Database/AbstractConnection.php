<?php

namespace Wikibot\Database;

use WikiConfig;

abstract class AbstractConnection {

	protected $conn;

	protected $user;

	protected $password;

	protected $dbname;

	protected $dbhost;

	public function __construct( $user, $password, $dbname, $dbhost ) {
		$this->user = $user;
		$this->password = $password;
		$this->dbname = $dbname;
		$this->dbhost = $dbhost;
	}

	abstract public function connect();

	public function getConn() {
		if ( !$this->conn ) {
			$this->connect();
		}

		return $this->conn;
	}

	public function query( $sql ) {
		if ( !$this->conn ) {
			$this->connect();
		}

		$res = $this->conn->query( $sql );
		$this->conn = null;

		return $res;
	}

	public static function newFromDbInfo( $class, $dbInfo ) {
		$user = $dbInfo['user'];
		$password = $dbInfo['password'];
		$dbname = $dbInfo['dbname'];
		$dbhost = $dbInfo['dbhost'];

		return new $class( $user, $password, $dbname, $dbhost );
	}

}
