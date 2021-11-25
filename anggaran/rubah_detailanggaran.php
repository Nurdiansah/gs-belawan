
<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['simpan'])) {
    $id_anggaran = $_POST['id_anggaran'];
    $divisi = $_POST['divisi'];
    $tahun = $_POST['tahun'];
    $no_coa = $_POST['no_coa'];
    $kd_anggaran = $_POST['kd_anggaran'];
    $golongan = $_POST['golongan'];
    $sub_golongan = $_POST['sub_golongan'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $januari_kuantitas = $_POST['januari_kuantitas'];
    $januari_nominal = $_POST['januari_nominal'];
    $februari_kuantitas = $_POST['februari_kuantitas'];
    $februari_nominal = $_POST['februari_nominal'];
    $maret_kuantitas = $_POST['maret_kuantitas'];
    $maret_nominal = $_POST['maret_nominal'];
    $april_kuantitas = $_POST['april_kuantitas'];
    $april_nominal = $_POST['april_nominal'];
    $mei_kuantitas = $_POST['mei_kuantitas'];
    $mei_nominal = $_POST['mei_nominal'];
    $juni_kuantitas = $_POST['juni_kuantitas'];
    $juni_nominal = $_POST['juni_nominal'];
    $juli_kuantitas = $_POST['juli_kuantitas'];
    $juli_nominal = $_POST['juli_nominal'];
    $agustus_kuantitas = $_POST['agustus_kuantitas'];
    $agustus_nominal = $_POST['agustus_nominal'];
    $september_kuantitas = $_POST['september_kuantitas'];
    $september_nominal = $_POST['september_nominal'];
    $oktober_kuantitas = $_POST['oktober_kuantitas'];
    $oktober_nominal = $_POST['oktober_nominal'];
    $november_kuantitas = $_POST['november_kuantitas'];
    $november_nominal = $_POST['november_nominal'];
    $desember_kuantitas = $_POST['desember_kuantitas'];
    $desember_nominal = $_POST['desember_nominal'];
    $jml_kuantitas = $_POST['jml_kuantitas'];
    $jml_nominal = $_POST['jml_nominal'];
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
                                            jml_kuantitas = '$jml_kuantitas',
                                            jml_nominal = '$jml_nominal',
                                            last_modified_by = '$yg_rubah',
                                            last_modified_on = now()
                                        WHERE id_anggaran = '$id_anggaran'");

    if ($updAnggaran) {
        header('Location: index.php?p=edit_anggaran&id=' . $id_anggaran . '');
    }
}
