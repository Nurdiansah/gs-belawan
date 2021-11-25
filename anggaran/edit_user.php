<?php
include "../fungsi/fungsi.php";
include "../fungsi/koneksi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = dekripRambo($_GET['id']);

$query =  mysqli_query($koneksi, "SELECT * FROM user u
                                           JOIN divisi d
                                           ON d.id_divisi = u.id_divisi
                                           WHERE u.id_user  = '$id'");
$data = mysqli_fetch_assoc($query);

date_default_timezone_set('Asia/Jakarta');
$tanggal = date("Y-m-d H:i:s");

// echo password_hash("gs2021", null);

// $options = [
//     'cost' => 12,
// ];

// register password
// $passwordGue = password_hash("rasmuslerdorf", PASSWORD_BCRYPT, $options);


// // See the password_hash() example to see where this came from.
// echo $passwordGue = $passwordGue;

// if (password_verify('rasmuslerdorf', $passwordGue)) {
//     echo '<br>Password is valid!';
// } else {
//     echo '<br>Invalid password.';
// }



if (isset($_POST['update'])) {

    $id_user = $_POST['id_user'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $id_divisi = $_POST['id_divisi'];

    $query = "UPDATE user 
              SET   nama = '$nama', 
                    username = '$username',
                    email = '$email',
                    id_divisi = '$id_divisi'
              WHERE id_user ='$id_user' ";


    $hasil = mysqli_query($koneksi, $query);
    if ($hasil) {
        setcookie('pesan', 'User Berhasil di ubah!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header("location:index.php?p=edit_user&id=" . enkripRambo($id_user));
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

if (isset($_POST['reset'])) {
    $id_user = $_POST['id_user'];
    $pw_pic = md5($_POST['pw_pic']);

    $password = md5('gs2021');

    // melakukan cek apakah password yang di inputkan sesuai dengan password akun
    if ($pw_pic == $pwUser) {

        $query = "UPDATE user 
                SET  password = '$password'
                WHERE id_user ='$id_user' ";
        $hasil = mysqli_query($koneksi, $query);

        if ($hasil) {
            setcookie('pesan', 'Password User Berhasil di ubah!', time() + (3), '/');
            setcookie('warna', 'alert-success', time() + (3), '/');

            header("location:index.php?p=edit_user&id=" . enkripRambo($id_user));
        } else {
            die("ada kesalahan : " . mysqli_error($koneksi));
        }
    } else {

        setcookie('pesan', 'Password User Gagal di ubah! <br> *karena password yang anda masukan salah <br> silahkan coba kembali.', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');

        header("location:index.php?p=edit_user&id=" . enkripRambo($id_user));
    }


    print_r($pwUser);
    die;
}
?>

<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>
    <div class="row">
        <div class="col-sm-8 col-xs-8">
            <div class="box box-primary">
                <div class="row">
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Edit User</h3>
                </div>
                <form method="post" name="form" action="" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" value="<?= $data['id_user']; ?>" name="id_user">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="nama" class="col-sm-5 control-label">Nama</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control" name="nama" value="<?= $data['nama'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="username" class="col-sm-5 control-label">Username</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control" name="username" value="<?= $data['username'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="email" for="email" class="col-sm-5 control-label">Email</label>
                            <div class="col-sm-4">
                                <input type="email" required class="form-control" name="email" value="<?= $data['email'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-5 control-label">Divisi</label>
                            <div class="col-sm-4">
                                <select name="id_divisi" id="" class="form-control">
                                    <option value="<?= $data['id_divisi']; ?>"><?= $data['nm_divisi']; ?></option>
                                    <?php
                                    $idDivisi = $data['id_divisi'];
                                    $divisions = mysqli_query($koneksi, " SELECT * FROM divisi WHERE id_divisi != '$idDivisi' ");

                                    foreach ($divisions as $divisi) {
                                        echo "<option value='" . $divisi['id_divisi'] . "'>" . $divisi['nm_divisi'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <!-- <button class="btn btn-warning  col-sm-offset-5 col-xs-offset-5"> <i class="fa fa-recycle"></i> Reset Passsword</button> -->
                            <button type="button" class="btn btn-warning col-sm-offset-5 col-xs-offset-5" data-toggle="modal" data-target="#resetPassword">
                                <i class="fa fa-recycle"></i>
                                Reset Passsword
                            </button>
                            <button type="submit" name="update" class="btn btn-primary"> <i class="fa fa-save"></i> Update </button>
                            <!-- &nbsp; -->
                            <a href="index.php?p=user" class="btn btn-danger"> <i class="fa fa-times"></i> Batal</a>
                        </div>
                    </div>
                </form>



            </div>
        </div>
        <!-- Akhir card edit -->

        <!-- Nav kanan -->
        <div class="col-sm-3 col-xs-4">
            <div class="box box-success">
                <!-- <div class="card" style="width: 18rem;"> -->
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"> Navbar</li>
                    <a href="index.php">
                        <li class="list-group-item"> <i class="fa fa-dashboard"></i> Dashboard</li>
                    </a>
                    <a href="index.php?p=user">
                        <li class="list-group-item"> <i class="fa fa-users"></i> Data User</li>
                    </a>
                </ul>
            </div>
            <!-- </div> -->
        </div>
        <!-- Akhir nav kanan -->

        <!-- Modal -->
        <div class="modal fade" id="resetPassword" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="modalLabel">Konfirmasi</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="POST" name="form">
                        <input type="hidden" name="id_user" value="<?= $data['id_user']; ?>">
                        <div class="modal-body">
                            <div class="form-group">
                                <h4>Apakah anda yakin ingin mereset password <b> <?= $data['nama']; ?> </b> ? </h4>
                            </div>
                            <div class="form-group">
                                <span class="text-danger"> <i>*Password user akan ke rubah menjadi <b>"gs2021"</b></i> </span>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-3">Password Anda</label>
                                <div class="col-sm-8">
                                    <input type="password" name="pw_pic" class="form-control" required>
                                </div>
                            </div>
                            <br>
                            <br>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" name="reset" class="btn btn-primary">Ya, saya yakin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- </div> -->
</section>

<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
        $(".add-more").click(function() {
            var html = $(".copy").html();
            $(".after-add-more").after(html);
        });
        $("body").on("click", ".remove", function() {
            $(this).parents(".control-group").remove();
        });
    });
</script>