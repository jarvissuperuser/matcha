<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of psn
 *
 * @author timothy
 */
class psn {

	public $name;
	public $lastname;
	public $email;
	public $pwd;
	public $username;

	public function __construct($e, $p, $u = '', $n = null, $l = null) {
		$this->name = $n;
		$this->lastname = $l;
		$this->email = $e;
		$this->username = $u;
		$this->pwd = hash("sha256", $p);
	}

}
