<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
    $id = base64_decode($_GET['id']);

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    $queryEmail = mysqli_query($koneksi, "SELECT *, mgr.nama as nm_mgr, usr.nama as nm_pemohon, mgr.email as email_mgr, bkk.nilai_barang + bkk.nilai_jasa AS nominal, bkk.id_manager as bkid_manager, usr.id_manager as usrid_manager
                                            FROM bkk bkk
                                            JOIN divisi dvs
                                                ON bkk.id_divisi = dvs.id_divisi
                                            JOIN user mgr
                                                ON bkk.id_manager = mgr.id_user
                                            JOIN user usr
                                                ON id_pemohon = usr.id_user
                                            WHERE id_bkk = '$id'");
    $dataEmail = mysqli_fetch_assoc($queryEmail);

    // update id_manager apabila dia kosong
    $id_mgr = $dataEmail['usrid_manager'];
    if ($dataEmail['bkid_manager'] == "" || is_null($dataEmail['bkid_manager'])) {
        $updMgr = mysqli_query($koneksi, "UPDATE bkk SET id_manager = '$id_mgr' WHERE id_bkk = '$id'");
    }

    $link = "url=index.php?p=approval_biayanonops&lvl=manager";
    // data email
    $name = $dataEmail['nm_mgr'];
    $email = $dataEmail['email_mgr'];
    $subject = "Approval Biaya Umum " . $dataEmail['kd_transaksi'];
    $body = addslashes("<font style='font-family: Courier;'>
                        Dear Bapak/Ibu <b>$name</b>,<br><br>
                        Diberitahukan bahwa <b>" . $dataEmail['nm_pemohon'] . "</b> telah membuat pengajuan Biaya Umum, dengan rincian sbb:<br>
                        <table>
                            <tr>
                                <td style='font-family: Courier;'>Kode Transaksi</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['kd_transaksi'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Divisi</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Nama Vendor</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['nm_vendor'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Keterangan</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['keterangan'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Nilai Barang</td>
                                <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['nilai_barang']) . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Nilai Jasa</td>
                                <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['nilai_jasa']) . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>PPN</td>
                                <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['ppn_nilai']) . " (" . $dataEmail['ppn_persen'] . "%)</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>PPH</td>
                                <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['pph_nilai']) . " (" . $dataEmail['pph_persen'] . "%)</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Total</td>
                                <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['jml_bkk']) . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Tanggal</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['tgl_pengajuan'] . "</td>
                            </tr>
                        </table>
                        <br>
                        Mohon untuk melakukan <i>Approval</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                        Best Regards,<br>
                        This email auto generate by system.
                        </font>");

    mysqli_begin_transaction($koneksi);

    // insert queue email
    $queue = createQueueEmail($name, $email, $subject, $body);

    $query1 = mysqli_query($koneksi, "UPDATE bkk SET status_bkk = 1, tgl_bkk = '$tanggal' WHERE id_bkk = '$id' ");

    $id_anggaran = $dataEmail['id_anggaran'];
    $nominal = $dataEmail['nominal'];
    $insReaSem =  insRealisasiSem('BUM', $id, $id_anggaran, $nominal);

    if ($query1 && $insReaSem) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'Biaya Umum berhasil di release!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        #jika ada query yang gagal
        mysqli_rollback($koneksi);

        setcookie('pesan', 'Biaya Umum Gagal di release  !<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=proses_biayanonops");
}
