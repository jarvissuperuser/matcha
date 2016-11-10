<?php
class pn extends psn {
	public $pic;
	public $age;
	public $local;
	public $interests;
	public $phone;
	public $pref;
	public $sex;
	public $uid;
	public $dob;
	public $country;
	public $other;
	public $city;
	public $loc;

	/** @var PDO */
	public $c;
	public $p;

	public function __construct($e, $p, $u = '', $n = null, $l = null) {
		parent::__construct($e, $p, $u, $n, $l);
		$this->p = new con();
		$this->c = $this->p->c;
		$this->uid = $this->p->getid($this->email);
	}

	public function pullq() {
		$q = "select i.cell, i.otherSocial,i.Bio i.preference, i.Interests ";
		$q .= "i.dateofbirth,i.sex, i.country, i.city";
		$q .= "from  Matcha.info i where i.iid = '{$this->uid}'";
		$stmt = $this->c->prepare($q);
		$stmt->execute();
		$stmt->bindColumn(1, $this->phone);
		$stmt->bindColumn(2, $this->other);
		$stmt->bindColumn(3, $this->pref);
		$stmt->bindColumn(4, $this->interests);
		$stmt->bindColumn(5, $this->dob);
		$stmt->bindColumn(6, $this->sex);
		$stmt->bindColumn(7, $this->country);
		$stmt->bindColumn(8, $this->city);
	}

	public function pushq() {
		$q = "Update Matcha.info set";
		$q .= "cell = :cell, otherSocial = :other";
		$q .= ",Bio =:pic ,preference = :pref,Interests = :intr";
		$q .= "dateofbirth = :dob, city = :cty, country = :ctry";
		$q .= " where iid = '{$this->uid}';";
		$stmt = $this->c->prepare($q);
		$param = array('cell' => $this->phone, 'other' => $this->other,
			'intr' => $this->interests, 'dob' => $this->dob, 'pic' => $this->pic,
			'pref' => $this->pref, 'Bio' => $this->pic, 'cty' => $this->city,
			'ctry' => $this->country);
		$stmt->execute($param);
	}

}
