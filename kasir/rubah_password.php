<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$err = "";

if (isset($_POST['submit'])) {

    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $id_user = $rowUser['id_user'];
    $password = $rowUser['password'];

    $pw_old = md5($_POST['pw_old']);
    $pw_new = $_POST['pw_new'];
    $pw_con = $_POST['pw_con'];

    if ($password == $pw_old) {
        # jika password lama benar
        if ($pw_new == $pw_con) {
            # code...
            $pw_new = md5($pw_new);
            $querybkk = "UPDATE user
                        SET password= '$pw_new' 
                        WHERE id_user = '$id_user' ";
            mysqli_query($koneksi, $querybkk);

            $err = "
                <div class='row' style='margin-top: 15px';>
                <div class='col-md-12'>
                        <div class='box box-solid bg-green'>
                            <div class='box-header'>
                                <h3 class='box-title'>Password berhasil di rubah!</h3>
                            </div>                           
                            </div>
                        </div>
                    </div>
                </div>";
        } else {
            # code...
            $err = "
		<div class='row' style='margin-top: 15px';>
	       <div class='col-md-12'>
	        	<div class='box box-solid bg-red'>
	        		<div class='box-header'>
	        			<h3 class='box-title'>Rubah Password Gagal!</h3>
	        		</div>
	        		<div class='box-body'>
	        			<p>Password tidak sesuai.</p>
	        		</div>
			        </div>
			     </div>
		     </div>
	    </div>";
        }
    } else {
        $err = "
		<div class='row' style='margin-top: 15px';>
	       <div class='col-md-12'>
	        	<div class='box box-solid bg-red'>
	        		<div class='box-header'>
	        			<h3 class='box-title'>Rubah Password Gagal!</h3>
	        		</div>
	        		<div class='box-body'>
	        			<p>Anda salah dalam menginputkan password lama.</p>
	        		</div>
			        </div>
			     </div>
		     </div>
	    </div>";
    }
}

?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-offset-3 col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Change Password</h3>
                </div>
                <div class="box-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Current Password</label>
                            <input type="password" class="form-control" name="pw_old">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">New Password</label>
                            <input type="password" class="form-control" name="pw_new">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Confirm Password</label>
                            <input type="password" class="form-control" name="pw_con">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="submit" value="submit" class="btn bg-primary ">Change Password</button>
                        </div>
                    </form>
                    <?= $err; ?>
                </div>
            </div>
        </div>
    </div>
</section>