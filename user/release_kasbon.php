<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['release'])) {
    $id_kasbon = $_POST['id'];
    $id_dbo = $_POST['id_dbo'];
    $vrf_pajak = $_POST['vrf_pajak'];

    $queryManager = mysqli_query($koneksi, "SELECT * FROM kasbon ks
                                            JOIN user u
                                                ON u.id_user = ks.id_manager   
                                            JOIN detail_biayaops dbo
                                                ON id_dbo = dbo.id    
                                            JOIN divisi d
                                                ON d.id_divisi = dbo.id_divisi
                                            WHERE id_kasbon = '$id_kasbon'
                                    ");
    $dataManager = mysqli_fetch_assoc($queryManager);

    $link = "url=index.php?p=approval_kasbon&lvl=manager";

    // data email
    $name = $dataManager['nama'];
    $email = $dataManager['email'];
    $subject = "Approval Kasbon " . $dataManager['id_kasbon'];
    $body = addslashes("<font style='font-family: Courier;'>
                        Dear Bapak/Ibu <b>$name</b>,<br><br>
                        Diberitahukan bahwa divisi <b>" . $dataManager['nm_divisi'] . "</b> telah membuat pengajuan Kasbon, dengan rincian sbb:<br>
                        <table>
                            <tr>
                                <td style='font-family: Courier;'>Kode Transaksi</td>
                                <td style='font-family: Courier;'>: " . $dataManager['id_kasbon'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Divisi</td>
                                <td style='font-family: Courier;'>: " . $dataManager['nm_divisi'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Keterangan</td>
                                <td style='font-family: Courier;'>: " . $dataManager['keterangan'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Total</td>
                                <td style='font-family: Courier;'>: " . formatRupiah2($dataManager['harga_akhir']) . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Tanggal</td>
                                <td style='font-family: Courier;'>: " . $dataManager['tgl_kasbon'] . "</td>
                            </tr>
                        </table>
                        <br>
                        Mohon untuk melakukan <i>Approval</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                        Best Regards,<br>
                        This email auto generate by system.
                        </font>");

    // BEGIN/START TRANSACTION        
    mysqli_begin_transaction($koneksi);

    // insert queue email
    $queue = createQueueEmail($name, $email, $subject, $body);

    // UPDATE KASBON
    $query = "UPDATE kasbon SET vrf_pajak = '$vrf_pajak', status_kasbon = '1'
                            WHERE id_kasbon ='$id_kasbon';";
    $hasil = mysqli_query($koneksi, $query);

    $id_anggaran = $dataManager['id_anggaran'];
    $nominal = $dataManager['harga_akhir'];

    $insReaSem =  insRealisasiSem('KBN', $id_kasbon, $id_anggaran, $nominal);

    if ($hasil && $queue && $insReaSem) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'Kasbon berhasil di release!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        #jika ada query yang gagal
        mysqli_rollback($koneksi);

        setcookie('pesan', 'Kasbon Gagal di release  !<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');

        // die("ada kesalahan : " . mysqli_error($koneksi));
    }
    header("location:index.php?p=buat_kasbon");
}

?>
<!--  -->