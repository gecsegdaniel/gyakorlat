<?php class News {
	// Attribútumok
	private $id;
	private $title;
	private $text;
	private $writeDate;
	private $userId;
	private $publical;
	
	// Konstruktor
	public function __construct($id, $title, $text, $writeDate, $userId, $publical) {
		$this->id = $id;
		$this->title = $title;
		$this->text = $text;
		$this->writeDate = $writeDate;
		$this->userId = $userId;
		$this->publical = $publical;
	}
	
	// Get-ek
	public function getId() {
		return $this->$id;
	}
	public function getTitle() {
		return $this->$title;
	}
	public function getText() {
		return $this->$text;
	}
	public function getWriteDate() {
		return $this->$writeDate;
	}
	public function getUserId() {
		return $this->$userId;
	}
	public function ispublic() {
		if($this->$publical == 1) {
			return true;
		}
		return false;
	}
}