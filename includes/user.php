<?php class User {
	// Attribútumok
	private $id;
	private $name;
	private $email;
	private $password;
	private $salt;
	private $active;
	
	// Konstruktor
	public function __construct($id, $name, $email, $password, $salt, $active) {
		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
		$this->password = $password;
		$this->salt = $salt;
		$this->active = $active;
	}
	
	// Get-ek
	public function getId() {
		return $this->$id;
	}
	public function getName() {
		return $this->$name;
	}
	public function getEmail() {
		return $this->$email;
	}
	public function getPass() {
		return $this->$password;
	}
	public function getSalt() {
		return $this->$Salt;
	}
	public function isActive() {
		if($this->$active == 1) {
			return true;
		}
		return false;
	}
}