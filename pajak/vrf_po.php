<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
    $id_po = $_POST['id_po'];
    $id_bkk = $_POST['id_bkk'];

    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];
    $nilai_ppn = str_replace(".", "", $_POST['ppn_nilai']);
    $id_pph = $_POST['id_pph'];
    $harga = str_replace(".", "", $_POST['jml']);
    if ($_POST['pph_nilai2'] == 0) {
        $nilai_pph = penghilangTitik($_POST['pph_nilai']);
    } else {
        $nilai_pph = $_POST['pph_nilai2'];
    }

    // cek user
    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]' ");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $nama = $rowUser['nama'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    mysqli_begin_transaction($koneksi);

    $query = "UPDATE bkk_final SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , 
                                nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', 
                                id_pph = '$id_pph', nominal = '$harga', status_bkk = '1'
                            WHERE id = '$id_bkk'
                ";
    $hasil = mysqli_query($koneksi, $query);

    $updTagihan = mysqli_query($koneksi, "UPDATE tagihan_po SET nominal = '$harga' WHERE bkk_id = '$id_bkk'");

    $updPO = mysqli_query($koneksi, "UPDATE po SET app_pajak = NOW() WHERE id_po = '$id_po'");

    // REMARK DULU KARNA PO BUKAN KEPAJAK
    // // query data buat diemail
    // $queryEmail = mysqli_query($koneksi, "SELECT * FROM po po
    //                                     JOIN detail_biayaops dbo
    //                                         ON po.kd_transaksi = dbo.kd_transaksi
    //                                     JOIN divisi d
    //                                         ON d.id_divisi = dbo.id_divisi
    //                                     JOIN biaya_ops bo
    //                                         ON dbo.kd_transaksi = bo.kd_transaksi
    //                                     WHERE id_po = '$id_po'
    //                                             ");
    // $dataEmail = mysqli_fetch_assoc($queryEmail);

    // // query buat ngirim keorang email
    // $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
    //                                             INNER JOIN divisi d
    //                                             ON u.id_divisi = d.id_divisi
    //                                             WHERE nm_divisi = 'finance'
    //                                             AND level = 'manager_keuangan'");

    // // data email
    // while ($dataUser = mysqli_fetch_assoc($queryUser)) {
    //     $link = "url=index.php?p=verifikasi_po&lvl=manager_keuangan";
    //     $name = $dataUser['nama'];
    //     $email = $dataUser['email'];
    //     $subject = "Approval PO [" . $dataEmail['po_number'] . "]";
    //     $body = addslashes("<font style='font-family: Courier;'>
    //                             Dear Bapak/Ibu <b>$name</b>,<br><br>
    //                             Diberitahukan bahwa divisi <b>" . $dataEmail['nm_divisi'] . "</b> telah membuat pengajuan PO, dengan rincian sbb:<br>
    //                             <table>
    //                                 <tr>
    //                                     <td style='font-family: Courier;'>No PO</td>
    //                                     <td style='font-family: Courier;'>: " . $dataEmail['po_number'] . "</td>
    //                                 </tr>
    //                                 <tr>
    //                                     <td style='font-family: Courier;'>Divisi</td>
    //                                     <td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
    //                                 </tr>
    //                                 <tr>
    //                                     <td style='font-family: Courier;'>Nama Barang</td>
    //                                     <td style='font-family: Courier;'>: " . $dataEmail['nm_barang'] . "</td>
    //                                 </tr>
    //                                 <tr>
    //                                     <td style='font-family: Courier;'>Keterangan</td>
    //                                     <td style='font-family: Courier;'>: " . $dataEmail['keterangan'] . "</td>
    //                                 </tr>
    //                                 <tr>
    //                                     <td style='font-family: Courier;'>Total</td>
    //                                     <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['grand_totalpo']) . "</td>
    //                                 </tr>
    //                                 <tr>
    //                                     <td style='font-family: Courier;'>Tanggal Pengajuan</td>
    //                                     <td style='font-family: Courier;'>: " . $dataEmail['tgl_po'] . "</td>
    //                                 </tr>
    //                                 <tr>
    //                                     <td style='font-family: Courier;'>Approve Manager</td>
    //                                     <td style='font-family: Courier;'>: " . $dataEmail['app_mgr'] . "</td>
    //                                 </tr>
    //                                 <tr>
    //                                     <td style='font-family: Courier;'>Bidding Purchasing</td>
    //                                     <td style='font-family: Courier;'>: " . $dataEmail['app_purchasing'] . "</td>
    //                                 </tr>
    //                                 <tr>
    //                                     <td style='font-family: Courier;'>Approve Manager GA</td>
    //                                     <td style='font-family: Courier;'>: " . $dataEmail['app_mgr_ga'] . "</td>
    //                                 </tr>
    //                                 <tr>
    //                                     <td style='font-family: Courier;'>Verifikasi Pajak</td>
    //                                     <td style='font-family: Courier;'>: " . $tanggal . "</td>
    //                                 </tr>
    //                             </table>
    //                             <br>
    //                             Mohon untuk melakukan <i>Approval</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
    //                             Best Regards,<br>
    //                             This email auto generate by system.
    //                         </font>");

    //     $queue = createQueueEmail($name, $email, $subject, $body);
    // }

    if ($hasil) {
        // mysql commit transaction
        mysqli_commit($koneksi);

        setcookie('pesan', 'PO berhasil di Submit!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        // mysql rollback transaction
        mysqli_rollback($koneksi);

        setcookie('pesan', 'PO gagal di Submit!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=verifikasi_po");
} else if (isset($_POST['simpan'])) {

    // ada tambahan untuk edit sub, total pembulatan po

    $id_po = $_POST['id_po'];
    $id_bkk = $_POST['id_bkk'];

    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];
    $nilai_ppn = str_replace(".", "", $_POST['ppn_nilai']);
    $id_pph = $_POST['id_pph'];
    $harga = str_replace(".", "", $_POST['jml']);

    if ($_POST['pph_nilai2'] == 0) {
        $nilai_pph = penghilangTitik($_POST['pph_nilai']);
    } else {
        $nilai_pph = $_POST['pph_nilai2'];
    }

    // cek user
    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]' ");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $nama = $rowUser['nama'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    mysqli_begin_transaction($koneksi);

    $query = "UPDATE bkk_final SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , 
                                nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', 
                                id_pph = '$id_pph', nominal = '$harga'
                            WHERE id = '$id_bkk'
                ";

    $hasil = mysqli_query($koneksi, $query);

    $updTagihan = mysqli_query($koneksi, "UPDATE tagihan_po SET nominal = '$harga' WHERE bkk_id = '$id_bkk'");

    $updPO = mysqli_query($koneksi, "UPDATE po SET app_pajak = NOW() WHERE id_po = '$id_po'");

    if ($hasil) {
        // mysql commit transaction
        mysqli_commit($koneksi);

        setcookie('pesan', 'PO berhasil di simpan!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        // mysql rollback transaction
        mysqli_rollback($koneksi);

        setcookie('pesan', 'PO gagal di simpan!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=verifikasi_dpo&id=" . enkripRambo($id_po) . "&bkk=" . enkripRambo($id_bkk));
}
?>
<!-- pindah -->
<!--  -->