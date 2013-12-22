<?php

namespace Wikibot\Database;

use Wikibot\Database\Connection;

abstract class Table {

	protected $tableName;

	protected $column;

	protected $dbName;

	protected $conn;

	public function __construct( $dbName, $tableName ) {
		$this->tableName = $tableName;
		$this->columns = $this->initColumns();
		$this->conn = null;
		$this->dbName = $dbName;
	}

	abstract protected function initColumns();

	public function getColumns() {
		return $this->columns;
	}

	public function columnExists( $column ) {
		return array_key_exists( $column, $this->columns );
	}

	public function getColumnType( $column ) {
		if ( $this->columnExists( $column ) ) {
			return $this->columns[$column];
		}

		return null;
	}

	public function getWhereSQL( array $params ) {
		$where = '';

		foreach( $params as $column => $value ) {
			$type = $this->getColumnType( $column );

			if ( $type === null ) {
				throw new InvalidArgumentException( "column name not found" );
			}

			$val = $this->getValType( $type );

			if ( $where === '' ) {
				$where = sprintf( " WHERE $column = $val", $value );
			} else {
				$where .= sprintf( " AND $column = $val", $value );
			}
		}

		return $where;
	}

	public function selectAll( array $params, $order = null ) {
		$sql = "SELECT * FROM " . $this->tableName . $this->getWhereSQL( $params );

		if ( $order !== null && $this->columnExists( $order ) ) {
			$sql .= " ORDER BY $order";
		}

		return $this->query( $sql );
	}

	protected function query( $sql ) {
		$this->initDatabase();
		$res = $this->conn->query( $sql );

		if ( $res ) {
			$rows = $res->fetchAll( PDO::FETCH_ASSOC );
			return $rows;
		}

		return array();
	}

	public function selectColumn( $column, array $params = array() ) {
		if ( !$this->columnExists( $column ) ) {
			return array();
		}

		$sql = "SELECT $column FROM " . $this->tableName . $this->getWhereSQL( $params );
		return $this->query( $sql );
	}

	public function selectColumns( array $columns, $params = array() ) {
		$sql = "SELECT " . implode( ',', $columns ) . " FROM " . $this->tableName
			. $this->getWhereSQL( $params );

		return $this->query( $sql );
	}

	protected function getValType( $type ) {
		switch ( $type ) {
			case 'int':
				$val = '%d';
				break;
			case 'float':
				$val = '%d';
				break;
			default:
				$val = "'%s'";
				break;
		}

		return $val;
	}

	protected function initDatabase() {
		if ( $this->conn === null ) {
			$this->conn = new Connection( $this->dbName );
			$this->conn->connect();
		}
	}

}
