 
<?php

$page = isset($_GET['p']) ? $_GET['p'] : "";

if ($page == 'formpesan') {
    include_once "formpesan.php";
} else if ($page == "") {
    include_once "laporan_anggaran.php"; // main.php
} else if ($page == "lihat_kaskeluar") {
    include_once "lihat_kaskeluar.php";
} else if ($page == "proses_kaskeluar") {
    include_once "proses_kaskeluar.php";
} else if ($page == "detail_proseskaskeluar") {
    include_once "detail_proseskaskeluar.php";
} else if ($page == "approval") {
    include_once "approval.php";
} else if ($page == "detail_lihatkaskeluar") {
    include_once "detail_lihatkaskeluar.php";
} else if ($page == "payment_kaskeluar") {
    include_once "payment_kaskeluar.php";
} else if ($page == "detail_paymentkaskeluar") {
    include_once "detail_paymentkaskeluar.php";
} else if ($page == "cetak_bkk") {
    include_once "cetak_bkk.php";
} else if ($page == "buat_kaskeluar") {
    include_once "buat_kaskeluar.php";
    // mulai page baru di anggaran
} else if ($page == "input_anggaran") {
    include_once "input_anggaran.php";
} else if ($page == "anggaran") {
    include_once "anggaran.php";
} else if ($page == "hapus_anggaran") {
    include_once "hapus_anggaran.php";
} else if ($page == "golongan") {
    include_once "golongan.php";
} else if ($page == "hapus_golongan") {
    include_once "hapus_golongan.php";
} else if ($page == "sub_golongan") {
    include_once "sub_golongan.php";
} else if ($page == "hapus_subgolongan") {
    include_once "hapus_subgolongan.php";
} else if ($page == "verifikasi_biayanonops") {
    include_once "verifikasi_biayanonops.php";
} else if ($page == "verifikasi_detailbiayanonops") {
    include_once "verifikasi_detailbiayanonops.php";
} else if ($page == "tolak_biayanonops") {
    include_once "tolak_biayanonops.php";
} else if ($page == "edit_golongan") {
    include_once "edit_golongan.php";
} else if ($page == "edit_subgolongan") {
    include_once "edit_subgolongan.php";
} else if ($page == "lihat_detailanggaran") {
    include_once "lihat_detailanggaran.php";
} else if ($page == "verifikasi_mr") {
    include_once "verifikasi_mr.php";
} else if ($page == "verifikasi_dmr") {
    include_once "verifikasi_dmr.php";
} else if ($page == "edit_item_mr") {
    include_once "edit_item_mr.php";
} else if ($page == "auto_edit_item_mr") {
    include_once "auto_edit_item_mr.php";
} else if ($page == "tolak_mr") {
    include_once "tolak_mr.php";
} else if ($page == "setuju_mr") {
    include_once "setuju_mr.php";
} else if ($page == "input_anggaran_manual") {
    include_once "input_anggaran_manual.php";
} else if ($page == "transaksi_kasbon") {
    include_once "transaksi_kasbon.php";
} else if ($page == "transaksi_dkasbon") {
    include_once "transaksi_dkasbon.php";
} else if ($page == "transaksi_po") {
    include_once "transaksi_po.php";
} else if ($page == "transaksi_dpo") {
    include_once "transaksi_dpo.php";
} else if ($page == "transaksi_bu") {
    include_once "transaksi_bu.php";
} else if ($page == "transaksi_dbu") {
    include_once "transaksi_dbu.php";
} else if ($page == "laporan-xls") {
    include_once "laporan-xls.php";
} else if ($page == "rubah_password") {
    include_once "rubah_password.php";
} else if ($page == "monitoring_mr") {
    include_once "monitoring_mr.php";
} else if ($page == "monitoring_kasbon") {
    include_once "monitoring_kasbon.php";
} else if ($page == "monitoring_po") {
    include_once "monitoring_po.php";
} else if ($page == "monitoring_pettycash") {
    include_once "monitoring_pettycash.php";
} else if ($page == "monitoring_bu") {
    include_once "monitoring_bu.php";
} else if ($page == "edit_anggaran") {
    include_once "edit_anggaran.php";
} else if ($page == "user") {
    include_once "user.php";
} else if ($page == "edit_user") {
    include_once "edit_user.php";
} else if ($page == "monitoring_so") {
    include_once "monitoring_so.php";
} else if ($page == "program_kerja") {
    include_once "program_kerja.php";
} else if ($page == "laporan_lr") {
    include_once "laporan_lr.php";
} else if ($page == "laporan_rk") {
    include_once "laporan_rk.php";
} else if ($page == "transaksi_bkk") {
    include_once "transaksi_bkk.php";
} else if ($page == "laporan_anggaran") {
    include_once "laporan_anggaran.php";
} else if ($page == "laporan_programkerja") {
    include_once "laporan_programkerja.php";
} else if ($page == "header_subheader") {
    include_once "header_subheader.php";
}
