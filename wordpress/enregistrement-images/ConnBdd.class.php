<?php
class ConnBdd {
	private $host, $dbname, $user, $pssword, $bdd;

	public function getBdd() {
		return $this -> bdd;
	}
	private function setBdd($db) {
		$this -> bdd = $db;
	}
	
	public function __construct($dbname) {
		$this -> host = 'localhost';
		$this -> dbname = $dbname;
		$this -> user = 'challing_adoukof';
		$this -> pssword = 'kHv&P-pkCSr98vsf';
		$this -> connect();
	}

	private function connect() {
		try {
			$bdd = new PDO('mysql:host='.$this -> host. ';dbname='. $this -> dbname , $this -> user, $this -> pssword);
		} catch(Exception $e) {
			die('Erreur : ' . $e -> getMessage());
		}
		$this -> setBdd($bdd);
		
	}

}
