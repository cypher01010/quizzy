<?php
/**
 * This class will be responsible for execution of the SQL Statement directed to the database.
 */
class Database
{
	public $mode;

	/**
	 * this will holds the connections of the database
	 */ 
	private $_conn = null;

	/**
	 * this holds the database credentials
	 */
	private $_dbCredentials = null;

	/**
	 * Class Constructor
	 */
	public function __construct($db)
	{
		$this->_dbCredentials = $db;
	}

	/**
	 * This function will be responsible for opening the connection to the database.
	 */
	public function open()
	{
		if($this->_conn == null) {
			$this->_conn = mysql_connect($this->_dbCredentials['host'], $this->_dbCredentials['username'], $this->_dbCredentials['password']) or die (mysql_error());
		}

		mysql_select_db($this->_dbCredentials['database'],$this->_conn) or die (mysql_error());
	}

	/**
	 * Closing the database connection
	 */
	public function close()
	{
		if($this->_conn != null) {
			mysql_close($this->_conn);
			$this->_conn = null;
		}
	}

	/**
	 * Query function to query the database.
	 * @param String $sql Sql Statement
	 * @return Array() $value This is the return value of the method
	 */
	public function query($sql)
	{
		if(!$this->correctSql($sql, 'SELECT')) {
			echo 'INVALID SQL STATEMENT';
			return false;
		}

		$res = $this->execute($sql);
		$rowCount = mysql_num_rows($res);
		$value = array();

		if($rowCount > 0) {
			$value = array();

			while($row = mysql_fetch_assoc($res)) {
				$value[] = $row;
			}
		}

		return $value;
	}

	/**
	 * Count record
	 * @param String $sql Sql Statement
	 * @return Count $value This is the return value of the method
	 */
	public function count($sql)
	{
		if(!$this->correctSql($sql, 'SELECT')) {
			echo 'INVALID SQL STATEMENT';
			return false;
		}

		$value = $this->execute($sql);
		return mysql_num_rows($value);
	}

	/**
	 * Inserting a record in the database
	 * @param String $sql Sql Statement
	 * @return Boolen $value This is the return value of the method
	 */ 
	public function insert($sql)
	{
		if(!$this->correctSql($sql, 'INSERT INTO')) {
			echo 'INVALID SQL STATEMENT';
			return false;
		}

		$value = $this->execute($sql);
		return $value;
	}

	/**
	 * Updating a record in the database.
	 * @param String $sql Sql Statement
	 * @return Boolen $value This is the return value of the method
	 */ 
	public function update($sql)
	{
		if(!$this->correctSql($sql, 'UPDATE')) {
			echo 'INVALID SQL STATEMENT';
			return false;
		}

		$value = $this->execute($sql);
		return $value;
	}

	/**
	 * Deleting a record in the database.
	 * @param String $sql Sql Statement
	 * @return Boolen $value This is the return value of the method
	 */
	public function delete($sql)
	{
		if(!$this->correctSql($sql, 'DELETE FROM')) {
			echo 'INVALID SQL STATEMENT';
			return false;
		}

		$value = $this->execute($sql);
		return $value;
	}

	/**
	 * Execute the sql command
	 * @param $sql
	 * @return $res
	 */
	private function execute($sql)
	{
		$this->open();
		$res =  mysql_query($sql) or die (mysql_error());
		return $res;
	}

	/**
	 * Correct the SQl command
	 * @param $sql
	 * @param $type
	 * @return bool
	 */
	private function correctSql($sql, $type)
	{
		$pos = strpos(strtoupper($sql), $type);
		if($pos === false) {
			return false;
		} else {
			return true;
		}
	}

	public function listTables()
	{
		$db = $this->_dbCredentials['database'];
		$tables = array();
		$list_tables_sql = "SHOW TABLES FROM {$db};";
		$result = mysql_query($list_tables_sql);
		if($result)
		while($table = mysql_fetch_row($result))
		{
			$tables[] = $table[0];
		}
		return $tables;
	}

	public function backupTables($table = '', $outputDir = '.')
	{
		try
		{
			$sql='';
			$t='';

			$result = mysql_query('SELECT * FROM '.$table);
			$numFields = mysql_num_fields($result);

			$sql .= "--\n";
			$sql .= "-- Table structure for table " . $table . "\n";
			$sql .= "--\n";

			$sql .= 'DROP TABLE IF EXISTS '.$table.';';
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$sql.= "\n\n".$row2[1].";\n\n";


			$sql .= "--\n";
			$sql .= "-- Dumping data for table " . $table . "\n";
			$sql .= "--\n";


			for ($i = 0; $i < $numFields; $i++) {
				while($row = mysql_fetch_row($result)) {
					$sql .= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$numFields; $j++) {
						$row[$j] = addslashes($row[$j]);
						$row[$j] = preg_replace("#\n#", "\\n", $row[$j]);
						
						if (isset($row[$j])) {
							$sql .= '"'.$row[$j].'"' ;
						} else {
							$sql.= '""';
						}
	
						if ($j < ($numFields-1)) {
							$sql .= ',';
						}
					}
	
					$sql.= ");\n";
				}
			}
			$sql.="\n\n\n";

		} catch (Exception $e) {
			var_dump($e->getMessage());
			return false;
		}

		return $sql;
	}

	/**
     * Save SQL to file
     * @param string $sql
     */
    public function saveFile(&$sql, $outputDir = '.', $table='')
    {
		if (!$sql) return false;
 
		try {
			$handle = fopen($outputDir, 'a');
			fwrite($handle, $sql);
			fclose($handle);
		} catch (Exception $e) {
			var_dump($e->getMessage());
			return false;
		}

		return true;
	}
}