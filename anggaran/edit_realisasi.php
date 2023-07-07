<?php
// session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
    $id = dekripRambo($_GET['id']);
} else {
    header('Location: index.php?p=anggaran&sp=realisasi');
}

$queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_anggaran = '$id'");
$dataAnggaran = mysqli_fetch_assoc($queryAnggaran);
$idPK = $dataAnggaran['programkerja_id'];
$idSub = $dataAnggaran['subheader_id'];
$idDivisi = $dataAnggaran['id_divisi'];
$tahun = $dataAnggaran['tahun'];

$queryUser =  mysqli_query($koneksi, "SELECT area, nama from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['id_area'];
$nama = $rowUser['nama'];

date_default_timezone_set('Asia/Jakarta');
$waktuSekarang = date('d-m-Y H:i:s');
$tahunAyeuna = date("Y");

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $realisasi_januari = str_replace(".", "", $_POST['realisasi_januari']);
    $realisasi_februari = str_replace(".", "", $_POST['realisasi_februari']);
    $realisasi_maret = str_replace(".", "", $_POST['realisasi_maret']);
    $realisasi_april = str_replace(".", "", $_POST['realisasi_april']);
    $realisasi_mei = str_replace(".", "", $_POST['realisasi_mei']);
    $realisasi_juni = str_replace(".", "", $_POST['realisasi_juni']);
    $realisasi_juli = str_replace(".", "", $_POST['realisasi_juli']);
    $realisasi_agustus = str_replace(".", "", $_POST['realisasi_agustus']);
    $realisasi_september = str_replace(".", "", $_POST['realisasi_september']);
    $realisasi_oktober = str_replace(".", "", $_POST['realisasi_oktober']);
    $realisasi_november = str_replace(".", "", $_POST['realisasi_november']);
    $realisasi_desember = str_replace(".", "", $_POST['realisasi_desember']);
    $realisasi_jumlah = str_replace(".", "", $_POST['realisasi_jumlah']);

    // ngambil data anggaran sebelum dirubah
    $dataAgg = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_anggaran = '$id'"));

    $keterangan = "Januari Realisasi   : " . $dataAgg['januari_realisasi'] . " menjadi $realisasi_januari
Februari Realisasi  : " . $dataAgg['februari_realisasi'] . " menjadi $realisasi_februari
Maret Realisasi     : " . $dataAgg['maret_realisasi'] . " menjadi $realisasi_maret
April Realisasi     : " . $dataAgg['april_realisasi'] . " menjadi $realisasi_april
Mei Realisasi       : " . $dataAgg['mei_realisasi'] . " menjadi $realisasi_mei
Juni Realisasi      : " . $dataAgg['juni_realisasi'] . " menjadi $realisasi_juni
Juli Realisasi      : " . $dataAgg['juli_realisasi'] . " menjadi $realisasi_juli
Agustus Realisasi   : " . $dataAgg['agustus_realisasi'] . " menjadi $realisasi_agustus
September Realisasi : " . $dataAgg['september_realisasi'] . " menjadi $realisasi_september
Oktober Realisasi   : " . $dataAgg['oktober_realisasi'] . " menjadi $realisasi_oktober
November Realisasi  : " . $dataAgg['november_realisasi'] . " menjadi $realisasi_november
Desember Realisasi  : " . $dataAgg['desember_realisasi'] . " menjadi $realisasi_desember
Jumlah Realisasi    : " . $dataAgg['jumlah_realisasi'] . " menjadi $realisasi_jumlah";

    $updateLog = mysqli_query($koneksi, "INSERT INTO log_anggaran (id_anggaran, aksi, keterangan, dirubah_oleh, waktu_dirubah) VALUES
                                                                    ('$id', 'EDIT REALISASI', '$keterangan', '$nama', NOW())
                        ");

    $update = mysqli_query($koneksi, "UPDATE anggaran
                                        SET januari_realisasi = '$realisasi_januari',
                                            februari_realisasi = '$realisasi_februari',
                                            maret_realisasi = '$realisasi_maret',
                                            april_realisasi = '$realisasi_april',
                                            mei_realisasi = '$realisasi_mei',
                                            juni_realisasi = '$realisasi_juni',
                                            juli_realisasi = '$realisasi_juli',
                                            agustus_realisasi = '$realisasi_agustus',
                                            september_realisasi = '$realisasi_september',
                                            oktober_realisasi = '$realisasi_oktober',
                                            november_realisasi = '$realisasi_november',
                                            desember_realisasi = '$realisasi_desember',
                                            jumlah_realisasi = '$realisasi_jumlah',
                                            last_modified_by = '$nama',
                                            last_modified_on = NOW() 
                                        WHERE id_anggaran = '$id'
                        ");

    if ($update) {
        header('Location: index.php?p=edit_realisasi&id=' . enkripRambo($id) . '');
    } else {
        echo mysqli_error($koneksi);
        die;
    }
}

// ngambil data header dari sub header
$dataSHeader = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM sub_header WHERE id_subheader = '$idSub'"));
$dataHdr = $dataSHeader['id_header'];


// cek klo dia anggaran amortisasi, maka bisa edit realisasi nya
$cekAmorPK = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_programkerja) AS jumlah FROM program_kerja
                                                        WHERE id_programkerja = '$idPK'
                                                        AND (nm_programkerja LIKE '%amortisasi%' OR nm_programkerja LIKE '%Penyusutan%')
                                                "));

$cekAmorAgg = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_anggaran) AS jumlah
                                                            FROM anggaran
                                                            WHERE id_anggaran = '$id'
                                                            AND (nm_coa LIKE '%amortisasi%' OR nm_coa LIKE '%penyusutan%' OR nm_item LIKE '%amortisasi%' OR nm_item LIKE '%penyusutan%')
                                                "));

