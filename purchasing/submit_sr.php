<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['approve'])) {
    $id = $_POST['id'];
    $id_user = $_POST['id_user'];
    $id_manager = $_POST['id_manager'];
    $doc_quotation = $_POST['doc_quotation'];
    $doc_penawaran = $_POST['doc_penawaran'];
    $nilai_barang = $_POST['total'];
    $nilai_ppn = $_POST['nilai_ppn'];
    $user_id = $_POST['id_user'];
    $divisi_id = $_POST['id_divisi'];
    $sr_id = $_POST['id_sr'];
    $id_manager = $_POST['id_manager'];

    $tanggal = dateNow();

    if ($doc_penawaran == ' ') {
        setcookie('pesan', 'File Document Penawaran Atau Quotation anda belum terupload , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');

        header("location:index.php?p=verifikasi_sr");

        die;
    }

    // BEGIN/START TRANSACTION        
    mysqli_begin_transaction($koneksi);

    // cek nominal dari SR
    $querySR  = mysqli_query($koneksi, "SELECT * FROM sr WHERE id_sr = '$id'");
    $dataSR = mysqli_fetch_assoc($querySR);
    $grand_total = $dataSR['grand_total'];

    // // Update SR
    $update = mysqli_query($koneksi, "UPDATE sr 
                                        SET  status = '3', app_purchasing = '$tanggal', komentar = NULL
                                        WHERE id_sr = '$sr_id'
                ");

    // jika diatas 10 jt masuk ke tabel SO
    if ($grand_total > 10000000) {
        // $tgl_tempo = $_POST['tgl_tempo'];

        $queryIns = mysqli_query($koneksi, "INSERT INTO so (sr_id, id_user, id_divisi, id_manager, id_anggaran, id_supplier, nm_barang, nominal, diskon, total, nilai_ppn, grand_total, note, keterangan, komentar, doc_ba, doc_penawaran, doc_quotation, created_at, updated_at, created_by, updated_by, app_mgr, app_purchasing, status)
                                            SELECT id_sr, id_user, id_divisi, id_manager, id_anggaran, id_supplier, nm_barang, nominal, diskon, total, nilai_ppn, grand_total, note, keterangan, komentar, doc_ba, doc_penawaran, doc_quotation, created_at, updated_at, created_by, updated_by, app_mgr, app_purchasing, '1'
                                            FROM sr
                                            WHERE id_sr = '$id'
                        ");


        // query data buat diemail
        $queryEmail = mysqli_query($koneksi, "SELECT * FROM so s
                                                JOIN user u
                                                    ON u.id_user = s.id_manager     
                                                JOIN divisi d
                                                    ON d.id_divisi = s.id_divisi
                                                WHERE s.sr_id = '$id'
                        ");
        $dataEmail = mysqli_fetch_assoc($queryEmail);

        // query buat ngirim keorang email
        $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
                                                INNER JOIN divisi d
                                                    ON u.id_divisi = d.id_divisi
                                                WHERE nm_divisi = 'ga umum'
                                                AND level = 'manager_ga'
                        ");


        // data email
        while ($dataUser = mysqli_fetch_assoc($queryUser)) {
            $link = "url=index.php?p=approval_srga&lvl=manager_ga";
            $name = $dataUser['nama'];
            $email = $dataUser['email'];
            $subject = "Approval Service Order " . $dataEmail['nm_barang'];
            $body = addslashes("<font style='font-family: Courier;'>
                                Dear Bapak/Ibu <b>$name</b>,<br><br>
                                Diberitahukan bahwa divisi <b>" . $dataEmail['nm_divisi'] . "</b> telah membuat pengajuan SR, dengan rincian sbb:<br>
                                <table>
                                    <tr>
                                        <td style='font-family: Courier;'>Nama Barang / Alat</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['nm_barang'] . "</td>
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
                                        <td style='font-family: Courier;'>Nominal</td>
                                        <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['grand_total']) . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Tanggal</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['created_at'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Approve Manager</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_mgr'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Bidding Purchasing</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_purchasing'] . "</td>
                                    </tr>
                                </table>
                                <br>
                                Mohon untuk melakukan <i>Approve</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                                Best Regards,<br>
                                This email auto generate by system.
                            </font>");

            $queue = createQueueEmail($name, $email, $subject, $body);
            // echo $name . "<br>" . $email . "<br>" . $subject . "<br><br>" . $body . "<br><br><hr>";
            // die;

        }
    } elseif ($grand_total > 100000 && $grand_total <= 10000000) {
        // jika dibawah 10 juta dan diatas 100 rb , maka masuknya ke tbl kasbon
        $kode_otomatis = nomorKasbon();

        //query kasbon
        $queryIns = mysqli_query($koneksi, "INSERT kasbon (id_kasbon, id_dbo, sr_id, divisi_id, nilai_barang, nilai_ppn, harga_akhir, tgl_kasbon, user_id, id_manager, status_kasbon) VALUES
		                                                 ('$kode_otomatis', '$id','$sr_id','$divisi_id','$nilai_barang','$nilai_ppn', '$grand_total', '$tanggal','$user_id','$id_manager', '1');
		");

        // query data buat diemail
        $queryEmail = mysqli_query($koneksi, "SELECT * FROM kasbon ks
                                                JOIN sr sr
                                                    ON id_sr = sr_id
                                                JOIN divisi d
                                                    ON d.id_divisi = divisi_id
                                                WHERE id_kasbon = '$kode_otomatis'
                        ");
        $dataEmail = mysqli_fetch_assoc($queryEmail);

        // query buat ngirim keorang email
        $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
                                                INNER JOIN divisi d
                                                    ON u.id_divisi = d.id_divisi
                                                WHERE nm_divisi = 'ga umum'
                                                AND level = 'manager_ga'
                        ");

        // data email
        while ($dataUser = mysqli_fetch_assoc($queryUser)) {
            $link = "url=index.php?p=verifikasi_kasbon&sp=vk_sr&lvl=manager_ga";
            $name = $dataUser['nama'];
            $email = $dataUser['email'];
            $subject = "Approval Kasbon " . $kode_otomatis;
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
                                    <td style='font-family: Courier;'>: " . $dataEmail['app_mgr'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Bidding Purchasing</td>
                                    <td style='font-family: Courier;'>: " . $tanggal . "</td>
                                </tr>
                            </table>
                            <br>
                            Mohon untuk melakukan <i>Approve</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                            Best Regards,<br>
                            This email auto generate by system.
                        </font>");


            $queue = createQueueEmail($name, $email, $subject, $body);
        }
    } elseif ($grand_total <= 100000) {
        // jika dibawah 100000 rb , maka masuknya ke tbl petty cas        

        $qManagerGa = mysqli_query($koneksi, "SELECT * FROM user WHERE level = 'manager_ga' ");

        $dataManagerGa = mysqli_fetch_assoc($qManagerGa);
        $idManagerGa = $dataManagerGa['id_user'];

        $id_anggaran = $_POST['id_anggaran'];
        $id_divisi = $_POST['id_divisi'];
        $nama = $_POST['pembuat'];
        $keterangan_pettycash = $_POST['keterangan'];
        $total_pettycash = $_POST['total'];

        // KODE OTOMATIS
        $kode_otomatis = nomorPettycash();

        $queryIns = mysqli_query($koneksi, "INSERT INTO transaksi_pettycash (kd_pettycash, id_anggaran, keterangan_pettycash, total_pettycash, id_divisi, id_manager, `from` ,status_pettycash,  created_pettycash_on, created_pettycash_by) VALUES 
                                                                          ( '$kode_otomatis', '$id_anggaran', '$keterangan_pettycash', '$total_pettycash', '$id_divisi', '$idManagerGa', 'sr','1','$tanggal','$nama' );
                ");
        // End Pettycash

        // query data buat diemail
        $queryEmail = mysqli_query($koneksi, "SELECT * FROM sr s
                                                JOIN divisi d
                                                    ON d.id_divisi = s.id_divisi
                                                WHERE s.id_sr = '$sr_id'
                        ");
        $dataEmail = mysqli_fetch_assoc($queryEmail);


        // query buat ngirim keorang email
        $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
                                                INNER JOIN divisi d
                                                    ON u.id_divisi = d.id_divisi
                                                WHERE nm_divisi = 'ga umum'
                                                AND level = 'manager_ga' ");

        // data email
        while ($dataUser = mysqli_fetch_assoc($queryUser)) {
            $link = "url=index.php?p=approval_pettycash&lvl=manager_ga";
            $name = $dataUser['nama'];
            $email = $dataUser['email'];
            $subject = "Approval Petty Cash " . $kode_otomatis;
            $body = addslashes("<font style='font-family: Courier;'>
                            Dear Bapak/Ibu <b>$name</b>,<br><br>
                            Diberitahukan bahwa divisi <b>" . $dataEmail['nm_divisi'] . "</b> telah membuat pengajuan pettycash, dengan rincian sbb:<br>
                            <table>
                                <tr>
                                    <td style='font-family: Courier;'>Kode Transaksi</td>
                                    <td style='font-family: Courier;'>: " . $kode_otomatis . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Divisi</td>
                                    <td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Keterangan</td>
                                    <td style='font-family: Courier;'>: " . $keterangan_pettycash . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Total</td>
                                    <td style='font-family: Courier;'>: " . formatRupiah2($total_pettycash) . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Tanggal Pengajuan</td>
                                    <td style='font-family: Courier;'>: " . $dataEmail['created_at'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Approve Manager</td>
                                    <td style='font-family: Courier;'>: " . $dataEmail['app_mgr'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Verifikasi Purchasing</td>
                                    <td style='font-family: Courier;'>: " . $tanggal . "</td>
                                </tr>
                            </table>
                            <br>
                            Mohon untuk melakukan <i>Approve</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                            Best Regards,<br>
                            This email auto generate by system.
                        </font>");


            $queue = createQueueEmail($name, $email, $subject, $body);
        }
    }

    if ($update && $queryIns && $queue) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'SR berhasil di Submit!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        #jika ada query yang gagal
        setcookie('pesan', 'SR gagal di Submit!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        mysqli_rollback($koneksi);

        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=" . $_POST['url'] . "");
}
