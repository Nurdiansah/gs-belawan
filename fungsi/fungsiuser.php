<?php
include "koneksi.php";

//
//
//
//      Fungsi fungsi untuk user
//
//
//

// fungsi untuk cek username
function cekUsername($id_user)
{
    global $koneksi;

    $query = mysqli_query($koneksi, " SELECT username
                                                FROM gs.user
                                                WHERE id_user = '$id_user' ");
    $row = mysqli_fetch_assoc($query);
    $username = $row['username'];

    return $username;
}
