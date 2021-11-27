<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
    $id_po = $_POST['id_po'];
    $diskon_po = $_POST['diskon_po'];
    $nilai_pembulatan = $_POST['nilai_pembulatan'];
    $total_po = str_replace(".", "", $_POST['total_po']);
    $nilai_ppn = str_replace(".", "", $_POST['nilai_ppn']);
    $grand_totalpo = str_replace(".", "", $_POST['grand_totalpo']);
    $note_po = $_POST['note_po'];

    $lokasi_doc_quotation = ($_FILES['doc_quotation']['tmp_name']);
    $doc_quotation = ($_FILES['doc_quotation']['name']);

    $ekstensi = pathinfo($doc_quotation, PATHINFO_EXTENSION);
    // 
    $nama_doc = $id_po . "-doc-quotation." . $ekstensi;
    move_uploaded_file($lokasi_doc_quotation, "../file/doc_quotation/" . $nama_doc);

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    mysqli_begin_transaction($koneksi);

    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $id_user = $rowUser['id_user'];
    $nama = $rowUser['nama'];

    $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Mensubmit Quotation id : $id_po');

									";
    mysqli_query($koneksi, $queryLog);

    $query = "UPDATE po SET doc_quotation = '$nama_doc' , status_po = '2', diskon_po = '$diskon_po', total_po = '$total_po', nilai_ppn = '$nilai_ppn', 
							nilai_pembulatan = '$nilai_pembulatan' ,grand_totalpo = '$grand_totalpo', note_po = '$note_po'
                            WHERE id_po ='$id_po' ";

    $hasil = mysqli_query($koneksi, $query);


    // query data buat diemail
    $queryEmail = mysqli_query($koneksi, "SELECT * FROM po po
                                        JOIN detail_biayaops dbo
                                            ON po.kd_transaksi = dbo.kd_transaksi
                                        JOIN divisi d
                                            ON d.id_divisi = dbo.id_divisi
                                        JOIN biaya_ops bo
                                            ON dbo.kd_transaksi = bo.kd_transaksi
                                        WHERE id_po = '$id_po'
                                                ");
    $dataEmail = mysqli_fetch_assoc($queryEmail);

    // query buat ngirim keorang email
    $queryUser = mysqli_query($koneksi, "SELECT * FROM user 
                                                WHERE level = 'manager_keuangan'");

    // data email
    while ($dataUser = mysqli_fetch_assoc($queryUser)) {
        $link = "url=index.php?p=verifikasi_po&lvl=manager_ga";
        $name = $dataUser['nama'];
        $email = $dataUser['email'];
        $subject = "Approval PO [" . $dataEmail['po_number'] . "]";
        $body = addslashes("<font style='font-family: Courier;'>
                                Dear Bapak/Ibu <b>$name</b>,<br><br>
                                Diberitahukan bahwa divisi <b>" . $dataEmail['nm_divisi'] . "</b> telah membuat pengajuan PO, dengan rincian sbb:<br>
                                <table>
                                    <tr>
                                        <td style='font-family: Courier;'>No PO</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['po_number'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Divisi</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Nama Barang</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['nm_barang'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Keterangan</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['keterangan'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Total</td>
                                        <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['grand_totalpo']) . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Tanggal Pengajuan</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['tgl_po'] . "</td>
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
                                Mohon untuk melakukan <i>Approval</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                                Best Regards,<br>
                                This email auto generate by system.
                            </font>");

        $queue = createQueueEmail($name, $email, $subject, $body);
    }




    if ($queue && $hasil) {
        // mysql commit transaction
        mysqli_commit($koneksi);

        setcookie('pesan', 'PO berhasil di Verifikasi!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        // mysql rollback transaction
        mysqli_rollback($koneksi);

        setcookie('pesan', 'PO gagal di Verifikasi!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=submit_po");
}

?>
<!-- pindah -->
<!--  -->