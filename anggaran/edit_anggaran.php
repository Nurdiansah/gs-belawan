<?php
// session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
    $id = dekripRambo($_GET['id']);
} else {
    header('Location: index.php?p=anggaran');
}

$queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_anggaran = '$id'");
$dataAnggaran = mysqli_fetch_assoc($queryAnggaran);
$idPK = $dataAnggaran['programkerja_id'];
$idSub = $dataAnggaran['subheader_id'];
$idDivisi = $dataAnggaran['id_divisi'];
$tahun = $dataAnggaran['tahun'];

$queryUser =  mysqli_query($koneksi, "SELECT area, nama from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];
$nama = $rowUser['nama'];

date_default_timezone_set('Asia/Jakarta');
$waktuSekarang = date('d-m-Y H:i:s');
$tahunAyeuna = date("Y");

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $id_divisi = $_POST['id_divisi'];
    $program_kerja  = $_POST['program_kerja'];
    $sub_header = $_POST['sub_header'];
    $tahun = $_POST['tahun'];
    $segmen = $_POST['segmen'];
    $no_coa = $_POST['no_coa'];
    $nm_coa = $_POST['nm_coa'];
    $tipe_anggaran = $_POST['tipe_anggaran'];
    $jenis_anggaran = $_POST['jenis_anggaran'];
    $deskripsi = $_POST['deskripsi'];
    $kd_anggaran = $_POST['kd_anggaran'];
    $perdin = $_POST['perdin'] == "1" ? $_POST['perdin'] : "0";
    $unlock = $_POST['unlock'] == "1" ? $_POST['unlock'] : "0";
    $nominal_januari = str_replace(".", "", $_POST['nominal_januari']);
    $nominal_februari = str_replace(".", "", $_POST['nominal_februari']);
    $nominal_maret = str_replace(".", "", $_POST['nominal_maret']);
    $nominal_april = str_replace(".", "", $_POST['nominal_april']);
    $nominal_mei = str_replace(".", "", $_POST['nominal_mei']);
    $nominal_juni = str_replace(".", "", $_POST['nominal_juni']);
    $nominal_juli = str_replace(".", "", $_POST['nominal_juli']);
    $nominal_agustus = str_replace(".", "", $_POST['nominal_agustus']);
    $nominal_september = str_replace(".", "", $_POST['nominal_september']);
    $nominal_oktober = str_replace(".", "", $_POST['nominal_oktober']);
    $nominal_november = str_replace(".", "", $_POST['nominal_november']);
    $nominal_desember = str_replace(".", "", $_POST['nominal_desember']);
    $nominal_jumlah = str_replace(".", "", $_POST['nominal_jumlah']);

    $update = mysqli_query($koneksi, "UPDATE anggaran SET id_divisi = '$id_divisi',
                                            programkerja_id = '$program_kerja',
                                            subheader_id = '$sub_header',
                                            tahun = '$tahun',
                                            id_segmen = '$segmen',
                                            no_coa = '$no_coa',
                                            nm_coa = '$nm_coa',
                                            tipe_anggaran = '$tipe_anggaran',
                                            jenis_anggaran = '$jenis_anggaran',
                                            nm_item = '$deskripsi',
                                            kd_anggaran = '$kd_anggaran',
                                            spj = '$perdin',
                                            `unlock` = '$unlock',
                                            januari_nominal = '$nominal_januari',
                                            februari_nominal = '$nominal_februari',
                                            maret_nominal = '$nominal_maret',
                                            april_nominal = '$nominal_april',
                                            mei_nominal = '$nominal_mei',
                                            juni_nominal = '$nominal_juni',
                                            juli_nominal = '$nominal_juli',
                                            agustus_nominal = '$nominal_agustus',
                                            september_nominal = '$nominal_september',
                                            oktober_nominal = '$nominal_oktober',
                                            november_nominal = '$nominal_november',
                                            desember_nominal = '$nominal_desember',
                                            jumlah_nominal = '$nominal_jumlah',
                                            last_modified_by = '$nama',
                                            last_modified_on = NOW() 
                                        WHERE id_anggaran = '$id'
                        ");

    if ($update) {
        header('Location: index.php?p=edit_anggaran&id=' . enkripRambo($id) . '');
    } else {
        echo mysqli_error($koneksi);
        die;
    }
}

// ngambil data header dari sub header
$dataSHeader = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM sub_header WHERE id_subheader = '$idSub'"));
$dataHdr = $dataSHeader['id_header'];

?>

<section class="content">
    <div class="row">
        <form method="post" name="form" action="" enctype="multipart/form-data" class="form-horizontal">
            <div class="col-sm-6 col-xs-12">
                <div class="box box-primary">
                    <div class="row">
                        <div class="col-md-2">
                            <a href="index.php?p=anggaran&tahun=<?= enkripRambo($tahun); ?>&divisi=<?= enkripRambo($idDivisi); ?>" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                        </div>
                    </div>
                    <div class="box-header with-border">
                        <h3 class="text-center">Edit Anggaran</h3>
                    </div>
                    <input type="hidden" name="id" value="<?= $id; ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset-1 col-sm-3 control-label">Divisi</label>
                            <div class="col-sm-5">
                                <select name="id_divisi" id="id_divisi" class="form-control id_divisi" required>
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
                                    <select name="program_kerja" id="id_programkerja" class="form-control" required>
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
                                            <option value="<?= $dataPK['id_programkerja']; ?>" <?= $dataPK['id_programkerja'] == $idPK ? "selected" : ""; ?>><?= $dataPK['nm_programkerja']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="tahun" class="col-sm-offset-1 col-sm-3 control-label">Anggaran Tahun</label>
                            <div class="col-sm-5">
                                <select name="tahun" class="form-control" required>
                                    <?php foreach (range(2021, $tahunAyeuna + 1) as $tahunLoop) { ?>
                                        <option value="<?= $tahunLoop; ?>" <?= $tahunLoop == $dataAnggaran['tahun'] ? "selected=selected" : ''; ?>><?= $tahunLoop; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>
                        <div class="form-group">
                            <label id="tes" for="tahun" class="col-sm-offset-1 col-sm-3 control-label">Segmen/Job Code</label>
                            <div class="col-sm-5">
                                <select name="segmen" class="form-control">
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
                                <select name="id_header" id="id_header" class="form-control header_id" required>
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
                                <select name="sub_header" id="sub_header" class="form-control">
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
                                <input type="text" class="form-control" name="no_coa" value="<?= $dataAnggaran['no_coa']; ?>" id="no_coa">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nm_coa" class="col-sm-offset-1 col-sm-3 control-label">Nama Coa</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="nm_coa" value="<?= $dataAnggaran['nm_coa']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="id_golongan" class="col-sm-offset-1 col-sm-3 control-label">Tipe Anggaran</label>
                            <div class="col-sm-5">
                                <select name="tipe_anggaran" class="form-control">
                                    <option value="OPEX" <?= $dataAnggaran['tipe_anggaran'] == "OPEX" ? 'selected' : ''; ?>>OPEX</option>
                                    <option value="CAPEX" <?= $dataAnggaran['tipe_anggaran'] == "CAPEX" ? 'selected' : ''; ?>>CAPEX</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="id_subgolongan" class=" col-sm-offset-1 col-sm-3 control-label">Jenis Anggaran</label>
                            <div class="col-sm-5">
                                <select name="jenis_anggaran" class="form-control">
                                    <option value="BIAYA" <?= $dataAnggaran['jenis_anggaran'] == "BIAYA" ? 'selected' : ''; ?>>BIAYA</option>
                                    <option value="PENDAPATAN" <?= $dataAnggaran['jenis_anggaran'] == "PENDAPATAN" ? 'selected' : ''; ?>>PENDAPATAN</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="perhitungan"> -->
                        <div class="form-group">
                            <label id="tes" for="deskripsi" class="col-sm-offset-1 col-sm-3 control-label">Deskripsi</label>
                            <div class="col-sm-5">
                                <!-- <input type="text" required class="form-control" name="deskripsi" value="<?= $dataAnggaran['nm_item']; ?>"> -->
                                <textarea name="deskripsi" id="deskripsi" rows="2" class="form-control"><?= $dataAnggaran['nm_item']; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="kd_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="kd_anggaran" value="<?= $dataAnggaran['kd_anggaran']; ?>" id="kd_anggaran">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="kd_anggaran" class="col-sm-offset-1 col-sm-3 control-label"></label>
                            <div class="col-sm-5">
                                <input type="checkbox" name="perdin" id="perdin" value="1" <?= $dataAnggaran['spj'] == "1" ? "checked" : ""; ?>>&nbsp;<label for="perdin">SPJ/Perjalanan Dinas</label>
                            </div>

                            <label id="tes" for="kd_anggaran" class="col-sm-offset-1 col-sm-3 control-label"></label>
                            <div class="col-sm-5">
                                <input type="checkbox" name="unlock" id="unlock" value="1" <?= $dataAnggaran['unlock'] == "1" ? "checked" : ""; ?>>&nbsp;<label for="unlock">Unlock Anggaran</label>
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
                        <h3 class="text-center">Nominal</h3>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="nominal_januari" class="col-sm-offset- col-sm-4 control-label">Januari </label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" class="form-control" value="<?= formatRupiah2($dataAnggaran['januari_nominal']); ?>" name="nominal_januari" id="nominal_januari" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" readonly />
                                </div>
                                <input type="checkbox" name="all" id="myCheck" onclick="checkBox()" disabled><label for="myCheck">&nbsp;&nbsp;Semua Bulan</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_februari" class="col-sm-offset- col-sm-4 control-label">Februari</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['februari_nominal']); ?>" name="nominal_februari" id="nominal_februari" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_maret" class="col-sm-offset- col-sm-4 control-label">Maret</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['maret_nominal']); ?>" name="nominal_maret" id="nominal_maret" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_april" class="col-sm-offset- col-sm-4 control-label">April </label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['april_nominal']); ?>" name="nominal_april" id="nominal_april" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_mei" class="col-sm-offset- col-sm-4 control-label">Mei</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['mei_nominal']); ?>" name="nominal_mei" id="nominal_mei" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_juni" class="col-sm-offset- col-sm-4 control-label">Juni</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['juni_nominal']); ?>" name="nominal_juni" id="nominal_juni" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_juli" class="col-sm-offset- col-sm-4 control-label">Juli</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['juli_nominal']); ?>" name="nominal_juli" id="nominal_juli" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_agustus" class="col-sm-offset- col-sm-4 control-label">Agustus </label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['agustus_nominal']); ?>" name="nominal_agustus" id="nominal_agustus" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_september" class="col-sm-offset- col-sm-4 control-label">September</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['september_nominal']); ?>" name="nominal_september" id="nominal_september" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_oktober" class="col-sm-offset- col-sm-4 control-label">Oktober</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['oktober_nominal']); ?>" name="nominal_oktober" id="nominal_oktober" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_november" class="col-sm-offset- col-sm-4 control-label">November</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['november_nominal']); ?>" name="nominal_november" id="nominal_november" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_desember" class="col-sm-offset- col-sm-4 control-label">Desember </label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['desember_nominal']); ?>" name="nominal_desember" id="nominal_desember" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" readonly />
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-auto">
                            <div class="form-group">
                                <label id="tes" for="jml_bkk" class="col-sm-offset- col-sm-4 control-label">Jumlah Nominal </label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" value="<?= formatRupiah2($dataAnggaran['jumlah_nominal']); ?>" name="nominal_jumlah" id="nominal_jumlah" readonly />
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

    function jumlah_nominal() {
        //  Math.round(document.getElementById('nominal_januari').value);
        var nominal_januari = getNumber('nominal_januari');
        var nominal_februari = getNumber('nominal_februari');
        var nominal_maret = getNumber('nominal_maret');
        var nominal_april = getNumber('nominal_april');
        var nominal_mei = getNumber('nominal_mei');
        var nominal_juni = getNumber('nominal_juni');
        var nominal_juli = getNumber('nominal_juli');
        var nominal_agustus = getNumber('nominal_agustus');
        var nominal_september = getNumber('nominal_september');
        var nominal_oktober = getNumber('nominal_oktober');
        var nominal_november = getNumber('nominal_november');
        var nominal_desember = getNumber('nominal_desember');
        var nominal_hasil = parseInt(nominal_januari) + parseInt(nominal_februari) + parseInt(nominal_maret) + parseInt(nominal_april) + parseInt(nominal_mei) + parseInt(nominal_juni) + parseInt(nominal_juli) + parseInt(nominal_agustus) + parseInt(nominal_september) + parseInt(nominal_oktober) + parseInt(nominal_november) + parseInt(nominal_desember);

        // console.log(nominal_hasil);
        if (!isNaN(nominal_hasil)) {
            document.getElementById('nominal_jumlah').value = tandaPemisahTitik(nominal_hasil);
        }
    }

    function checkBox() {
        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {

            var nominal_januari = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_januari').value)))));
            var jumlah = nominal_januari * 12;

            document.form.nominal_februari.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_maret.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_april.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_mei.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_juni.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_juli.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_agustus.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_september.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_oktober.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_november.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_desember.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_jumlah.value = tandaPemisahTitik(jumlah);

        } else {
            document.form.nominal_februari.value = tandaPemisahTitik(<?= $dataAnggaran['februari_nominal']; ?>);
            document.form.nominal_maret.value = tandaPemisahTitik(<?= $dataAnggaran['maret_nominal']; ?>);
            document.form.nominal_april.value = tandaPemisahTitik(<?= $dataAnggaran['april_nominal']; ?>);;
            document.form.nominal_mei.value = tandaPemisahTitik(<?= $dataAnggaran['mei_nominal']; ?>);;
            document.form.nominal_juni.value = tandaPemisahTitik(<?= $dataAnggaran['juni_nominal']; ?>);;
            document.form.nominal_juli.value = tandaPemisahTitik(<?= $dataAnggaran['juli_nominal']; ?>);;
            document.form.nominal_agustus.value = tandaPemisahTitik(<?= $dataAnggaran['agustus_nominal']; ?>);;
            document.form.nominal_september.value = tandaPemisahTitik(<?= $dataAnggaran['september_nominal']; ?>);;
            document.form.nominal_oktober.value = tandaPemisahTitik(<?= $dataAnggaran['oktober_nominal']; ?>);;
            document.form.nominal_november.value = tandaPemisahTitik(<?= $dataAnggaran['november_nominal']; ?>);;
            document.form.nominal_desember.value = tandaPemisahTitik(<?= $dataAnggaran['desember_nominal']; ?>);;
            document.form.nominal_jumlah.value = tandaPemisahTitik(<?= $dataAnggaran['jumlah_nominal']; ?>);;
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

    // $(".perhitungan").keyup(function() {

    //     //ambil inputan harga
    //     // var harga = parseInt($("#harga_nominal").val())

    //     var harga = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('harga_nominal').value))))); //input ke dalam angka tanpa titik
    //     var nominal_januari = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_januari').value))))); //input ke dalam angka tanpa titik
    //     var nominal_februari = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_februari').value))))); //input ke dalam angka tanpa titik
    //     var nominal_maret = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_maret').value))))); //input ke dalam angka tanpa titik
    //     var nominal_april = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_april').value))))); //input ke dalam angka tanpa titik
    //     var nominal_mei = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_mei').value))))); //input ke dalam angka tanpa titik
    //     var nominal_juni = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_juni').value))))); //input ke dalam angka tanpa titik
    //     var nominal_juli = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_juli').value))))); //input ke dalam angka tanpa titik
    //     var nominal_agustus = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_agustus').value))))); //input ke dalam angka tanpa titik
    //     var nominal_september = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_september').value))))); //input ke dalam angka tanpa titik
    //     var nominal_oktober = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_oktober').value))))); //input ke dalam angka tanpa titik
    //     var nominal_november = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_november').value))))); //input ke dalam angka tanpa titik
    //     var nominal_desember = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_desember').value))))); //input ke dalam angka tanpa titik

    //     var jk = parseInt($("#januari_kuantitas").val())
    //     var fk = parseInt($("#februari_kuantitas").val())
    //     var mk = parseInt($("#maret_kuantitas").val())
    //     var apk = parseInt($("#april_kuantitas").val())
    //     var mek = parseInt($("#mei_kuantitas").val())
    //     var junk = parseInt($("#juni_kuantitas").val())
    //     var julk = parseInt($("#juli_kuantitas").val())
    //     var agk = parseInt($("#agustus_kuantitas").val())
    //     var sepk = parseInt($("#september_kuantitas").val())
    //     var oktk = parseInt($("#oktober_kuantitas").val())
    //     var novk = parseInt($("#november_kuantitas").val())
    //     var desk = parseInt($("#desember_kuantitas").val())

    //     // jumlah nominal
    //     var jmlKuantitas = jk + fk + mk + apk + mek + junk + julk + agk + sepk + oktk + novk + desk;
    //     $("#jml_kuantitas").attr("value", jmlKuantitas);
    //     document.form.jml_kuantitas.value = jmlKuantitas;

    //     // jumlah nominal
    //     var jml_nominal = nominal_januari + nominal_februari + nominal_maret + nominal_april + nominal_mei + nominal_juni + nominal_juli + nominal_agustus + nominal_september + nominal_oktober + nominal_november + nominal_desember;
    //     var jml_nominala = tandaPemisahTitik(jml_nominal);
    //     document.form.jml_nominal.value = jml_nominala;

    // });


    // onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" 

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