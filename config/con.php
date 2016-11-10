<?php
/**
 * Manages the PDO connections
 *
 * @author timothy
 */
class con {

	/**
	 * @var PDO connection
	 */
	public $c;

	/**
	 * @var PDOStatement
	 */
	public $st;
	protected $dsn0;
    protected $dsn;
	public $d = 'root';
	public $pwd = 'cm9vdDEyMzQ=';
	public $dbname= 'Matcha';
    public function __construct() {
		try {
			$this->setDSN();
			if (!file_exists("ready.x")) {
				$this->c = new PDO($this->dsn0, $this->d, base64_decode($this->pwd));
				$this->c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$sql = file_get_contents('config/db.sql');
				$this->c->exec($sql);
				$fil = fopen("ready.x", 'w');
				fwrite($fil, $sql) or die("bad permisions");
				fclose($fil);
			}
			$this->c = new PDO($this->dsn, $this->d, base64_decode($this->pwd));
		} catch (PDOException $e) {
			$this->c = null;
			error_log($e);
		}
    }
    
    public function setdsn($con = 'mysql',$url='localhost'){
        $this->dsn0 = "$con:host=$url;";
        $this->dsn =  $this->dsn0 . 'name=' . $this->dbname;
    }

	private function suq(psn $user) {
		$q = "insert into Matcha.user set ";
		$q .= "email ='{$user->email}',";
		$q .= "password = '{$user->pwd}'";
		if ($user->username != '') {
			$q .= ", username = '{$user->username}'";
		}
		if ($user->lastname != '') {
			$q .= ", lastname = '{$user->lastname}'";
		}
		if ($user->name != '') {
			$q .= ", name = '{$user->name}'";
		}
		return $q;
	}

	private function suq1(psn $user) {
		//$q = "select `name` from Matcha.user where email='{$user->email}' "
		//		. "and pwd='{$user->pwd}'";
		//$st = $this->c->prepare($q);
		//$r = $st->execute(PDO::FETCH_OBJ);
		//$st->closeCursor();
		return " insert into Matcha.info set Bio = '{$user->email}'";
	}

	public function suuser(psn $user) {
		try {
			$cnt = $this->c->exec($this->suq($user));
			//sleep(7);
			if ($cnt > 0) {
				//$this->c->exec($this->suq1($user));
			} else {
				throw new ErrorException("Failed RegistraTion" . json_encode($this->c->errorInfo()));
			}
		} catch (Exception $ex) {
			echo json_encode([$ex->getTraceAsString(), $ex->getMessage()]);
			error_log($ex->getTraceAsString());
		}
	}
	
	public function lgn($email,$pwd){
		$q = "select count(*) as c from Matcha.user where "
				. "email = '$email' and password = '$pwd'";
		$st = $this->c->prepare($q);
		$st->execute();
		$r = $st->fetch(PDO::FETCH_OBJ);
		$st->closeCursor();
		return $r->c;
	}

	public function getid($mail) {
		$q = "select uid as c from Matcha.user where "
				. "email = ?";
		$st = $this->c->prepare($q);
		$st->execute([$mail]);
		$r = $st->fetch(PDO::FETCH_OBJ);
		$st->closeCursor();
		return $r->c;
	}

	public function setpic($uid, $url) {
		$q = "update Matcha.info values Bio = :pic where "
				. "iid = :uid";
		$st = $this->c->prepare($q);
		$st->bindParam('uid', $uid);
		$st->bindParam('pic', $url);
		$st->execute();
		$r = $st->rowCount();
		$st->closeCursor();
		return $r;
	}

	public function img_add(psn &$nam) {
		$dir = "img/";
		if (filter_input(INPUT_POST,'fileSubmit') == 'propic'){
			if (!file_exists($dir)) {
				mkdir($dir, 0777);
			}
			//make file
			$fi = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
			if (file_exists($dir . $nam->name)) {
				getimagesize($_FILES['imgtoupload']);
				$cnt = iterator_count($fi);
				//echo $cnt;
				$nam->username = $dir . $nam->name . "$cnt";
				return move_uploaded_file($_FILES['imgtoupload']['tmp_name'], $nam->username);
			} else {
				$nam->username = $dir . $nam->name;
				return move_uploaded_file($_FILES['imgtoupload']['tmp_name'], $nam->username);
			}
		}
	}

	public function imgupload(psn $m) {
		if ($this->img_add($m)) {
			$uid = $this->getid($m->email);
			if ($uid > 0) {
				$this->setpic($uid, $m->username);
			}
		} else {
			return false;
		}
	}

	public function getpsn($ml) {
		$uid = $this->getid($ml);
		return $uid;
	}

	public function __destruct() {
		$this->c = null;
	}

}
