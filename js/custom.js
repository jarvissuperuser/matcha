function lo() {

	$.post("./index.php", {logout: 'yes'}, function (r) {
		console.log(r);
		alert(r.works);
		window.location = "./index.php";
	}, 'JSON');
	console.log("triggered");
}


