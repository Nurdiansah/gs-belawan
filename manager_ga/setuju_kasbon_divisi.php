<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['setuju'])) {
    $id_kasbon = $_POST['id'];
    $vrf_pajak = $_POST['vrf_pajak'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    // query data buat diemail
    $queryEmail = mysqli_query($koneksi, "SELECT * FROM kasbon ks
                                                JOIN user u
                                                    ON u.id_user = ks.id_manager   
                                                JOIN detail_biayaops dbo
                                                    ON id_dbo = dbo.id    
                                                JOIN divisi d
                                                    ON d.id_divisi = dbo.id_divisi
                                                WHERE id_kasbon = '$id_kasbon'
                                                ");
    $dataEmail = mysqli_fetch_assoc($queryEmail);

    // BEGIN/START TRANSACTION        
    mysqli_begin_transaction($koneksi);

    if ($vrf_pajak == 'bp') {
        // jika kasbon verifikasi sebelum pembayaran
        $query = "UPDATE kasbon SET  status_kasbon = '3', app_supervisor = '$tanggal'
                WHERE id_kasbon ='$id_kasbon' ";
        $hasil = mysqli_query($koneksi, $query);


        // query buat ngirim keorang email
        $queryUser = mysqli_query($koneksi, "SELECT * FROM user 
                                                WHERE level = 'manager_keuangan'");

        // data email
        while ($dataUser = mysqli_fetch_assoc($queryUser)) {
            $link = "url=index.php?p=verifikasi_kasbon&sp=vk_user&lvl=manager_keuangan";
            $name = $dataUser['nama'];
            $email = $dataUser['email'];
            $subject = "Approval Kasbon " . $dataEmail['id_kasbon'];
            $body = addslashes("<font style='font-family: Courier;'>
                                Dear Bapak/Ibu <b>$name</b>,<br><br>
                                Diberitahukan bahwa divisi <b>" . $dataEmail['nm_divisi'] . "</b> telah membuat pengajuan Kasbon, dengan rincian sbb:<br>
                                <table>
                                    <tr>
                                        <td style='font-family: Courier;'>Kode Transaksi</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['id_kasbon'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Divisi</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Keterangan</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['keterangan'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Total</td>
                                        <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['harga_akhir']) . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Tanggal Pengajuan</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['tgl_kasbon'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Approve Manager</td>
                                        <td style='font-family: Courier;'>: " . $tanggal . "</td>
                                    </tr>
                                </table>
                                <br>
                                Mohon untuk melakukan <i>Verifikasi</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                                Best Regards,<br>
                                This email auto generate by system.
                            </font>");

            // insert queue email
            $queue = createQueueEmail($name, $email, $subject, $body);
        }
    } else if ($vrf_pajak == 'as') {
        // jika kasbon verifikasi setelah lpj
        $query = "UPDATE kasbon SET  status_kasbon = '3', app_supervisor = '$tanggal'
                WHERE id_kasbon ='$id_kasbon' ";
        $hasil = mysqli_query($koneksi, $query);


        // query buat ngirim keorang email
        $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
                                                INNER JOIN divisi d
                                                ON u.id_divisi = d.id_divisi
                                                WHERE nm_divisi = 'finance'
                                                AND level = 'manager_keuangan'");

        // data email
        while ($dataUser = mysqli_fetch_assoc($queryUser)) {
            $link = "url=index.php?p=approval_kasbon&lvl=manager_keuangan";
            $name = $dataUser['nama'];
            $email = $dataUser['email'];
            $subject = "Approval Kasbon " . $dataEmail['id_kasbon'];
            $body = addslashes("<font style='font-family: Courier;'>
                                Dear Bapak/Ibu <b>$name</b>,<br><br>
                                Diberitahukan bahwa divisi <b>" . $dataEmail['nm_divisi'] . "</b> telah membuat pengajuan Kasbon, dengan rincian sbb:<br>
                                <table>
                                    <tr>
                                        <td style='font-family: Courier;'>Kode Transaksi</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['id_kasbon'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Divisi</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Keterangan</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['keterangan'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Total</td>
                                        <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['harga_akhir']) . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Tanggal</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['tgl_kasbon'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Approve Manager</td>
                                        <td style='font-family: Courier;'>: " . $tanggal . "</td>
                                    </tr>
                                </table>
                                <br>
                                Mohon untuk melakukan <i>Approval</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                                Best Regards,<br>
                                This email auto generate by system.
                            </font>");

            // insert queue email
            $queue = createQueueEmail($name, $email, $subject, $body);
        }
    }

    if ($hasil && $queue) {
        # jika semua query berhasil di jalankan

        mysqli_commit($koneksi);

        setcookie('pesan', 'Kasbon berhasil di Approve!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        #jika ada query yang gagal

        mysqli_rollback($koneksi);

        setcookie('pesan', 'Kasbon gagal di Approve!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=approval_kasbon");
}

?>
<!--  -->