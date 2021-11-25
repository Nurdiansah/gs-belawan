<?php

include "../fungsi/koneksi.php";
$id = $_GET['id'];

$queryUser =  mysqli_query($koneksi, "SELECT area from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];

$queryAnggaran =  mysqli_query($koneksi, "SELECT * 
                                              from anggaran a
                                              JOIN divisi d
                                              ON a.id_divisi=d.id_divisi                                              
                                              LEFT JOIN golongan g
                                              ON a.id_golongan = g.id_golongan
                                              LEFT JOIN sub_golongan sg
                                              ON a.id_subgolongan = sg.id_subgolongan
                                              WHERE id_anggaran = '$id'");
$rowAnggaran = mysqli_fetch_assoc($queryAnggaran);

date_default_timezone_set('Asia/Jakarta');
$waktuSekarang = date('d-m-Y H:i:s');

if (isset($_POST['simpan'])) {
    $id_anggaran = $_POST['id'];
    $divisi = $_POST['divisi'];
    $tahun = $_POST['tahun'];
    $no_coa = $_POST['no_coa'];
    $kd_anggaran = $_POST['kd_anggaran'];
    $golongan = $_POST['golongan'];
    $sub_golongan = $_POST['sub_golongan'];
    $deskripsi = $_POST['deskripsi'];
    $harga = str_replace(".", "", $_POST['harga']);
    $januari_kuantitas = $_POST['januari_kuantitas'];
    $januari_nominal = str_replace(".", "", $_POST['januari_nominal']);
    $februari_kuantitas = $_POST['februari_kuantitas'];
    $februari_nominal = str_replace(".", "", $_POST['februari_nominal']);
    $maret_kuantitas = $_POST['maret_kuantitas'];
    $maret_nominal = str_replace(".", "", $_POST['maret_nominal']);
    $april_kuantitas = $_POST['april_kuantitas'];
    $april_nominal = str_replace(".", "", $_POST['april_nominal']);
    $mei_kuantitas = $_POST['mei_kuantitas'];
    $mei_nominal = str_replace(".", "", $_POST['mei_nominal']);
    $juni_kuantitas = $_POST['juni_kuantitas'];
    $juni_nominal = str_replace(".", "", $_POST['juni_nominal']);
    $juli_kuantitas = $_POST['juli_kuantitas'];
    $juli_nominal = str_replace(".", "", $_POST['juli_nominal']);
    $agustus_kuantitas = $_POST['agustus_kuantitas'];
    $agustus_nominal = str_replace(".", "", $_POST['agustus_nominal']);
    $september_kuantitas = $_POST['september_kuantitas'];
    $september_nominal = str_replace(".", "", $_POST['september_nominal']);
    $oktober_kuantitas = $_POST['oktober_kuantitas'];
    $oktober_nominal = str_replace(".", "", $_POST['oktober_nominal']);
    $november_kuantitas = $_POST['november_kuantitas'];
    $november_nominal = str_replace(".", "", $_POST['november_nominal']);
    $desember_kuantitas = $_POST['desember_kuantitas'];
    $desember_nominal = str_replace(".", "", $_POST['desember_nominal']);
    $jumlah_kuantitas = $_POST['jml_kuantitas'];
    $jumlah_nominal = str_replace(".", "", $_POST['jml_nominal']);
    $yg_rubah = $_SESSION['username'];

    $updAnggaran = mysqli_query($koneksi, "UPDATE anggaran SET tahun = '$tahun',
                                            id_divisi = '$divisi',
                                            no_coa = '$no_coa',
                                            kd_anggaran = '$kd_anggaran',
                                            id_golongan = '$golongan',
                                            id_subgolongan = '$sub_golongan',
                                            nm_item = '$deskripsi',
                                            harga = '$harga',
                                            januari_kuantitas = '$januari_kuantitas',
                                            januari_nominal = '$januari_nominal',
                                            februari_kuantitas = '$februari_kuantitas',
                                            februari_nominal = '$februari_nominal',
                                            maret_kuantitas = '$maret_kuantitas',
                                            maret_nominal = '$maret_nominal',
                                            april_kuantitas = '$april_kuantitas',
                                            april_nominal = '$april_nominal',
                                            mei_kuantitas = '$mei_kuantitas',
                                            mei_nominal = '$mei_nominal',
                                            juni_kuantitas = '$juni_kuantitas',
                                            juni_nominal = '$juni_nominal',
                                            juli_kuantitas = '$juli_kuantitas',
                                            juli_nominal = '$juli_nominal',
                                            agustus_kuantitas = '$agustus_kuantitas',
                                            agustus_nominal = '$agustus_nominal',
                                            september_kuantitas = '$september_kuantitas',
                                            september_nominal = '$september_nominal',
                                            oktober_kuantitas = '$oktober_kuantitas',
                                            oktober_nominal = '$oktober_nominal',
                                            november_kuantitas = '$november_kuantitas',
                                            november_nominal = '$november_nominal',
                                            desember_kuantitas = '$desember_kuantitas',
                                            desember_nominal = '$desember_nominal',
                                            jumlah_kuantitas = '$jumlah_kuantitas',
                                            jumlah_nominal = '$jumlah_nominal',
                                            last_modified_by = '$yg_rubah',
                                            last_modified_on = now()
                                        WHERE id_anggaran = '$id_anggaran'");
    if ($updAnggaran) {
        header('Location: index.php?p=anggaran&divisi=' . $_GET['divisi'] . '&tahun=' . $_GET['tahun'] . '');
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}
?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=anggaran&divisi=<?= $_GET['divisi'] ?>&tahun=<?= $_GET['tahun']; ?>" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Rubah Anggaran <?= $rowAnggaran['nm_item']; ?></h3>
                </div>
                <form method="POST" name="form" action="" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="id" value="<?= $id; ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset-1 col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <select name="divisi" class="form-control">
                                    <option value="">--Pilih Divisi--</option>
                                    <?php
                                    $queryDivisi = mysqli_query($koneksi, "SELECT * FROM divisi ORDER BY nm_divisi ASC");
                                    if (mysqli_num_rows($queryDivisi)) {
                                        while ($rowDivisi = mysqli_fetch_assoc($queryDivisi)) :
                                    ?>
                                            <option value="<?= $rowDivisi['id_divisi']; ?>" <?php if ($rowAnggaran['id_divisi'] == $rowDivisi['id_divisi']) {
                                                                                                echo "selected=\"selected\"";
                                                                                            } ?>><?= $rowDivisi['nm_divisi']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                            <!-- </div>
                    <div class="form-group"> -->
                            <label id="tes" for="tahun" class="col-sm-2 control-label">Anggaran Tahun</label>
                            <div class="col-sm-3">
                                <select name="tahun" class="form-control">
                                    <?php
                                    $tahunSekarang = date('Y');
                                    foreach (range(2019, $tahunSekarang) as $tahun) { ?>
                                        <option value="<?= $tahun; ?>" <?php if ($rowAnggaran['tahun'] == $tahun) {
                                                                            echo "selected=\"selected\"";
                                                                        } ?>><?= $tahun; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="waktu" value="<?php echo $waktuSekarang; ?>">
                        <div class="form-group">
                            <label id="tes" for="no_coa" class="col-sm-2 control-label">Nomor Coa</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="no_coa" value="<?= $rowAnggaran['no_coa']; ?>">
                            </div>
                            <!-- </div>
                        <div class="form-group"> -->
                            <label id="tes" for="kd_anggaran" class="col-sm-2 control-label">Kode Transaksi</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="kd_anggaran" value="<?= $rowAnggaran['kd_anggaran']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="golongan" class="col-sm-offset-1 col-sm-1 control-label">Golongan</label>
                            <div class="col-sm-3">
                                <select name="golongan" class="form-control">
                                    <option value="">--Pilih Golongan--</option>
                                    <?php
                                    $querygolongan = mysqli_query($koneksi, "SELECT * FROM golongan ORDER BY nm_golongan ASC");
                                    if (mysqli_num_rows($querygolongan)) {
                                        while ($rowgolongan = mysqli_fetch_assoc($querygolongan)) :
                                    ?>
                                            <option value="<?= $rowgolongan['id_golongan']; ?>" <?php if ($rowAnggaran['id_golongan'] == $rowgolongan['id_golongan']) {
                                                                                                    echo "selected=selected";
                                                                                                } ?>><?= $rowgolongan['nm_golongan']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                            <!-- </div>
                        <div class="form-group"> -->
                            <label id="tes" for="sub_golongan" class="col-sm-offset-0 col-sm-2 control-label">Sub Golongan</label>
                            <div class="col-sm-3">
                                <select name="sub_golongan" class="form-control">
                                    <option value="">--Pilih Sub Golongan--</option>
                                    <?php
                                    $querysubgolongan = mysqli_query($koneksi, "SELECT * FROM sub_golongan ORDER BY nm_subgolongan ASC");
                                    if (mysqli_num_rows($querysubgolongan)) {
                                        while ($rowsubgolongan = mysqli_fetch_assoc($querysubgolongan)) :
                                    ?>
                                            <option value="<?= $rowsubgolongan['id_subgolongan']; ?>" <?php if ($rowAnggaran['id_subgolongan'] == $rowsubgolongan['id_subgolongan']) {
                                                                                                            echo "selected=selected";
                                                                                                        } ?>><?= $rowsubgolongan['nm_subgolongan']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="perhitungan">
                            <div class="form-group">
                                <label id="tes" for="nm_item" class="col-sm-offset-1 col-sm-1 control-label">Deskripsi</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="deskripsi" value="<?= $rowAnggaran['nm_item']; ?>">
                                </div>
                                <!-- </div>
                        <div class="form-group"> -->
                                <label id="tes" for="harga" class="col-sm-offset-1 col-sm-1 control-label" id="hargal">Harga</label>
                                <div class="col-sm-3">

                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control " name="harga" id="harga_nominal" value="<?= $rowAnggaran['harga']; ?>" />
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label id="tes" for="Quantity" class="col-sm-offset-1 col-sm-3 control-label">Quantity</label>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="januari_nominal" class="col-sm-offset-1 col-sm-4 control-label">Nominal</label>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label id="tes" for="januari_kuantitas" class="col-sm-offset- col-sm-2 control-label">Januari </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="<?= $rowAnggaran['januari_kuantitas']; ?>" min="0" name="januari_kuantitas" id="januari_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="januari_nominal" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" value="<?= $rowAnggaran['januari_nominal']; ?>" name="januari_nominal" id="januari_nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="februari_kuantitas" class="col-sm-offset- col-sm-2 control-label">Februari </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="<?= $rowAnggaran['februari_kuantitas']; ?>" min="0" name="februari_kuantitas" id="februari_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="februari_nominal" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" value="<?= $rowAnggaran['februari_nominal']; ?>" name="februari_nominal" id="februari_nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="maret_kuantitas" class="col-sm-offset- col-sm-2 control-label">Maret </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="<?= $rowAnggaran['maret_kuantitas']; ?>" min="0" name="maret_kuantitas" id="maret_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="maret_nominal" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" value="<?= $rowAnggaran['maret_nominal']; ?>" name="maret_nominal" id="maret_nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="april_kuantitas" class="col-sm-offset- col-sm-2 control-label">April </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="<?= $rowAnggaran['april_kuantitas']; ?>" min="0" name="april_kuantitas" id="april_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="april_nominal" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" value="<?= $rowAnggaran['april_nominal']; ?>" name="april_nominal" id="april_nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="mei_kuantitas" class="col-sm-offset- col-sm-2 control-label">Mei </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="<?= $rowAnggaran['mei_kuantitas']; ?>" min="0" name="mei_kuantitas" id="mei_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="mei_nominal" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" value="<?= $rowAnggaran['mei_nominal']; ?>" name="mei_nominal" id="mei_nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="juni_kuantitas" class="col-sm-offset- col-sm-2 control-label">Juni </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="<?= $rowAnggaran['juni_kuantitas']; ?>" min="0" name="juni_kuantitas" id="juni_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="juni_nominal" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" value="<?= $rowAnggaran['juni_nominal']; ?>" name="juni_nominal" id="juni_nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="juli_kuantitas" class="col-sm-offset- col-sm-2 control-label">Juli </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="<?= $rowAnggaran['juli_kuantitas']; ?>" min="0" name="juli_kuantitas" id="juli_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="juli_nominal" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" value="<?= $rowAnggaran['juli_nominal']; ?>" name="juli_nominal" id="juli_nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="agustus_kuantitas" class="col-sm-offset- col-sm-2 control-label">Agustus </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="<?= $rowAnggaran['agustus_kuantitas']; ?>" min="0" name="agustus_kuantitas" id="agustus_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="agustus_nominal" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" value="<?= $rowAnggaran['agustus_nominal']; ?>" name="agustus_nominal" id="agustus_nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="september_kuantitas" class="col-sm-offset- col-sm-2 control-label">September </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="<?= $rowAnggaran['september_kuantitas']; ?>" min="0" name="september_kuantitas" id="september_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="september_nominal" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" value="<?= $rowAnggaran['september_nominal']; ?>" name="september_nominal" id="september_nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="oktober_kuantitas" class="col-sm-offset- col-sm-2 control-label">Oktober </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="<?= $rowAnggaran['oktober_kuantitas']; ?>" min="0" name="oktober_kuantitas" id="oktober_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="oktober_nominal" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" value="<?= $rowAnggaran['oktober_nominal']; ?>" name="oktober_nominal" id="oktober_nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="november_kuantitas" class="col-sm-offset- col-sm-2 control-label">November </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="<?= $rowAnggaran['november_kuantitas']; ?>" min="0" name="november_kuantitas" id="november_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="november_nominal" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" value="<?= $rowAnggaran['november_nominal']; ?>" name="november_nominal" id="november_nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="desember_kuantitas" class="col-sm-offset- col-sm-2 control-label">Desember </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="<?= $rowAnggaran['desember_kuantitas']; ?>" min="0" name="desember_kuantitas" id="desember_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="desember_nominal" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" value="<?= $rowAnggaran['desember_nominal']; ?>" name="desember_nominal" id="desember_nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" />
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-auto">
                                <div class="form-group">
                                    <label id="tes" for="jml_kuantitas" class="col-sm-offset- col-sm-2 control-label">Jumlah Kuantitas</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="jml_kuantitas" value="<?= $rowAnggaran['jumlah_kuantitas']; ?>">
                                    </div>
                                    <!-- </div>
                            <div class="form-group"> -->
                                    <label id="tes" for="jml_nominal" class="col-sm-offset- col-sm-2 control-label">Jumlah Nominal </label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" class="form-control" name="jml_nominal" readonly value="<?= $rowAnggaran['jumlah_nominal']; ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-5 " value="Simpan">
                                &nbsp;
                                <input type="reset" class="btn btn-danger" value="Batal">
                            </div>
                        </div>
                </form>

            </div>
        </div>
    </div>
</section>

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

    $(".perhitungan").keyup(function() {

        //ambil inputan harga
        // var harga = parseInt($("#harga_nominal").val())

        var harga = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('harga_nominal').value))))); //input ke dalam angka tanpa titik

        // nominal januari
        var januari_nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('januari_nominal').value))))); //input ke dalam angka tanpa titik

        // nominal februari
        var februari_nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('februari_nominal').value))))); //input ke dalam angka tanpa titik

        // nominal maret
        var maret_nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('maret_nominal').value))))); //input ke dalam angka tanpa titik

        // nominal april
        var april_nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('april_nominal').value))))); //input ke dalam angka tanpa titik

        // nominal mei
        var mei_nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('mei_nominal').value))))); //input ke dalam angka tanpa titik

        // nominal juni
        var juni_nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('juni_nominal').value))))); //input ke dalam angka tanpa titik

        // nominal juli
        var juli_nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('juli_nominal').value))))); //input ke dalam angka tanpa titik

        // nominal agustus
        var agustus_nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('agustus_nominal').value))))); //input ke dalam angka tanpa titik

        // nominal september
        var september_nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('september_nominal').value))))); //input ke dalam angka tanpa titik

        // nominal oktober
        var oktober_nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('oktober_nominal').value))))); //input ke dalam angka tanpa titik

        // nominal november
        var november_nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('november_nominal').value))))); //input ke dalam angka tanpa titik

        // nominal desember
        var desember_nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('desember_nominal').value))))); //input ke dalam angka tanpa titik

        //ambil inputan kuantitas januari
        var jk = parseInt($("#januari_kuantitas").val())

        //ambil inputan kuantitas februari
        var fk = parseInt($("#februari_kuantitas").val())

        //ambil inputan kuantitas maret
        var mk = parseInt($("#maret_kuantitas").val())

        //ambil inputan kuantitas april
        var apk = parseInt($("#april_kuantitas").val())


        //ambil inputan kuantitas mei
        var mek = parseInt($("#mei_kuantitas").val())


        //ambil inputan kuantitas juni
        var junk = parseInt($("#juni_kuantitas").val())


        //ambil inputan kuantitas juli
        var julk = parseInt($("#juli_kuantitas").val())



        //ambil inputan kuantitas agustus
        var agk = parseInt($("#agustus_kuantitas").val())


        //ambil inputan kuantitas september
        var sepk = parseInt($("#september_kuantitas").val())


        //ambil inputan kuantitas oktober
        var oktk = parseInt($("#oktober_kuantitas").val())


        //ambil inputan kuantitas november
        var novk = parseInt($("#november_kuantitas").val())


        //ambil inputan kuantitas desember
        var desk = parseInt($("#desember_kuantitas").val())


        // jumlah nominal
        var jmlKuantitas = jk + fk + mk + apk + mek + junk + julk + agk + sepk + oktk + novk + desk;
        $("#jml_kuantitas").attr("value", jmlKuantitas);
        document.form.jml_kuantitas.value = jmlKuantitas;

        // jumlah nominal
        var jml_nominal = januari_nominal + februari_nominal + maret_nominal + april_nominal + mei_nominal + juni_nominal + juli_nominal + agustus_nominal + september_nominal + oktober_nominal + november_nominal + desember_nominal;
        var jml_nominala = tandaPemisahTitik(jml_nominal);
        document.form.jml_nominal.value = jml_nominala;

    });


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