// menggunakan fungsi preg_match, dan huruf i disampingnya supaya insensitive (gabaca huruf besar atau kecil)
// $cekAmorCOA = preg_match("/amortisasi/i", $dataAnggaran['nm_coa']);
// $cekAmorAgg = preg_match("/amortisasi/i", $dataAnggaran['nm_item']);
$totalCekAmor = $cekAmorPK['jumlah'] + $cekAmorAgg['jumlah']; // + $cekAmorCOA + $cekAmorAgg;

$readonly = $totalCekAmor > 0 ?  "" : "readonly";

?>

<section class="content">
    <div class="row">
        <form method="post" name="form" action="" enctype="multipart/form-data" class="form-horizontal">
            <div class="col-sm-6 col-xs-12">
                <div class="box box-primary">
                    <div class="row">
                        <div class="col-md-2">
                            <a href="index.php?p=anggaran&sp=realisasi&tahun=<?= enkripRambo($tahun); ?>&divisi=<?= enkripRambo($idDivisi); ?>" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                        </div>
                    </div>
                    <div class="box-header with-border">
                        <h3 class="text-center">Anggaran</h3>
                    </div>
                    <input type="hidden" name="id" value="<?= $id; ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset-1 col-sm-3 control-label">Divisi</label>
                            <div class="col-sm-5">
                                <select name="id_divisi" id="id_divisi" class="form-control id_divisi" required disabled>
                                    <option value="">-- Pilih Divisi --</option>
                                    <?php
                                    $queryDivisi = mysqli_query($koneksi, "SELECT * FROM divisi ORDER BY nm_divisi ASC");
                                    if (mysqli_num_rows($queryDivisi)) {
                                        while ($rowDivisi = mysqli_fetch_assoc($queryDivisi)) :
                                    ?>
                                            <option value="<?= $rowDivisi['id_divisi']; ?>" <?= $rowDivisi['id_divisi'] == $dataAnggaran['id_divisi'] ? 'selected=selected' : ''; ?>><?= $rowDivisi['nm_divisi']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="ktkPK">
                            <div class="form-group">
                                <label id="tes" for="divisi" class="col-sm-offset-1 col-sm-3 control-label">Program Kerja</label>
                                <div class="col-sm-5">
                                    <select name="program_kerja" id="id_programkerja" class="form-control" required disabled>
                                        <?php $queryPK = mysqli_query($koneksi, "SELECT *
                                                                                    FROM cost_center
                                                                                    JOIN pt
                                                                                        ON id_pt = pt_id
                                                                                    JOIN divisi
                                                                                        ON id_divisi = divisi_id
                                                                                    JOIN parent_divisi
                                                                                        ON id_parent = parent_id
                                                                                    JOIN program_kerja
                                                                                        ON id_costcenter = costcenter_id
                                                                                    WHERE id_divisi = '$idDivisi'
                                                                                    AND tahun = '$tahunAyeuna'
                                                                                    ORDER BY nm_programkerja ASC");

                                        while ($dataPK = mysqli_fetch_assoc($queryPK)) { ?>
                                            <option value="<?= $dataPK['id_programkerja']; ?>" <?= $dataPK['id_programkerja'] == $idPK ? "selected" : ""; ?>><?= $dataPK['kd_programkerja'] . " [" . $dataPK['nm_programkerja']; ?>]</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="tahun" class="col-sm-offset-1 col-sm-3 control-label">Anggaran Tahun</label>
                            <div class="col-sm-5">
                                <select name="tahun" class="form-control" required disabled>
                                    <?php foreach (range(2021, $tahunAyeuna + 1) as $tahunLoop) { ?>
                                        <option value="<?= $tahunLoop; ?>" <?= $tahunLoop == $dataAnggaran['tahun'] ? "selected=selected" : ''; ?>><?= $tahunLoop; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>
                        <div class="form-group">
                            <label id="tes" for="tahun" class="col-sm-offset-1 col-sm-3 control-label">Segmen/Job Code</label>
                            <div class="col-sm-5">
                                <select name="segmen" class="form-control" disabled>
                                    <?php $querySegmen = mysqli_query($koneksi, "SELECT * FROM segmen ORDER BY nm_segmen ASC");
                                    while ($dataSegmen = mysqli_fetch_assoc($querySegmen)) {
                                    ?>
                                        <option value="<?= $dataSegmen['id_segmen']; ?>" <?= $dataAnggaran['id_segmen'] == $dataSegmen['id_segmen'] ? 'selected' : ''; ?>><?= $dataSegmen['nm_segmen']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="header" class="col-sm-offset-1 col-sm-3 control-label">Header</label>
                            <div class="col-sm-5">
                                <select name="id_header" id="id_header" class="form-control header_id" required disabled>
                                    <?php
                                    $queryHeader = mysqli_query($koneksi, "SELECT * FROM header ORDER BY nm_header ASC");
                                    while ($dataHeader = mysqli_fetch_assoc($queryHeader)) {
                                    ?>
                                        <option value="<?= $dataHeader['id_header']; ?>" <?= $dataHeader['id_header'] == $dataHdr ? "selected" : ""; ?>><?= $dataHeader['nm_header']; ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="sub_header" class="col-sm-offset-1 col-sm-3 control-label">Sub Header</label>
                            <div class="col-sm-5">
                                <select name="sub_header" id="sub_header" class="form-control" disabled>
                                    <?php $querySub = mysqli_query($koneksi, "SELECT * FROM sub_header WHERE id_header = '$dataHdr' ORDER BY nm_subheader ASC");
                                    while ($dataSub = mysqli_fetch_assoc($querySub)) { ?>
                                        <option value="<?= $dataSub['id_subheader']; ?>" <?= $dataSub['id_subheader'] == $idSub ? "selected" : ""; ?>><?= $dataSub['nm_subheader']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="waktu" value="<?php echo $waktuSekarang; ?>">
                        <div class="form-group">
                            <label id="tes" for="no_coa" class="col-sm-offset-1 col-sm-3 control-label">Nomor Coa</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="no_coa" value="<?= $dataAnggaran['no_coa']; ?>" id="no_coa" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nm_coa" class="col-sm-offset-1 col-sm-3 control-label">Nama Coa</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="nm_coa" value="<?= $dataAnggaran['nm_coa']; ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="id_golongan" class="col-sm-offset-1 col-sm-3 control-label">Tipe Anggaran</label>
                            <div class="col-sm-5">
                                <select name="tipe_anggaran" class="form-control" disabled>
                                    <option value="OPEX" <?= $dataAnggaran['tipe_anggaran'] == "OPEX" ? 'selected' : ''; ?>>OPEX</option>
                                    <option value="CAPEX" <?= $dataAnggaran['tipe_anggaran'] == "CAPEX" ? 'selected' : ''; ?>>CAPEX</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="id_subgolongan" class=" col-sm-offset-1 col-sm-3 control-label">Jenis Anggaran</label>
                            <div class="col-sm-5">
                                <select name="jenis_anggaran" class="form-control" disabled>
                                    <option value="BIAYA" <?= $dataAnggaran['jenis_anggaran'] == "BIAYA" ? 'selected' : ''; ?>>BIAYA</option>
                                    <option value="PENDAPATAN" <?= $dataAnggaran['jenis_anggaran'] == "PENDAPATAN" ? 'selected' : ''; ?>>PENDAPATAN</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="perhitungan"> -->
                        <div class="form-group">
                            <label id="tes" for="deskripsi" class="col-sm-offset-1 col-sm-3 control-label">Deskripsi Anggaran</label>
                            <div class="col-sm-5">
                                <input type="text" required class="form-control" name="deskripsi" value="<?= $dataAnggaran['nm_item']; ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="kd_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="kd_anggaran" value="<?= $dataAnggaran['kd_anggaran']; ?>" id="kd_anggaran" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="kd_anggaran" class="col-sm-offset-1 col-sm-3 control-label"></label>
                            <div class="col-sm-5">
                                <input type="checkbox" name="perdin" id="perdin" value="1" <?= $dataAnggaran['spj'] == "1" ? "checked" : ""; ?> disabled>&nbsp;<label for="perdin">SPJ/Perjalanan Dinas</label>
                            </div>

                            <label id="tes" for="kd_anggaran" class="col-sm-offset-1 col-sm-3 control-label"></label>
                            <div class="col-sm-5">
                                <input type="checkbox" name="unlock" id="unlock" value="1" <?= $dataAnggaran['unlock'] == "1" ? "checked" : ""; ?> disabled>&nbsp;<label for="unlock">Unlock Anggaran</label>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label id="tes" for="kd_anggaran" class="col-sm-offset-1 col-sm- control-label"><i>(Dibuat oleh : <?= $dataAnggaran['created_by'] . " " . $dataAnggaran['created_on'] ?>, Dirubah oleh : <?= $dataAnggaran['last_modified_by'] . " " . $dataAnggaran['last_modified_on'] ?>)</i></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="text-center">Realisasi</h3>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="realisasi_januari" class="col-sm-offset- col-sm-4 control-label">Januari </label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" class="form-control" value="<?= formatRupiah2($dataAnggaran['januari_realisasi']); ?>" name="realisasi_januari" id="realisasi_januari" <?= $readonly == "readonly" ? "" : "onkeydown='return numbersonly(this, event);'"; ?> <?= $readonly; ?> onkeyup="jumlah_realisasi();" />
                                </div>
                                <!-- <input type="checkbox" name="all" id="myCheck" onclick="checkBox()"><label for="myCheck">&nbsp;&nbsp;Semua Bulan</label> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="realisasi_februari" class="col-sm-offset- col-sm-4 control-label">Februari</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['februari_realisasi']); ?>" name="realisasi_februari" id="realisasi_februari" <?= $readonly == "readonly" ? "" : "onkeydown='return numbersonly(this, event);'"; ?> <?= $readonly; ?> onkeyup="jumlah_realisasi();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="realisasi_maret" class="col-sm-offset- col-sm-4 control-label">Maret</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['maret_realisasi']); ?>" name="realisasi_maret" id="realisasi_maret" <?= $readonly == "readonly" ? "" : "onkeydown='return numbersonly(this, event);'"; ?> <?= $readonly; ?> onkeyup="jumlah_realisasi();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="realisasi_april" class="col-sm-offset- col-sm-4 control-label">April </label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['april_realisasi']); ?>" name="realisasi_april" id="realisasi_april" <?= $readonly == "readonly" ? "" : "onkeydown='return numbersonly(this, event);'"; ?> <?= $readonly; ?> onkeyup="jumlah_realisasi();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="realisasi_mei" class="col-sm-offset- col-sm-4 control-label">Mei</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['mei_realisasi']); ?>" name="realisasi_mei" id="realisasi_mei" <?= $readonly == "readonly" ? "" : "onkeydown='return numbersonly(this, event);'"; ?> <?= $readonly; ?> onkeyup="jumlah_realisasi();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="realisasi_juni" class="col-sm-offset- col-sm-4 control-label">Juni</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['juni_realisasi']); ?>" name="realisasi_juni" id="realisasi_juni" <?= $readonly == "readonly" ? "" : "onkeydown='return numbersonly(this, event);'"; ?> <?= $readonly; ?> onkeyup="jumlah_realisasi();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="realisasi_juli" class="col-sm-offset- col-sm-4 control-label">Juli</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['juli_realisasi']); ?>" name="realisasi_juli" id="realisasi_juli" <?= $readonly == "readonly" ? "" : "onkeydown='return numbersonly(this, event);'"; ?> <?= $readonly; ?> onkeyup="jumlah_realisasi();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="realisasi_agustus" class="col-sm-offset- col-sm-4 control-label">Agustus </label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['agustus_realisasi']); ?>" name="realisasi_agustus" id="realisasi_agustus" <?= $readonly == "readonly" ? "" : "onkeydown='return numbersonly(this, event);'"; ?> <?= $readonly; ?> onkeyup="jumlah_realisasi();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="realisasi_september" class="col-sm-offset- col-sm-4 control-label">September</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['september_realisasi']); ?>" name="realisasi_september" id="realisasi_september" <?= $readonly == "readonly" ? "" : "onkeydown='return numbersonly(this, event);'"; ?> <?= $readonly; ?> onkeyup="jumlah_realisasi();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="realisasi_oktober" class="col-sm-offset- col-sm-4 control-label">Oktober</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['oktober_realisasi']); ?>" name="realisasi_oktober" id="realisasi_oktober" <?= $readonly == "readonly" ? "" : "onkeydown='return numbersonly(this, event);'"; ?> <?= $readonly; ?> onkeyup="jumlah_realisasi();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="realisasi_november" class="col-sm-offset- col-sm-4 control-label">November</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['november_realisasi']); ?>" name="realisasi_november" id="realisasi_november" <?= $readonly == "readonly" ? "" : "onkeydown='return numbersonly(this, event);'"; ?> <?= $readonly; ?> onkeyup="jumlah_realisasi();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="realisasi_desember" class="col-sm-offset- col-sm-4 control-label">Desember </label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['desember_realisasi']); ?>" name="realisasi_desember" id="realisasi_desember" <?= $readonly == "readonly" ? "" : "onkeydown='return numbersonly(this, event);'"; ?> <?= $readonly; ?> onkeyup="jumlah_realisasi();" />
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-auto">
                            <div class="form-group">
                                <label id="tes" for="jml_bkk" class="col-sm-offset- col-sm-4 control-label">Jumlah Realisasi </label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['jumlah_realisasi']); ?>" name="realisasi_jumlah" id="realisasi_jumlah" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-5" value="Simpan">
                            &nbsp;
                            <input type="reset" class="btn btn-danger" value="Batal">
                        </div>
                        <!-- </div> -->

                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    var host = '<?= host() ?>';

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

    function getNumber(data) {
        return eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById(data).value)))));
    }

    function jumlah_realisasi() {
        //  Math.round(document.getElementById('realisasi_januari').value);
        var realisasi_januari = getNumber('realisasi_januari');
        var realisasi_februari = getNumber('realisasi_februari');
        var realisasi_maret = getNumber('realisasi_maret');
        var realisasi_april = getNumber('realisasi_april');
        var realisasi_mei = getNumber('realisasi_mei');
        var realisasi_juni = getNumber('realisasi_juni');
        var realisasi_juli = getNumber('realisasi_juli');
        var realisasi_agustus = getNumber('realisasi_agustus');
        var realisasi_september = getNumber('realisasi_september');
        var realisasi_oktober = getNumber('realisasi_oktober');
        var realisasi_november = getNumber('realisasi_november');
        var realisasi_desember = getNumber('realisasi_desember');
        var realisasi_hasil = parseInt(realisasi_januari) + parseInt(realisasi_februari) + parseInt(realisasi_maret) + parseInt(realisasi_april) + parseInt(realisasi_mei) + parseInt(realisasi_juni) + parseInt(realisasi_juli) + parseInt(realisasi_agustus) + parseInt(realisasi_september) + parseInt(realisasi_oktober) + parseInt(realisasi_november) + parseInt(realisasi_desember);

        // console.log(realisasi_hasil);
        if (!isNaN(realisasi_hasil)) {
            document.getElementById('realisasi_jumlah').value = tandaPemisahTitik(realisasi_hasil);
        }
    }

    function checkBox() {
        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {

            var realisasi_januari = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('realisasi_januari').value)))));
            var jumlah = realisasi_januari * 12;

            document.form.realisasi_februari.value = tandaPemisahTitik(realisasi_januari);
            document.form.realisasi_maret.value = tandaPemisahTitik(realisasi_januari);
            document.form.realisasi_april.value = tandaPemisahTitik(realisasi_januari);
            document.form.realisasi_mei.value = tandaPemisahTitik(realisasi_januari);
            document.form.realisasi_juni.value = tandaPemisahTitik(realisasi_januari);
            document.form.realisasi_juli.value = tandaPemisahTitik(realisasi_januari);
            document.form.realisasi_agustus.value = tandaPemisahTitik(realisasi_januari);
            document.form.realisasi_september.value = tandaPemisahTitik(realisasi_januari);
            document.form.realisasi_oktober.value = tandaPemisahTitik(realisasi_januari);
            document.form.realisasi_november.value = tandaPemisahTitik(realisasi_januari);
            document.form.realisasi_desember.value = tandaPemisahTitik(realisasi_januari);
            document.form.realisasi_jumlah.value = tandaPemisahTitik(jumlah);

        } else {
            document.form.realisasi_februari.value = tandaPemisahTitik(<?= $dataAnggaran['februari_realisasi']; ?>);
            document.form.realisasi_maret.value = tandaPemisahTitik(<?= $dataAnggaran['maret_realisasi']; ?>);
            document.form.realisasi_april.value = tandaPemisahTitik(<?= $dataAnggaran['april_realisasi']; ?>);;
            document.form.realisasi_mei.value = tandaPemisahTitik(<?= $dataAnggaran['mei_realisasi']; ?>);;
            document.form.realisasi_juni.value = tandaPemisahTitik(<?= $dataAnggaran['juni_realisasi']; ?>);;
            document.form.realisasi_juli.value = tandaPemisahTitik(<?= $dataAnggaran['juli_realisasi']; ?>);;
            document.form.realisasi_agustus.value = tandaPemisahTitik(<?= $dataAnggaran['agustus_realisasi']; ?>);;
            document.form.realisasi_september.value = tandaPemisahTitik(<?= $dataAnggaran['september_realisasi']; ?>);;
            document.form.realisasi_oktober.value = tandaPemisahTitik(<?= $dataAnggaran['oktober_realisasi']; ?>);;
            document.form.realisasi_november.value = tandaPemisahTitik(<?= $dataAnggaran['november_realisasi']; ?>);;
            document.form.realisasi_desember.value = tandaPemisahTitik(<?= $dataAnggaran['desember_realisasi']; ?>);;
            document.form.realisasi_jumlah.value = tandaPemisahTitik(<?= $dataAnggaran['jumlah_realisasi']; ?>);;
            // text.style.display = "none";
        }
    }

    // nomor coa dengan kd anggaran sama sekarnag
    const no_coa = document.getElementById('no_coa');
    const kd_anggaran = document.getElementById('kd_anggaran');

    no_coa.addEventListener('keyup', function() {
        document.form.kd_anggaran.value = no_coa.value;
    });


    $('.header_id').on('change', function() {
        let headerId = this.value;

        // console.log(headerId);
        $.ajax({
            url: host + 'api/anggaran/getSubHeader.php',
            data: {
                id: headerId
            },
            method: 'post',
            dataType: 'json',
            success: function(data) {
                // console.log(data);

                $('#sub_header').empty()
                $.each(data, function(i, value) {
                    $('#sub_header').append($('<option>').text(value.nm_subheader).attr('value', value.id_subheader));
                });
            }
        });
        // }
    });
    $('.divisi').on('change', function() {
        let divisi = this.value;
    });

    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
                    element.attr("name", $(this).attr("name"));
                    element.change(function() {
                        element.next(element).find('input').val((element.val()).split('\\').pop());
                    });
                    $(this).find("button.btn-choose").click(function() {
                        element.click();
                    });
                    $(this).find("button.btn-reset").click(function() {
                        element.val(null);
                        $(this).parents(".input-file").find('input').val('');
                    });
                    $(this).find('input').css("cursor", "pointer");
                    $(this).find('input').mousedown(function() {
                        $(this).parents('.input-file').prev().click();
                        return false;
                    });
                    return element;
                }
            }
        );
    }
    $(function() {
        bs_input_file();
    });
</script>