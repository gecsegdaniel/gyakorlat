<?php class DB {
	// ADATBÁZIS HOZZÁFÉRÉSI ADATOK
	protected $user;
	protected $password;
	protected $database;
	protected $host;
	
	protected $charSet;				// A KAPCSOLAT KÓDOLÁSA
	protected $conn;				// A KAPCSOLATOT AZONOSÍTÓ ERŐFORRÁS ID
	public $isDebugMode = false;	// FEJLESZTŐI MÓD KAPCSOLÓ
	
	/**
	 * Konstruktor.
	 * 
	 * @param string $host		a hoszt neve
	 * @param string $database	az adatbázis neve
	 * @param string $user		felhasználó neve
	 * @param string $password	jelszó
	 * @param string $charSet	a kapcsolathoz használt karakterkészlet, alapértelmezetten UTF-8
	 */
	public function __construct($host, $database, $user, $password = '', $charSet = 'utf8') {
		$this->user = $user;
		$this->password = $password;
		$this->host = $host;
		$this->database = $database;
		$this->charSet = $charSet;
		
		$this->connect();
	}
	
	/**
	 * Bontja a jelenlegi kapcsolatot
	 */
	public function disconnect() {
		if ($this->conn) return mysql_close($this->conn);
		return true;
	}

	/**
	 * Kapcsolódik az adatbázishoz
	 * 
	 * @throws Exception nem sikerült a kapcsolat valamely pontját inicializálni
	 */
	protected function connect() {
		$this->disconnect();
		$this->conn = mysql_connect($this->host, $this->user, $this->password, true);
		
		if (!$this->conn) {
			throw new Exception ('Nem sikerult kapcsolodni a szerverhez: `'.$this->host.'`.');
		}	
		
		if (!mysql_select_db($this->database, $this->conn)) {
			throw new Exception ('Nem sikerult kapcsolodni az adatbazishoz: `'.$this->database.'`.');
		}
		
		mysql_query("SET CHARACTER SET ".$this->charSet, $this->conn);
		mysql_query("SET NAME ".$this->charSet, $this->conn);
	}
	
	/**
	 * Futtat egy tetszőleges SQL utasítást
	 * 
	 * @param string $sql		a futtatandó SQL kifejezés
	 * @return MySQLResource	az eredményre mutató erőforrásazonosító
	 * @throws Exception		hiba esetén kivétel a mysql_errno és mysql_error üzenetekkel
	 */
	public function query($sql) {
		if ($this->isDebugMode === true) Debug::printData($sql);
		
		$result = mysql_query($sql, $this->conn);
		if ($result === false) {
			throw new Exception(mysql_error($this->conn), mysql_errno($this->conn));
		}

		return $result;
	}
	
	/**
	 * Visszaadja az SQL-nek megfelelő sorokat az adatbázisból
	 * 
	 * @param string $sql				az SQL kifejezés
	 * @param boolean $asAssoc			TRUE: asszociatív tömbként; FALSE: számozott tömbként
	 * @return array[]
	 */
	public function getRows($sql, $asAssoc = true) {
		$result = $this->query($sql);
		$rows = array();
		
		if ($asAssoc) while ($row = mysql_fetch_assoc($result)) $rows[] = $row;
		else while ($row = mysql_fetch_row($result)) $rows[] = $row;
	
		mysql_free_result($result);
		return $rows;
	}
	
	/**
	 * Visszaadja az első megfelelő sort az adatbázisból
	 * 
	 * @param string $sql				az SQL kifejezés
	 * @param boolean $asAssoc			TRUE: asszociatív tömbként; FALSE: számozott tömbként
	 * @return array
	 */
	public function getRow($sql, $asAssoc = true) {
		if (!preg_match('/LIMIT\s+\d+(\s*,\s*\d+)?$/', $sql)) $sql .= ' LIMIT 1'; // Ha nem volt az $sql végén LIMIT, akkor hozzáfűzünk, hogy LIMIT 1
		
		$result = $this->query($sql);

		if ($asAssoc) $row = mysql_fetch_assoc($result);
		else $row = mysql_fetch_row($result);
		
		mysql_free_result($result);
		return $row;
	}
	
	/**
	 * Visszaadja egy mező értékét az adatbázisból
	 * 
	 * @param string $sql	az SQL kifejezés
	 * @return mixed
	 */
	public function getValue($sql) {
		$row = $this->getRow($sql, false);
		return $row[0];
	}
	
	/**
	 * Beszúr egy sort egy táblába és visszaadja a beszúrt elem azonosítóját
	 * 
	 * @param string $table	a tábla neve, melybe beszúrunk
	 * @param array $data	asszociatív tömb mezőnév => érték párokkal.
	 * @return mixed
	 */
	public function insert($table, $data) {
		if (!$data || !is_array($data)) return false;
		
		$keys = array_keys($data);
		foreach ($keys as $index => $key){
			$keys[$index] = DB::wrapKeyWord($key);
		}
		$keys = implode(', ', $keys);

		$values = array();
		foreach ($data as $key => $value) {
			$values[] = $this->escapeValue($value);
		}
		$values = implode(', ', $values);
		
		$sql = "INSERT INTO ".DB::wrapKeyWord($table)." (".$keys.") VALUES (".$values.")";

		if ($this->query($sql) !== false) return mysql_insert_id($this->conn);
		
		return false;
	}
	
	/**
	 * Frissít egy táblát a megadott értékekre felételek mellett
	 * 
	 * @param string $table		a tábla neve melyet frissítünk
	 * @param array $data		asszociatív tömb mezőnév => új érték párokkal.
	 * @param string $where		string, mely a where feltétel maga.
	 * @return boolean
	 */
	public function update($table, $data, $where) {
		if (!$data || !is_array($data)) return false;
		
		// A data tömb konvertálása `mező`='érték' szöveggé
		$sets = array();
		foreach ($data as $key => $value) {
			$sets[] = DB::wrapKeyWord($key).' = '.$this->escapeValue($value);
		}

		$sets = implode(',', $sets);
				
		return $this->query("UPDATE ".DB::wrapKeyWord($table)." SET ".$sets." WHERE ".$where);
	}
	
	/**
	 * Sorokat töröl egy táblából
	 * 
	 * @param string $table		a tábla neve
	 * @param string $where		string, mely a where feltétel maga.
	 * @return boolean
	 */
	public function delete($table, $where) {
		$sql = "DELETE FROM ".DB::wrapKeyWord($table)." WHERE ".$where;
		return $this->query($sql);
	}

	/**
	 * Bezárja a kulcsszavakat ` karakterek közé
	 * 
	 * @param string $keyWord	a kulcsszó
	 * @return string
	 */
	public static function wrapKeyWord($keyWord){
		if(substr($keyWord, 0, 1) != '`') $keyWord = '`'.$keyWord.'`';
		return $keyWord;
	}

	/**
	 * Bezárja az értékeket ' karakterek közé és escape-eli azokat
	 * 
	 * @param string $value		az érték
	 * @return string
	 */
	public function escapeValue($value){
		if (!is_numeric($value)) $value = "'".mysql_real_escape_string($value)."'";
		return $value;
	}
}