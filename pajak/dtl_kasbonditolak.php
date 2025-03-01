<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_POST['simpan']) || isset($_POST['submit'])) {

    // Deklarasi
    $id_kasbon = $_POST['id_kasbon'];
    $from_user = $_POST['from_user'];
    $vrf_pajak = $_POST['vrf_pajak'];
    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];

    $nilai_ppn = penghilangTitik($_POST['nilai_ppn']);

    $id_pph = $_POST['id_pph'];

    if ($_POST['nilai_pph2'] == 0) {
        $nilai_pph = penghilangTitik($_POST['nilai_pph']);
    } else {
        $nilai_pph = $_POST['nilai_pph2'];
    }


    $biaya_lain = $_POST['biaya_lain'];
    $potongan = $_POST['potongan'];
    $pembulatan = $_POST['pembulatan'];

    $harga = penghilangTitik($_POST['harga_akhir']);

    $tanggal = dateNow();

    // Simpan data
    if (isset($_POST['simpan'])) {
        // Simpan

        // BEGIN/START TRANSACTION        
        mysqli_begin_transaction($koneksi);

        $update = mysqli_query($koneksi, "UPDATE kasbon SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , 
                nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', 
                id_pph = '$id_pph', biaya_lain = '$biaya_lain', potongan = '$potongan', 
                harga_akhir = '$harga', app_pajak = '$tanggal'                                              
                WHERE id_kasbon ='$id_kasbon' ");

        if ($update) {
            # jika semua query berhasil di jalankan
            mysqli_commit($koneksi);

            setcookie('pesan', 'Kasbon berhasil di Simpan!', time() + (3), '/');
            setcookie('warna', 'alert-success', time() + (3), '/');
        } else {
            #jika ada query yang gagal
            mysqli_rollback($koneksi);
            echo mysqli_error($koneksi);
            die;
            setcookie('pesan', 'Kasbon gagal di Simpan!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
            setcookie('warna', 'alert-danger', time() + (3), '/');
        }
        header("location:index.php?p=ditolak_kasbon&sp=tolak_user");
    }

    // Submit atau release
    if (isset($_POST['submit'])) {
        // Submit atau release       
        // cek user
        $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
        $rowUser = mysqli_fetch_assoc($queryUser);
        $nama = $rowUser['nama'];

        // cek jika kasbon dari kasir, maka proses langsung ke direksi
        $queryCekKasbon = mysqli_query($koneksi, "SELECT * FROM kasbon
                                                    JOIN detail_biayaops
                                                        ON id_dbo = id
                                                    WHERE id_kasbon = '$id_kasbon'");
        $dataCekKasbon = mysqli_fetch_assoc($queryCekKasbon);
        // end cek        

        // BEGIN/START TRANSACTION        
        mysqli_begin_transaction($koneksi);

        // kemanager finance
        $status_kasbon = "5";


        #kondisi jika verfikasi pajak sebelum pembayaran            

        $query = "UPDATE kasbon SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , 
                                        nilai_ppn = '$nilai_ppn', nilai_pph = '$nilai_pph', 
                                        id_pph = '$id_pph', biaya_lain = '$biaya_lain', potongan = '$potongan', 
                                        harga_akhir = '$harga', app_pajak = '$tanggal', status_kasbon = '$status_kasbon',
                                        komentar_mgr_finjkt = NULL, komentar_direktur = NULL  
                                        WHERE id_kasbon ='$id_kasbon' ";

        $hasil = mysqli_query($koneksi, $query);


        if ($hasil) {
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
        header("location:index.php?p=ditolak_kasbon&sp=tolak_user");
    }
}

// Akhir Simpan Dan Submit

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = $_GET['id'];


$queryDetail =  mysqli_query($koneksi, "SELECT * FROM kasbon k
                                                         JOIN detail_biayaops db 
                                                         ON k.id_dbo = db.id
                                                         LEFT JOIN pph p
                                                         ON p.id_pph =  k.id_pph
                                                         JOIN divisi d
                                                         ON d.id_divisi = db.id_divisi
                                                         JOIN anggaran a
                                                         ON db.id_anggaran = a.id_anggaran 
                                                         JOIN supplier s
                                                         ON s.id_supplier = db.id_supplier
                                                         WHERE k.id_kasbon = '$id' ");
$data = mysqli_fetch_assoc($queryDetail);
$id_supplier = $data['id_supplier'];
$Divisi = $data['id_divisi'];
$id_dbo = $data['id'];

$queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$Divisi' AND id_anggaran !='$data[id_anggaran]' ORDER BY nm_item ASC");

$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo ");

$tanggalCargo = date("Y-m-d");

$queryReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_kasbon WHERE kasbon_id = '$id'");
$dataReapp = mysqli_fetch_assoc($queryReapp);
$totalReapp = mysqli_num_rows($queryReapp);
?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="col-md-2">
                    <a href="index.php?p=ditolak_kasbon&sp=tolak_user" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                </div>
                <br><br>
                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi Pajak</h3>
                </div>

                <form action="" class="form-horizontal">

                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="tanggal" class="col-sm-offset col-sm-2 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tanggal" value="<?= formatTanggal($data['tgl_kasbon']); ?>">
                            </div>
                            <!-- </div>
                            <div class="form-group"> -->
                            <label for="satuan" class="col-sm-offset- col-sm-2 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control " name="satuan" value="<?= $data['nm_divisi']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="nominal" for="nominal" class="col-sm-offset col-sm-2 control-label">Nominal</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="nominal" value="<?= formatRupiah($data['harga_akhir']); ?>">
                            </div>
                            <!-- </div>
                            <div class="form-group"> -->
                            <label for="id_anggaran" class="col-sm-offset- col-sm-2 control-label">Kode Anggaran</label>
                            <div class="col-sm-3">
                                <select class="form-control select2" name="id_anggaran" disabled>
                                    <option value="<?= $data['id_anggaran']; ?>"><?= $data['kd_anggaran'] . ' ' . $data['nm_item']; ?></option>
                                    <?php
                                    if (mysqli_num_rows($queryAnggaran)) {
                                        while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                    ?>
                                            <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['kd_anggaran'] . ' ' . $rowAnggaran['nm_item']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>

                        </div>
                        <div class="form-group">
                            <input type="hidden" required class="form-control is-valid" name="id_kasbon" value="<?= $data['id_kasbon']; ?>">
                            <input type="hidden" required class="form-control is-valid" name="id" value="<?= $data['id']; ?>">
                            <input type="hidden" required class="form-control is-valid" name="from_user" value="<?= $data['from_user']; ?>">
                            <input type="hidden" required class="form-control is-valid" name="vrf_pajak" value="<?= $data['vrf_pajak']; ?>">

                            <label for="keterangan" class="col-sm-offset- col-sm-2 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data['keterangan']; ?></textarea>
                            </div>

                            <label for="alasan_reapprove" class="col-sm-offset- col-sm-2 control-label">Alasan Ditolak</label>
                            <div class="col-sm-">
                                <div class="col-sm-3">
                                    <textarea rows="5" type="text" name="alasan_reapprove" disabled class="form-control "> <?= $data['komentar_mgr_finjkt']; ?>&#13;&#10;<?= $data['komentar_direktur']; ?></textarea>
                                </div>
                            </div>

                            <!-- </div>
                            <div class="form-group"> -->


                            <!-- <label for="waktu_reapprove" class="col-sm-offset- col-sm-2 control-label">Waktu Reapprove</label>
                                <div class="col-sm-3">
                                    <textarea rows="5" type="text" name="waktu_reapprove" disabled class="form-control "> <?= $dataReapp['waktu_reapprove_mgr']; ?></textarea>
                                </div> -->
                        </div>
                    </div>
                    <!-- </div> -->
                </form>

            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <!-- Form -->
        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <!-- Verifikasi Tax  -->
                <div class="box-header with-border">
                    <h3 class="text-center">Form Verifikasi </h3>
                </div>
                <form method="post" name="form" action="" enctype="multipart/form-data" class="form-horizontal">
                    <!-- Kotak  perhitungan  -->
                    <div class="perhitungan">
                        <form method="post" name="form" action="" class="form-horizontal">
                            <input type="hidden" name="id_kasbon" value="<?= $data['id_kasbon'] ?>">
                            <input type="hidden" name="from_user" value="<?= $data['from_user'] ?>">
                            <input type="hidden" name="vrf_pajak" value="<?= $data['vrf_pajak'] ?>">
                            <div class="box-body">
                                <div class="form-group">
                                    <label id="tes" for="nilai_barang" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Barang</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" required class="form-control" value="<?= round($data['nilai_barang']) ?>" name="nilai_barang" id="nilai_barang" autocomplete="off" />
                                        </div>
                                        <i><span id="nb_ui"></span></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="nilai_jasa" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Jasa</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" required class="form-control" value="<?= round($data['nilai_jasa']) ?>" name="nilai_jasa" id="nilai_jasa" autocomplete="off" />
                                        </div>
                                        <i><span id="nj_ui"></span></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">PPN</label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="all" id="myCheck" onclick="checkBox()">
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" class="form-control " name="nilai_ppn" id="nilai_ppn" value="<?= formatRupiah2(round($data['nilai_ppn'])) ?>" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div id="bgn-pembulatan">
                                    <div class="form-group">
                                        <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Pembulatan</label>
                                        <div class="col-sm-3">
                                            <input type="radio" name="pembulatan" value="keatas" id="pembulatan" onclick="checkPembulatan()"> Ke atas
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="radio" name="pembulatan" value="kebawah" id="pembulatan" onclick="checkPembulatan()" checked="checked"> Ke bawah
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="biaya_lain" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Biaya Lain</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" required class="form-control" value="<?= $data['biaya_lain'] ?>" name="biaya_lain" id="biaya_lain" autocomplete="off" />
                                        </div>
                                        <i><span id="bl_ui"></span></i></br>
                                        <i><span class="text-danger">*Biaya Materai/lain</span></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="id_pph" class="col-sm-offset-1 col-sm-3 control-label">Jenis PPh</label>
                                    <div class="col-sm-5">
                                        <select name="id_pph" class="form-control" id="id_pph" value="<?= $data['id_pph'] ?>">
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
                                <div id="fixed">
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
                                        <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai PPh</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" readonly class="form-control " name="nilai_pph" value="<?= formatRupiah2($data['nilai_pph']) ?>" id="nilai_pph" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="progresive">
                                    <div class="form-group">
                                        <label id="tes" for="nilai_pph2" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai PPh</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" class="form-control " name="nilai_pph2" value="<?= $data['nilai_pph'] ?>" id="nilai_pph2" />
                                            </div>
                                            <i><span id="pph_ui"></span></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="potongan" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Potongan</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" required class="form-control" value="<?= $data['potongan'] ?>" name="potongan" id="potongan" autocomplete="off" />
                                        </div>
                                        <i><span id="np_ui"></span></i>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label id="tes" for="harga_akhir" class="col-sm-offset-1 col-sm-3 control-label">Grand Total</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" required class="form-control" name="harga_akhir" id="jml" readonly value="<?= formatRupiah2($data['harga_akhir']) ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <div class="form-group">
                                        <button type="submit" name="simpan" class="btn btn-primary col-sm-offset-5"> <i class="fa fa-save"></i> Simpan</button>
                                        &nbsp;
                                        <button type="submit" name="submit" class="btn btn-warning col-sm-offset-"> <i class="fa fa-rocket"></i> Submit</button>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolak"> <i class="fa fa-reply"></i> Reject </button></span></a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </form>

            </div>
        </div>

        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <?php if (!empty($data['doc_pendukung'])) { ?>
                    <div class="form-group ">
                        <div class="box-header with-border">
                            <h3 class="text-center">Document Pendukung </h3>
                            <div class="embed-responsive embed-responsive-4by3">
                                <iframe class="embed-responsive-item" src="../file/doc_pendukung/<?= $data['doc_pendukung']; ?>" id="ml_doc"></iframe>
                            </div>
                        </div>
                    </div>

                <?php } ?>

                <?php if ($data['vrf_pajak'] == 'as') { ?>
                    <?php if (!empty($data['doc_lpj'])) {; ?>
                        <h3 class="text-center">Document LPJ</h3>
                        <div class="embed-responsive embed-responsive-4by3">
                            <object data="../file/doc_lpj/<?php echo $data['doc_lpj']; ?> " width="800" height="500"></object>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>

    </div>
</section>

<!-- modal tolak -->
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
                <form method="POST" enctype="multipart/form-data" action="tolaktax_kasbon_user.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">

                            <div class="col-sm-4">
                                <input type="hidden" value="<?= $data['id_kasbon']; ?>" class="form-control" name="id_kasbon">
                                <input type="hidden" value="ditolak_kasbon&sp=tolak_user" class="form-control" name="url">
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
<!-- end modal tolak -->

<script>
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


    // perhitungan pajak
    var np = <?= $data['nilai_ppn'] ?>;

    if (np > 0) {
        $('#myCheck').attr('checked', 'checked');
    }

    // Cek PPH
    var id_pph = '<?= $data['id_pph']; ?>';
    var jenis = '<?= $data['jenis']; ?>';



    var harga_akhir = '<?= $data['harga_akhir']; ?>';
    document.form.jml.value = harga_akhir;

    $("#id_pph").val(id_pph);

    showPph(jenis);

    $("#bgn-pembulatan").hide();
    // $("#ktk").hide();

    $('#id_pph').on('change', function() {
        let id_pph = this.value;
        let jenis = $(this).find(':selected').data('id')

        showPph(jenis);

    });

    $(".perhitungan").keyup(function() {

        var nilaiBarang = parseInt($("#nilai_barang").val())
        var nb_ui = tandaPemisahTitik(nilaiBarang);
        $('#nb_ui').text('Rp.' + nb_ui);

        var nilaiJasa = parseInt($("#nilai_jasa").val())
        var nj_ui = tandaPemisahTitik(nilaiJasa);
        $('#nj_ui').text('Rp.' + nj_ui);

        var pph_persen = parseFloat($("#pph_persen").val())
        var nilai_pph = Math.floor(nilaiJasa * pph_persen / 100);

        // console.log(nilai_pph);

        var nilai_ppha = tandaPemisahTitik(nilai_pph);
        $("#pph").attr("value", nilai_ppha);
        document.form.nilai_pph.value = nilai_ppha;

        // Biaya lain
        var biayaLain = parseInt($("#biaya_lain").val())
        var bl_ui = tandaPemisahTitik(biayaLain);
        $('#bl_ui').text('Rp.' + bl_ui);

        // nilai pph untuk pajak progresive
        var nilai_pph2 = parseInt($("#nilai_pph2").val())
        var pph_ui = tandaPemisahTitik(nilai_pph2);
        $('#pph_ui').text('Rp.' + pph_ui);

        // nilai potongan
        var potongan = parseInt($("#potongan").val())
        var np_ui = tandaPemisahTitik(potongan);
        $('#np_ui').text('Rp.' + np_ui);

        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {
            var nilai_ppn = Math.floor(0.11 * (nilaiBarang + nilaiJasa));
        } else if (checkBox.checked == false) {
            var nilai_ppn = 0;
        }

        var nilai_ppna = tandaPemisahTitik(nilai_ppn);
        document.form.nilai_ppn.value = nilai_ppna;

        var jmla = nilaiBarang + nilaiJasa + nilai_ppn + biayaLain - nilai_pph - nilai_pph2 - potongan;
        var jml = tandaPemisahTitik(jmla);
        $("#jml").attr("value", jml);

        document.form.jml.value = jml;


    });

    function hilangkanTitik(data) {
        var angka = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById(data).value))))); //input ke dalam angka tanpa titik

        return angka;
    }

    function showPph(data) {

        var nilai_barang = hilangkanTitik('nilai_barang')
        var nilai_jasa = hilangkanTitik('nilai_jasa')
        var nilai_ppn = hilangkanTitik('nilai_ppn')
        var biaya_lain = hilangkanTitik('biaya_lain')
        var potongan = hilangkanTitik('potongan')

        // var jml = hilangkanTitik('jml')
        var nilai_pph = hilangkanTitik('nilai_pph')

        // pph nilai 2 untuk tarif progresive
        var nilai_pph2 = hilangkanTitik('nilai_pph2')

        console.log(nilai_ppn);


        if (data == 'fixed') {
            $("#fixed").show();
            $("#progresive").hide();


            var jml = (nilai_barang + nilai_jasa + nilai_ppn + biaya_lain) - nilai_pph - potongan;
            jml = tandaPemisahTitik(jml);

            console.log(jml)

            document.form.nilai_pph2.value = 0;
            document.form.jml.value = jml;

            if (nilai_pph > 0) {
                var persen = (nilai_pph / nilai_jasa) * 100;

                document.form.pph_persen.value = persen;
            }

        } else if (data == 'progresive') {
            $("#fixed").hide();
            $("#progresive").show();

            var jml = (nilai_barang + nilai_jasa + nilai_ppn + biaya_lain) - nilai_pph2 - potongan;
            jml = tandaPemisahTitik(jml);

            document.form.pph_persen.value = 0;
            document.form.nilai_pph.value = 0;
            document.form.jml.value = jml;
        } else {
            $("#fixed").hide();
            $("#progresive").hide();


            var jml = (nilai_barang + nilai_jasa + nilai_ppn + biaya_lain) - potongan;
            jml = tandaPemisahTitik(jml);


            document.form.pph_persen.value = 0;
            document.form.nilai_pph.value = 0;
            document.form.nilai_pph2.value = 0;
            document.form.jml.value = jml;
        }

    }

    function checkBox() {
        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {

            $("#bgn-pembulatan").show();

            // $("#pembulatan").val('kebawah');

            var nilaiJasa = parseInt($("#nilai_jasa").val())
            var pph_persen = parseInt($("#pph_persen").val())
            var nilai_pph = Math.floor(nilaiJasa * pph_persen / 100);
            var nilai_ppha = tandaPemisahTitik(nilai_pph);
            $("#pph").attr("value", nilai_ppha);
            document.form.nilai_pph.value = nilai_ppha;


            var nilaiBarang = parseInt($("#nilai_barang").val())
            var biayaLain = parseInt($("#biaya_lain").val())
            var potongan = parseInt($("#potongan").val())

            var nilai_ppn = Math.floor(0.11 * (nilaiBarang + nilaiJasa));
            var nilai_ppna = tandaPemisahTitik(nilai_ppn);
            $("#ppn").attr("value", nilai_ppna);
            document.form.nilai_ppn.value = nilai_ppna;

            var nilai_pph2 = parseInt($("#nilai_pph2").val())

            var jmla = nilaiBarang + nilaiJasa + nilai_ppn + biayaLain - nilai_pph - nilai_pph2 - potongan;
            var jml = tandaPemisahTitik(jmla);
            $("#jml").attr("value", jml);
            document.form.jml.value = jml;

        } else if (checkBox.checked == false) {

            $("#bgn-pembulatan").hide();

            var nilaiJasa = parseInt($("#nilai_jasa").val())
            var pph_persen = parseInt($("#pph_persen").val())
            var nilai_pph = Math.floor(nilaiJasa * pph_persen / 100);
            var nilai_ppha = tandaPemisahTitik(nilai_pph);
            $("#pph").attr("value", nilai_ppha);
            document.form.nilai_pph.value = nilai_ppha;


            var nilaiBarang = parseInt($("#nilai_barang").val())
            var biayaLain = parseInt($("#biaya_lain").val())
            var potongan = parseInt($("#potongan").val())

            var nilai_ppn = 0;
            var nilai_ppna = tandaPemisahTitik(nilai_ppn);
            $("#ppn").attr("value", nilai_ppna);
            document.form.nilai_ppn.value = nilai_ppna;

            var nilai_pph2 = parseInt($("#nilai_pph2").val())

            var jmla = nilaiBarang + nilaiJasa + nilai_ppn + biayaLain - nilai_pph - nilai_pph2 - potongan;
            var jml = tandaPemisahTitik(jmla);
            $("#jml").attr("value", jml);
            document.form.jml.value = jml;
        }
    }

    // check pembulatan
    function checkPembulatan() {

        var pembulatan = $("input[name='pembulatan']:checked").val();

        var nilaiJasa = parseInt($("#nilai_jasa").val())
        var nilaiBarang = parseInt($("#nilai_barang").val())

        var nilai_pph = hilangkanTitik('nilai_pph')
        var nilai_pph2 = parseInt($("#nilai_pph2").val())
        var biayaLain = parseInt($("#biaya_lain").val())
        var potongan = parseInt($("#potongan").val())


        if (pembulatan == 'keatas') {

            // pembulatan ke atas
            var nilai_ppn = Math.ceil(0.11 * (nilaiBarang + nilaiJasa));

        } else if (pembulatan == 'kebawah') {

            // pembulatan ke bawah
            var nilai_ppn = Math.floor(0.11 * (nilaiBarang + nilaiJasa));
        }

        if (nilai_pph == 0 && nilai_pph2 == 0) {
            var jmla = nilaiBarang + nilaiJasa + nilai_ppn + biayaLain - potongan;

            // console.log(biayaLain);
        } else {
            var jmla = nilaiBarang + nilaiJasa + nilai_ppn + biayaLain - nilai_pph - nilai_pph2 - potongan;
        }

        var jml = tandaPemisahTitik(jmla);
        document.form.jml.value = jml;

        var nilai_ppna = tandaPemisahTitik(nilai_ppn);
        $("#ppn").attr("value", nilai_ppna);
        document.form.nilai_ppn.value = nilai_ppna;
    }
</script>