<?php

require_once'config/cfg.php';
$rg = null;
$con = new con();
$pg = 0;
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
	$pg = 1;
}
if (filter_input(INPUT_GET, "login") == 'return') {
	echo $lgn;
	$pg = 1;
}
if (filter_input(INPUT_GET, "home") == 'new') {
	if (isset($_SESSION['login'])) {
		echo $hm;
	} else {
		header('Location: ?login=return');
	}
	$pg = 1;
}
if (filter_input(INPUT_GET, "profile") == 'editself') {
	if (isset($_SESSION['login'])) {
		echo $pe;
	} else {
		header('Location: ?login=return');
	}
	$pg = 1;
}
if ($pg == 0) {
	header('Location: ?home=new');
}

echo "<script src='js/bootstrap.min.js'></script>";
echo "<script src='js/custom.js'></script>";
echo "</body></html>";
