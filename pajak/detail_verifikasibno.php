<?php

include "../fungsi/fungsi.php";
include "../fungsi/VerifikasiPajak.php";

date_default_timezone_set('Asia/Jakarta');
$tanggal = date("Y-m-d H:i:s");
// insertkan
if (isset($_POST['simpan'])) {

    // BEGIN TRAN, mysql
    // mysqli_begin_transaction($koneksi);

    $id_bkk = $_POST['id_bkk'];
    $id_pph = $_POST['id_pph'];
    $nilai_baranga = $_POST['nilai_barang'];
    $nilai_barang = str_replace(".", "", $nilai_baranga);
    $nilai_jasaa = $_POST['nilai_jasa'];
    $nilai_jasa = str_replace(".", "", $nilai_jasaa);
    $ppn_nilaia = $_POST['ppn_nilai'];

    // Opsional
    $ppn_of = $_POST['ppn_atas'];
    $rounding = $_POST['pembulatan'];

    $biaya_lain = $_POST['biaya_lain'];

    $ppn_nilai = str_replace(".", "", $ppn_nilaia);
    $pph_persent = $_POST['pph_persen'];
    $pph_nilaia = $_POST['pph_nilai'];
    $pph_nilai = str_replace(".", "", $pph_nilaia);
    $jml_bkka = $_POST['jml_bkk'];
    $jml_bkk = str_replace(".", "", $jml_bkka);
    $terbilang_bkk = Terbilang($jml_bkk);
    $potongan = penghilangTitik($_POST['potongan']);

    if ($_POST['pph_nilai2'] == 0) {
        $pph_nilai = penghilangTitik($_POST['pph_nilai']);
    } else {
        $pph_nilai = $_POST['pph_nilai2'];
    }

    if ($ppn_nilai != 0) {

        $with_ppn = 1;
    } else {

        $with_ppn = 0;
    }



    mysqli_begin_transaction($koneksi);

    $data = [
        'jenispengajuan_id' => '3',
        'permohonan_id' => $id_bkk,
        'nilai_barang' => $nilai_barang,
        'nilai_jasa' => $nilai_jasa,
        'total_harga' => $nilai_barang + $nilai_jasa,
        'nilai_dpp' => $nilai_barang,
        'ppn_nilai' => $ppn_nilai,
        'id_pph' => $id_pph,
        'pph_persen' => $pph_persent,
        'pph_nilai' => $pph_nilai,
        'biaya_lain' => $biaya_lain,
        'potongan' => $potongan,
        'grand_total' => $jml_bkk,
        'with_ppn' => $with_ppn,
        'ppn_of' => $ppn_of,
        'rounding' => $rounding,
        'created_by' => $Nama,
        'updated_by' => $Nama,
        'created_at' => dateNow(),
        'updated_at' => dateNow()
    ];



    // verifikasi pajak
    $verifikasi = verifikasi($data);

    $update = mysqli_query($koneksi, "UPDATE bkk SET nilai_barang='$nilai_barang', nilai_jasa='$nilai_jasa', ppn_nilai='$ppn_nilai', pph_persen= '$pph_persent',
                                        biaya_lain = '$biaya_lain' ,pph_nilai='$pph_nilai', id_pph='$id_pph', jml_bkk='$jml_bkk', terbilang_bkk='$terbilang_bkk', potongan = '$potongan'
    WHERE id_bkk ='$id_bkk' ");


    if ($update & $verifikasi) {
        mysqli_commit($koneksi);

        setcookie('pesan', 'Data Tersimpan', time() + (3), '/');

        header("location:index.php?p=detail_verifikasibno&id=$id_bkk");
    } else {
        mysqli_rollback($koneksi);

        echo 'error' . mysqli_error($koneksi);
    }


    $queue = "berhasil";
} else if (isset($_POST['submit'])) {


    $id_bkk = $_POST['id_bkk'];
    $id_pph = $_POST['id_pph'];
    $nilai_baranga = $_POST['nilai_barang'];
    $nilai_barang = str_replace(".", "", $nilai_baranga);
    $nilai_jasaa = $_POST['nilai_jasa'];
    $nilai_jasa = str_replace(".", "", $nilai_jasaa);
    $ppn_nilaia = $_POST['ppn_nilai'];
    $ppn_nilai = str_replace(".", "", $ppn_nilaia);
    $pph_persent = $_POST['pph_persen'];
    $pph_nilaia = $_POST['pph_nilai'];
    $pph_nilai = str_replace(".", "", $pph_nilaia);
    $jml_bkka = $_POST['jml_bkk'];
    $jml_bkk = str_replace(".", "", $jml_bkka);
    $terbilang_bkk = Terbilang($jml_bkk);
    $potongan = penghilangTitik($_POST['potongan']);



    // BEGIN TRAN, mysql
    mysqli_begin_transaction($koneksi);

    $queryUbah = mysqli_query($koneksi, "UPDATE bkk SET nilai_barang='$nilai_barang', nilai_jasa='$nilai_jasa', ppn_nilai='$ppn_nilai', pph_persen= '$pph_persent',
                                        pph_nilai='$pph_nilai', id_pph='$id_pph', jml_bkk='$jml_bkk', terbilang_bkk='$terbilang_bkk', potongan = '$potongan', status_bkk= 5
                                        WHERE id_bkk ='$id_bkk' ");

    // query data BU
    $queryEmail = mysqli_query($koneksi, "SELECT *, mgr.nama as nm_mgr, usr.nama as nm_pemohon, mgr.email as email_mgr
                                            FROM bkk bkk
                                            JOIN divisi dvs
                                                ON bkk.id_divisi = dvs.id_divisi
                                            JOIN user mgr
                                                ON bkk.id_manager = mgr.id_user
                                            JOIN user usr
                                                ON id_pemohon = usr.id_user
                                            WHERE id_bkk = '$id_bkk'");
    $dataEmail = mysqli_fetch_assoc($queryEmail);

    // query buat ngirim email
    $queryMgr = mysqli_query($koneksi, "SELECT * FROM user u
										INNER JOIN divisi d
											ON u.id_divisi = d.id_divisi
										WHERE level = 'manager_keuangan'");

    // data email
    while ($dataMgr = mysqli_fetch_assoc($queryMgr)) {
        $link = "url=index.php?p=approval_bno&lvl=manager_keuangan";
        $name = $dataMgr['nama'];
        $email = $dataMgr['email'];
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
                                <td style='font-family: Courier;'>Tanggal Pengajuan</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['tgl_pengajuan'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Approve Manager</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['tgl_verifikasimanager'] . "</td>
                            </tr>
							<tr>
								<td style='font-family: Courier;'>Verifikasi Pajak</td>
								<td style='font-family: Courier;'>: $tanggal</td>
							</tr>
                        </table>
                        <br>
                        Mohon untuk melakukan <i>Approval</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                        Best Regards,<br>
                        This email auto generate by system.
                        </font>");

        $queue = createQueueEmail($name, $email, $subject, $body);
    }


    if ($queryUbah && $queue) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'Biaya Umum berhasil di Approve!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        #jika ada query yang gagal
        mysqli_rollback($koneksi);



        setcookie('pesan', 'Biaya Umum gagal di Approve!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=verifikasi_bno");
}

$id = $_GET['id'];

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

$queryBkk = mysqli_query($koneksi, "SELECT b.*, p.*, a.*,vp.permohonan_id, vp.ppn_of, vp.rounding 
                                            FROM bkk b
                                            LEFT JOIN verifikasi_pajak vp
                                            ON vp.permohonan_id = b.id_bkk
                                            LEFT JOIN pph p
                                            ON p.id_pph = b.id_pph
                                            JOIN anggaran a
                                            ON a.id_anggaran = b.id_anggaran
                                            WHERE b.id_bkk = '$id' ");


$row2 = mysqli_fetch_assoc($queryBkk);

// print_r($row2);
// die;
// query Total_cargo
$nilai_barang = number_format($row2['nilai_barang'], 0, ",", ".");
$nilai_jasa = number_format($row2['nilai_jasa'], 0, ",", ".");
$ppn_nilai = number_format($row2['ppn_nilai'], 0, ",", ".");
$pph_nilai = number_format($row2['pph_nilai'], 0, ",", ".");
$jml_bkk = number_format($row2['jml_bkk'], 0, ",", ".");
$bll_bkk = number_format($row2['bll_bkk'], 0, ",", ".");

$budget = $row2['januari_nominal'] + $row2['februari_nominal'] + $row2['maret_nominal'] + $row2['april_nominal'] + $row2['mei_nominal'] + $row2['juni_nominal'] + $row2['juli_nominal'] + $row2['agustus_nominal'] + $row2['september_nominal'] + $row2['oktober_nominal'] + $row2['november_nominal'] + $row2['desember_nominal'];
$realisasi = $row2['januari_realisasi'] + $row2['februari_realisasi'] + $row2['maret_realisasi'] + $row2['april_realisasi'] + $row2['mei_realisasi'] + $row2['juni_realisasi'] + $row2['juli_realisasi'] + $row2['agustus_realisasi'] + $row2['september_realisasi'] + $row2['oktober_realisasi'] + $row2['november_realisasi'] + $row2['desember_realisasi'];
$saldoAnggaranb = $budget - $realisasi;
$saldoAnggaran = 'Rp. ' . number_format($saldoAnggaranb, 0, ",", ".");
$sub_total = $row2['nilai_barang'] + $row2['nilai_jasa'] + $row2['ppn_nilai'];



?>
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <?php
            if (isset($_COOKIE['pesan'])) {
                echo "<div class='alert alert-success' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <!-- <div class="col-md-2">
                            <a href="index.php?p=data_jovessel" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div> -->
                    <br><br>
                </div>

                <!-- Detail Job Order -->

                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi Biaya Non OPS</h3>
                </div>

                <form class="form-horizontal">
                    <div class="box-body">

                        <div class="form-group ">
                            <label for="id_joborder" class=" col-sm-2 control-label">Kode Transaksi</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['kd_transaksi']; ?>" disabled class="form-control" name="id_bkk">
                            </div>
                            <!-- </div>
                    <div class="form-group "> -->
                            <label id="tes" for="tgl_bkk" class=" col-sm-2 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['tgl_pengajuan']; ?>" disabled class="form-control" name="tgl_bkk">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nm_vendor" class=" col-sm-2 control-label">Nama Vendor</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['nm_vendor']; ?>" disabled class="form-control" name="nm_vendor">
                            </div>
                            <!-- </div>
                    <div class="form-group"> -->
                            <label for="kd_transaksi" class="col-sm-2 control-label">Kode Anggaran</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['kd_anggaran']; ?>" class="form-control " name="kd_transaksi" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea name="keterangan" id="" cols="20" rows="5" class="form-control " readonly><?= $row2['keterangan']; ?></textarea>
                                <!-- <input type="text" value="<?= $row2['keterangan']; ?>" class="form-control " name="keterangan" readonly> -->
                            </div>
                            <!-- </div>
                    <div class="form-group"> -->
                            <label for="terbilang_bkk" class=" col-sm-2 control-label">Terbilang</label>
                            <div class="col-sm-3">
                                <textarea name="terbilang_bkk" id="" cols="20" rows="5" class="form-control " readonly><?= $row2['terbilang_bkk'] . ' Rupiah'; ?></textarea>
                                <!-- <input type="text" value="<?= $row2['terbilang_bkk'] . ' Rupiah'; ?>" disabled class="form-control tanggal" name="terbilang_bkk"> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="jml_bkk" class="col-sm-2 control-label">Saldo Anggaran</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $saldoAnggaran; ?>" readonly class="form-control" name="jml_bkk">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-sm-offset-1 col-sm-10">
                                <div class="table-responsive">
                                    <table class="table text-right table-striped table-hover" id=" ">
                                        <thead style="background-color: royalblue;">
                                            <tr>
                                                <th class="text-center">Deskripsi</th>
                                                <th class="text-center">Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">Nilai Barang</td>
                                                <td><?= "Rp." . $nilai_barang; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Nilai Jasa</td>
                                                <td><?= "Rp." . $nilai_jasa; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Nilai PPN</td>
                                                <td><?= "Rp." . $ppn_nilai; ?></td>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Sub Total</th>
                                                <th class="text-right"><?= "Rp." .  number_format($sub_total, 0, ",", "."); ?></th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Nilai PPh</td>
                                                <td>(<?= "Rp." . $pph_nilai; ?>)</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-center">Grand Total</th>
                                                <th class="text-right"><?= "Rp." . $jml_bkk; ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Jenis</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= strtoupper($row2['jenis']) ?>" readonly class="form-control" name="nilai_bkk">
                            </div>
                        </div>
                        <?php
                        if ($row2['jenis'] == 'kontrak') { ?>
                            <div class="form-group">
                                <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Tanggal Tempo</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= formatTanggalHari($row2['tgl_tempo']);  ?>" readonly class="form-control" name="nilai_ppn">
                                </div>
                                <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Tanggal Pembayaran </label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= formatTanggalHari($row2['tgl_payment']);  ?>" readonly class="form-control" name="nilai_ppn">
                                </div>
                            </div>
                        <?php } ?>
                        <hr>
                        <div class="form-group">
                            <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Metode Pembayaran</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= strtoupper($row2['metode_pembayaran']) ?>" readonly class="form-control" name="nilai_bkk">
                            </div>
                        </div>
                        <?php
                        if ($row2['metode_pembayaran'] == 'transfer') { ?>
                            <div class="form-group">
                                <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Bank Tujuan</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= $row2['bank_tujuan'];  ?>" readonly class="form-control" name="nilai_ppn">
                                </div>
                                <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">No Rekening</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= $row2['norek_tujuan'];  ?>" readonly class="form-control" name="nilai_ppn">
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Nama Penerima</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= $row2['penerima_tujuan'];  ?>" readonly class="form-control" name="nilai_ppn">
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </form>

                <hr>

                <!-- Form Verifikasi Pajak -->
                <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="text-center">Verifikasi Pajak</h3>
                            </div>
                            <div class="perhitungan">
                                <form method="post" name="form" action="" class="form-horizontal">
                                    <input type="hidden" name="id_bkk" value="<?= $row2['id_bkk'] ?>">
                                    <!-- <input type="hidden" name="link" value="<?= $host; ?>pajak/index.php?p=detail_srk&id=<?= $_GET['id'] ?>"> -->
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label id="tes" for="nilai_barang" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Barang</label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Rp.</span>
                                                    <input type="text" required class="form-control" value="<?= $row2['nilai_barang'] ?>" name="nilai_barang" id="nilai_barang" autocomplete="off" />
                                                </div>
                                                <i><span id="nb_ui"></span></i>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="nilai_jasa" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Jasa</label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Rp.</span>
                                                    <input type="text" required class="form-control" value="<?= $row2['nilai_jasa'] ?>" name="nilai_jasa" id="nilai_jasa" autocomplete="off" />
                                                </div>
                                                <i><span id="nj_ui"></span></i>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">PPN
                                                <select name="pilih_ppn" id="setppn">
                                                    <option value="0.11">11%</option>
                                                    <option value="0.10">10%</option>
                                                    <option value="0.011">1.1%</option>
                                                </select>
                                            </label>
                                            <div class="col-sm-1">
                                                <input type="checkbox" name="all" id="myCheck" onclick="checkBox()">
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Rp.</span>
                                                    <input type="text" class="form-control " name="ppn_nilai" id="ppn_nilai" value="<?= formatRupiah2($row2['ppn_nilai']) ?>" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div id="bgn-pembulatan" class="bg-warning">
                                            <hr>
                                            <div class="form-group">
                                                <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">PPN Atas</label>
                                                <div class="col-sm-3">
                                                    <input type="radio" name="ppn_atas" value="all" id="all" onclick="checkPpnAtas()" checked=" checked"> Barang & Jasa
                                                </div>
                                                <div class=" col-sm-3">
                                                    <input type="radio" name="ppn_atas" value="barang" id="barang" onclick="checkPpnAtas()"> Hanya Barang
                                                </div>
                                                <div class=" col-sm-3">
                                                    <input type="radio" name="ppn_atas" value="jasa" id="jasa" onclick="checkPpnAtas()"> Hanya Jasa
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Pembulatan</label>
                                                <div class="col-sm-3">
                                                    <input type="radio" name="pembulatan" value="keatas" id="keatas" onclick="checkPembulatan()"> Ke atas
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="radio" name="pembulatan" value="kebawah" id="kebawah" onclick="checkPembulatan()" checked="checked"> Ke bawah
                                                </div>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="form-group">
                                            <label id="tes" for="biaya_lain" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Biaya Lain</label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Rp.</span>
                                                    <input type="text" required class="form-control" value="<?= $row2['biaya_lain'] ?>" name="biaya_lain" id="biaya_lain" autocomplete="off" />
                                                </div>
                                                <i><span id="bl_ui"></span></i></br>
                                                <i><span class="text-danger">*Biaya Materai/lain</span></i>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="id_pph" class="col-sm-offset-1 col-sm-3 control-label">Jenis PPh</label>
                                            <div class="col-sm-5">
                                                <select name="id_pph" class="form-control" id="id_pph" value="<?= $row2['id_pph'] ?>">
                                                    <option value="">--Jenis PPh--</option>
                                                    <?php
                                                    $queryPph = mysqli_query($koneksi, "SELECT * FROM pph ORDER BY nm_pph ASC");
                                                    if (mysqli_num_rows($queryPph)) {
                                                        while ($rowPph = mysqli_fetch_assoc($queryPph)) :
                                                    ?>
                                                            <option value="<?= $rowPph['id_pph']; ?>" data-id="<?= $rowPph['jenis']; ?>" type="checkbox"><?= $rowPph['nm_pph'] ?></option>
                                                    <?php endwhile;
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="fixed" class="bg-success">
                                            <hr>
                                            <div class="form-group">
                                                <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                                <div class="col-sm-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">PPh</span>
                                                        <input type="text" required class="form-control " name="pph_persen" value="0" id="pph_persen" />
                                                        <span class="input-group-addon">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                                <div class="col-sm-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Rp.</span>
                                                        <input type="text" readonly class="form-control " name="pph_nilai" value="<?= formatRupiah2($row2['pph_nilai']) ?>" id="pph_nilai" />
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                        <div id="progresive" class="bg-success">
                                            <hr>
                                            <div class="form-group">
                                                <label id="tes" for="pph_nilai2" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                                <div class="col-sm-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Rp.</span>
                                                        <input type="text" class="form-control " name="pph_nilai2" value="<?= $row2['pph_nilai'] ?>" id="pph_nilai2" />
                                                    </div>
                                                    <i><span id="pph_ui"></span></i>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="potongan" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Potongan</label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Rp.</span>
                                                    <input type="text" required class="form-control" value="<?= $row2['potongan'] ?>" name="potongan" id="potongan" autocomplete="off" />
                                                </div>
                                                <i><span id="np_ui"></span></i>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="form-group">
                                                <label id="tes" for="jml_bkk" class="col-sm-offset-1 col-sm-3 control-label">Grand Total</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Rp.</span>
                                                        <input type="text" required class="form-control" name="jml_bkk" id="jml" readonly value="<?= formatRupiah2($row2['jml_bkk']) ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <div class="form-group">
                                                <button type="submit" name="simpan" class="btn btn-primary col-sm-offset-6"> <i class="fa fa-save"></i> Simpan</button>
                                                &nbsp;
                                                <button type="submit" name="submit" class="btn btn-warning col-sm-offset-"> <i class="fa fa-rocket"></i> Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End form pajak -->
                    <div class="col-sm-6 col-xs-12">
                        <div class="box box-primary"></div>
                        <h3 class="text-center">Invoice </h3>
                        <div class="embed-responsive embed-responsive-4by3">
                            <iframe class="embed-responsive-item" src="../file/<?php echo $row2['invoice']; ?> "></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <!-- Embed Document               -->
            <!-- Document PTW -->
            <div class="box-header with-border">


                <br>
                <br>
                <div class="col-sm-offset-10 col-sm-5 control-label">
                    <h4> Verifikasi </h4>
                    <!-- <a href="setuju_bno.php?id=<?= $row2['id_bkk']; ?>"><span data-placement='top' data-toggle='tooltip' title='Kirim'><button class="btn btn-success">Approve</button></span></a> -->
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolak">Reject To User</button></span></a>
                </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
    </div>

    <div id="tolak" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Alasan Penolakan </h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="tolak_biayanonops.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">

                                <div class="col-sm-4">
                                    <input type="hidden" value="<?= $row2['id_bkk']; ?>" class="form-control" name="id_bkk" readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="validationTextarea">Komentar</label>
                                <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required>@<?php echo $Nama ?> : </textarea>
                                <div class="invalid-feedback">
                                    Please enter a message in the textarea.
                                </div>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="tolak">Kirim</button></span></a>
                                <!-- <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-1 " value="kirim" >  -->
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php

    $np = $row2['ppn_nilai'];
    $id_pph = $row2['id_pph'];

    ?>
</section>

<script>
    var host = '<?= $host ?>';

    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
    });

    // perhitungan pajak
    var np = <?= $np ?>;
    console.log(np);

    // Deklarasi
    var id_pph = '<?= $row2['id_pph']; ?>';
    var jenis = '<?= $row2['jenis']; ?>';
    var jml_bkk = '<?= $row2['jml_bkk']; ?>';
    document.form.jml.value = jml_bkk;

    let ppn_of = '<?= $row2['ppn_of']; ?>';
    let rounding = '<?= $row2['rounding']; ?>';

    var ppn_atas = $("input[name='ppn_atas']:checked").val();

    $("#" + ppn_of).attr('checked', 'checked');
    $("#" + rounding).attr('checked', 'checked');

    let persentasePpn = getPersentasePpn();

    // set ppn default 11%
    let setPpn = 0.11;
    // set default sesuai dari db
    if (persentasePpn != 0) {
        $('#setppn').val(persentasePpn);

        setPpn = persentasePpn;
    }

    // jika ada perubahan ppn
    $('#setppn').on('change', function() {
        let ppnTemp = this.value;

        if (setPpn != ppnTemp) {
            setPpn = ppnTemp;
            // cek terlebih dahulu apakah checkbox nya ini aktif
            checkBox();

        }
    });



    $("#bgn-pembulatan").hide();
    if (np > 0) {
        $('#myCheck').attr('checked', 'checked');

        $("#bgn-pembulatan").show();
    }



    // Tampilkan nilai dengan format titik di bawah
    showValueInput('nb_ui', <?= $row2['nilai_barang'] ?>);
    showValueInput('nj_ui', <?= $row2['nilai_jasa'] ?>);

    $("#id_pph").val(id_pph);

    showPph(jenis);

    /*
            nilai barang
    */

    // $("#ktk").hide();

    $('#id_pph').on('change', function() {
        let id_pph = this.value;
        jenis = $(this).find(':selected').data('id')

        showPph(jenis);

    });


    $(".perhitungan").keyup(function() {

        // Deklarasi
        var nilaiBarang = parseInt($("#nilai_barang").val())
        var nb_ui = tandaPemisahTitik(nilaiBarang);
        $('#nb_ui').text('Rp.' + nb_ui);

        var nilaiJasa = parseInt($("#nilai_jasa").val())
        var nj_ui = tandaPemisahTitik(nilaiJasa);
        $('#nj_ui').text('Rp.' + nj_ui);

        var pph_persen = parseFloat($("#pph_persen").val())
        var pph_nilai = Math.floor(nilaiJasa * pph_persen / 100);

        var pph_nilaia = tandaPemisahTitik(pph_nilai);
        $("#pph").attr("value", pph_nilaia);
        document.form.pph_nilai.value = pph_nilaia;

        // Biaya lain
        var biayaLain = parseInt($("#biaya_lain").val())
        var bl_ui = tandaPemisahTitik(biayaLain);
        $('#bl_ui').text('Rp.' + bl_ui);

        // nilai pph untuk pajak progresive
        var pph_nilai2 = parseInt($("#pph_nilai2").val())
        var pph_ui = tandaPemisahTitik(pph_nilai2);
        $('#pph_ui').text('Rp.' + pph_ui);

        // nilai potongan
        var potongan = parseInt($("#potongan").val())
        var np_ui = tandaPemisahTitik(potongan);
        $('#np_ui').text('Rp.' + np_ui);

        hitungTotal();

    });



    function showPph(data) {

        var nilai_jasa = hilangkanTitik('nilai_jasa')
        // var jml = hilangkanTitik('jml')
        var pph_nilai = hilangkanTitik('pph_nilai')

        // pph nilai 2 untuk tarif progresive
        var pph_nilai2 = hilangkanTitik('pph_nilai2')


        if (data == 'fixed') {
            $("#fixed").show();
            $("#progresive").hide();

            document.form.pph_nilai2.value = 0;

            if (pph_nilai > 0) {
                var persen = Math.round((pph_nilai / nilai_jasa) * 100);

                document.form.pph_persen.value = persen;
            }

        } else if (data == 'progresive') {
            $("#fixed").hide();
            $("#progresive").show();

            document.form.pph_persen.value = 0;
            document.form.pph_nilai.value = 0;
        } else {
            $("#fixed").hide();
            $("#progresive").hide();

            document.form.pph_persen.value = 0;
            document.form.pph_nilai.value = 0;
            document.form.pph_nilai2.value = 0;
        }

        hitungTotal();
    }

    // check box ppn
    function checkBox() {
        var checkBox = document.getElementById("myCheck");

        if (checkBox.checked == true) {

            $("#bgn-pembulatan").show();

            var ppn_nilai = Math.floor(setPpn * (getDpp()));


        } else if (checkBox.checked == false) {

            $("#bgn-pembulatan").hide();


            var ppn_nilai = 0;
        }

        // set nilai ppn
        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;

        hitungTotal();

    }

    // check ppn atas
    function checkPpnAtas() {
        // ambil cek ppn atas
        ppn_atas = $("input[name='ppn_atas']:checked").val();

        var ppn_nilai = Math.floor(setPpn * (getDpp()));

        // set nilai ppn
        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;

        hitungTotal();


    }

    function getDpp() {
        // var nilaiDpp = 0;

        if (ppn_atas == 'all') {
            var nilaiDpp = getNilaiBarang() + getNilaiJasa();
        } else if (ppn_atas == 'barang') {
            var nilaiDpp = getNilaiBarang();
        } else if (ppn_atas == 'jasa') {
            var nilaiDpp = getNilaiJasa();
        }

        return nilaiDpp;
    }

    function getPersentasePpn() {

        // let dpp = parseInt($("#nilai_barang").val()) + parseInt($("#nilai_jasa").val());
        let percent = np / getDpp();
        let percentOke = percent.toFixed(2);

        return parseFloat(percent.toFixed(2));
    }

    // check pembulatan
    function checkPembulatan() {

        var pembulatan = $("input[name='pembulatan']:checked").val();


        if (pembulatan == 'keatas') {

            // pembulatan ke atas
            var ppn_nilai = Math.ceil(setPpn * (getDpp()));

        } else if (pembulatan == 'kebawah') {

            // pembulatan ke bawah
            var ppn_nilai = Math.floor(setPpn * (getDpp()));
        }

        // Set Nilai PPN
        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;

        hitungTotal();

    }

    // hitung total
    function hitungTotal() {
        var grandTotal = getNilaiBarang() + getNilaiJasa() + getPpnNilai() + getBiayaLain() - getPphNilai() - getPotongan();

        var jml = tandaPemisahTitik(grandTotal);
        document.form.jml.value = jml;

        return grandTotal;
    }

    function showValueInput(idSpan, angka) {

        return $('#' + idSpan).text('Rp.' + tandaPemisahTitik(angka));
    }

    function getNilaiBarang() {
        return hilangkanTitik('nilai_barang');
    }

    function getNilaiJasa() {
        return hilangkanTitik('nilai_jasa');
    }

    function getPpnNilai() {
        return hilangkanTitik('ppn_nilai');
    }

    function getPpnAtas() {
        return ppn_atas = $("input[name='ppn_atas']:checked").val();
    }

    function getBiayaLain() {
        return hilangkanTitik('biaya_lain');
    }

    function getPotongan() {
        return hilangkanTitik('potongan');
    }

    function getPphNilai() {

        if (jenis == 'fixed') {

            // pph nilai 1 untuk tarif fix
            var pph_nilai = hilangkanTitik('pph_nilai')

        } else if (jenis == 'progresive') {

            // pph nilai 2 untuk tarif progresive
            var pph_nilai = hilangkanTitik('pph_nilai2')

        } else {
            var pph_nilai = 0;
        }

        return pph_nilai;


    }




    function hilangkanTitik(idTag) {
        var angka = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById(idTag).value))))); //input ke dalam angka tanpa titik

        return angka;
    }
</script>