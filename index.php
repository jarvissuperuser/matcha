<?php

require_once'config/cfg.php';
$head = file_get_contents('cls/test.hd');
$hdr = file_get_contents('cls/hdr.hd');
$lgn = file_get_contents('cls/lgn.hd');
$reg = file_get_contents('cls/suf.hd');
$hm = file_get_contents("cls/hm.hd");
$rg = null;
$con = new con();
if (filter_input(0, 'logout') == 'yes') {
	//session_unregister("login");
	session_destroy();
	header("Location: result.php");
}
if (filter_input(INPUT_POST, 'submit') == 'ok') {
	$e = filter_input(INPUT_POST, 'log');
	$p = filter_input(INPUT_POST, 'pwd');
	$s = filter_input(INPUT_POST, 'surname');
	$l = filter_input(INPUT_POST, 'username');
	$n = filter_input(INPUT_POST, 'name');
	$rg = new psn($e, $p, $l, $n, $s);
	try {
		$con->suuser($rg);
		$_SESSION['login'] = $e;
		$_SESSION['special'] = $rg->pwd;
	} catch (Exception $ex) {
		$rg->name = json_encode($ex);
	}
	
} elseif (filter_input(INPUT_POST, 'login') == 'ok') {
	$u = new psn(filter_input(INPUT_POST, 'log'), filter_input(INPUT_POST, 'pwd'));
	try {
		$cnt = $con->lgn($u->email, $u->pwd);
		//echo json_encode($cnt);
		if ($cnt > 0) {
			$_SESSION['login'] = $u->email;
			$_SESSION['special'] = $u->pwd;
			header('Location: ?home=new');
		} else {
			throw new Exception(json_encode([$con->c->errorInfo(), $cnt]));
		}
	} catch (Exception $exc) {
		echo json_encode($exc);
	}
}
echo $head;
echo $hdr;
echo "<body class=container>";
if (filter_input(INPUT_GET, 'signup') == 'newuser') {
	echo $reg;
	echo json_encode($rg);
}
if (filter_input(INPUT_GET, "login") == 'return') {
	echo $lgn;
}
if (filter_input(INPUT_GET, "home") == 'new') {
	if (isset($_SESSION['login'])) {
		echo $hm;
	} else {
		header('Location: ?login=return');
	}
}

echo "<script src='js/bootstrap.min.js'></script>";
echo "<script src='js/custom.js'></script>";
echo "</body></html>";
