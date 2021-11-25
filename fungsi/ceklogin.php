<?php  

	if (isset($_SESSION['login'])) {
		if ($_SESSION['level'] == "unit_pelayanan") {
			header("location:checker/index.php");
		} else if ($_SESSION['level'] == "kasir"){
			header("location:kasir/index.php");
		} else {
			header("location:index.php");
		}
	}

?>