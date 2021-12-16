<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
    $id_kasbon = $_POST['id_kasbon'];
    $from_user = $_POST['from_user'];
    $vrf_pajak = $_POST['vrf_pajak'];
    $free_approve = $_POST['free_approve'];    

    // print_r('Test');
    // die;


    // str_replace(".", "", $_POST['harga']);

    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];
    $nilai_ppn = str_replace(".", "", $_POST['ppn_nilai']);
    $nilai_pph = $_POST['pph_nilai'];
    $id_pph = $_POST['id_pph'];
    $harga = str_replace(".", "", $_POST['jml_bkk']);

    // cek user
    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $nama = $rowUser['nama'];

    // cek jika kasbon dari kasir, maka proses langsung ke direksi
    $queryCekKasbon = mysqli_query($koneksi, "SELECT * FROM kasbon
                                                JOIN detail_biayaops
                                                    ON id_dbo = id
                                                WHERE id_kasbon = '$id_kasbon'");
    $dataCekKasbon = mysqli_fetch_assoc($queryCekKasbon);
    // end cek

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    // BEGIN/START TRANSACTION        
    mysqli_begin_transaction($koneksi);

    if ($from_user == '1') {
        # jika kasbon dari user

        #cek verifikasi pajak
        if ($vrf_pajak == 'bp') {

            // jika dia divisi kasir, maka status kasbonnya 4 (lngsung ke direksi)
            if ($dataCekKasbon['id_divisi'] == "11") {
                // langsung kedireksi
                $status_kasbon = "4";
                $level = "direktur";
                $nm_divisi = "bod";
                $linkUser   = "url=index.php?p=verifikasi_kasbon&sp=vk_user&lvl=direktur";
            } else {
                // kemanager finance
                $status_kasbon = "3";
                $level = "manager_keuangan";
                $nm_divisi = "finance";
                $linkUser   = "url=index.php?p=verifikasi_kasbon&sp=vk_user&lvl=manager_keuangan";
            }

            #kondisi jika verfikasi pajak sebelum pembayaran
            $query = "UPDATE kasbon SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , 
                                    nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', 
                                    id_pph = '$id_pph', harga_akhir = '$harga', status_kasbon = '$status_kasbon', app_pajak = '$tanggal'                                              
                                    WHERE id_kasbon ='$id_kasbon' ";

            $hasil = mysqli_query($koneksi, $query);

            // query data buat diemail kasbon user
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

            // query buat ngirim keorang email
            $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
                                                    INNER JOIN divisi d
                                                        ON u.id_divisi = d.id_divisi
                                                    WHERE nm_divisi = '$nm_divisi'
                                                    AND level = '$level'");

            // data email
            while ($dataUser = mysqli_fetch_assoc($queryUser)) {
                $harga = str_replace(".", "", $_POST['jml_bkk']);

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
                                <td style='font-family: Courier;'>: " . $dataEmail['app_manager'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Verifikasi Pajak</td>
                                <td style='font-family: Courier;'>: " . $tanggal . "</td>
                            </tr>
                        </table>
                        <br>
                        Mohon untuk melakukan <i>Approval</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$linkUser' target='_blank'>disini</a><br><br>
                        Best Regards,<br>
                        This email auto generate by system.
                    </font>");

                // insert queue email
                $queue = createQueueEmail($name, $email, $subject, $body);
            }
        } else {
            # kondisi jika verifikasi pajak setelah pembelian atau pembayaran
            $query = "UPDATE kasbon SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , 
                                    nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', 
                                    id_pph = '$id_pph', harga_akhir = '$harga', status_kasbon = '7', app_pajak = '$tanggal'                                              
                                    WHERE id_kasbon ='$id_kasbon' ";

            $hasil = mysqli_query($koneksi, $query);

            $queue = "berhasil";
        }
    } else {
        # Jika kasbon dari purchasing

        if ($free_approve == '1') {
            # code...
            $query = "UPDATE kasbon SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , 
                                    nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', 
                                    id_pph = '$id_pph', harga_akhir = '$harga', status_kasbon = '7', app_pajak = '$tanggal' , app_mgr_finance = '$tanggal' , app_direktur = '$tanggal' , app_direktur2 = '$tanggal'                                                                                     
                                    WHERE id_kasbon ='$id_kasbon' ";

            $hasil = mysqli_query($koneksi, $query);
        } else {
            # code...
            $query = "UPDATE kasbon SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , 
                                                nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', 
                                                id_pph = '$id_pph', harga_akhir = '$harga', status_kasbon = '5', app_pajak = '$tanggal'
                                                WHERE id_kasbon ='$id_kasbon' ";
    
            $hasil = mysqli_query($koneksi, $query);
        }
        


        // query data buat diemail dikasbon purchasing
        $queryEmail = mysqli_query($koneksi, "SELECT * FROM kasbon ks
                                                JOIN detail_biayaops dbo
                                                    ON id_dbo = dbo.id
                                                JOIN biaya_ops bo
                                                    ON bo.kd_transaksi = dbo.kd_transaksi
                                                JOIN divisi d
                                                    ON d.id_divisi = dbo.id_divisi
                                                WHERE id_kasbon = '$id_kasbon'
                                                ");
        $dataEmail = mysqli_fetch_assoc($queryEmail);

        // query buat ngirim keorang email
        $queryUser = mysqli_query($koneksi, "SELECT * FROM user 
                                                WHERE level = 'gm'");

        // data email
        while ($dataUser = mysqli_fetch_assoc($queryUser)) {
            $linkPurchasing = "url=index.php?p=verifikasi_kasbon&sp=vk_purchasing&lvl=manager_keuangan";
            $name = $dataUser['nama'];
            $email = $dataUser['email'];
            $subject = "Approval Kasbon " . $id_kasbon;
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
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_purchasing'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Verifikasi Pajak</td>
                                        <td style='font-family: Courier;'>: " . $tanggal . "</td>
                                    </tr>
                                </table>
                                <br>
                                Mohon untuk melakukan <i>Approval</i>  / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$linkPurchasing' target='_blank'>disini</a><br><br>
                                Best Regards,<br>
                                This email auto generate by system.
                            </font>");

            // insert queue email
            $queue = createQueueEmail($name, $email, $subject, $body);
        }
    }

    $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Selesai melakukan verifikasi Kasbon id: $id_kasbon');

									";
    mysqli_query($koneksi, $queryLog);

    if ($hasil && $queue) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'Kasbon berhasil di Verifikasi!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        #jika ada query yang gagal
        mysqli_rollback($koneksi);
        echo mysqli_error($koneksi);
        die;
        setcookie('pesan', 'Kasbon gagal di Verifikasi!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=verifikasi_kasbon&sp=vk_user");
}

?>
<!-- pindah -->
<!--  -->