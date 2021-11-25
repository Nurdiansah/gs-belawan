 
<?php

$page = isset($_GET['p']) ? $_GET['p'] : "";

if ($page == 'formpesan') {
    include_once "formpesan.php";
} else if ($page == "") {
    include_once "main.php";
} else if ($page == "lihat_kaskeluar") {
    include_once "lihat_kaskeluar.php";
} else if ($page == "detail_kaskeluar") {
    include_once "detail_kaskeluar.php";
} else if ($page == "approval_biayanonops") {
    include_once "approval_biayanonops.php";
} else if ($page == "approval_detailbiayanonops") {
    include_once "approval_detailbiayanonops.php";
} else if ($page == "proses_biayanonops") {
    include_once "proses_biayanonops.php";
} else if ($page == "detail_biayanonops") {
    include_once "detail_biayanonops.php";
} else if ($page == "lihat_kaskeluar") {
    include_once "lihat_kaskeluar.php";
} else if ($page == "detail_lihatkaskeluar") {
    include_once "detail_lihatkaskeluar.php";
}
// page baru
else if ($page == "verifikasi_bno") {
    include_once "verifikasi_bno.php";
} else if ($page == "detail_verifikasibno") {
    include_once "detail_verifikasibno.php";
} else if ($page == "setuju_bno") {
    include_once "setuju_bno.php";
} else if ($page == "tolaktax_bno ") {
    include_once "tolaktax_bno.php";
} else if ($page == "tolakuser_bno ") {
    include_once "tolakuser_bno.php";
} else if ($page == "verifikasi_mr") {
    include_once "verifikasi_mr.php";
} else if ($page == "verifikasi_dmr") {
    include_once "verifikasi_dmr.php";
} else if ($page == "verifikasi_kasbon") {
    include_once "verifikasi_kasbon.php";
} else if ($page == "verifikasi_dkasbon") {
    include_once "verifikasi_dkasbon.php";
} else if ($page == "verifikasi_po") {
    include_once "verifikasi_po.php";
} else if ($page == "verifikasi_dpo") {
    include_once "verifikasi_dpo.php";
} else if ($page == "verifikasi_bkk") {
    include_once "verifikasi_bkk.php";
} else if ($page == "verifikasi_dbkk") {
    include_once "verifikasi_dbkk.php";
} else if ($page == "transaksi_bno") {
    include_once "transaksi_bno.php";
} else if ($page == "transaksi_dbno") {
    include_once "transaksi_dbno.php";
} else if ($page == "transaksi_kasbon") {
    include_once "transaksi_kasbon.php";
} else if ($page == "transaksi_dkasbon") {
    include_once "transaksi_dkasbon.php";
} else if ($page == "transaksi_po") {
    include_once "transaksi_po.php";
} else if ($page == "transaksi_dpo") {
    include_once "transaksi_dpo.php";
} else if ($page == "transaksi_bkk") {
    include_once "transaksi_bkk.php";
} else if ($page == "rubah_password") {
    include_once "rubah_password.php";
} else if ($page == "verifikasi_dkasbon_user") {
    include_once "verifikasi_dkasbon_user.php";
} else if ($page == "laporan_anggaran") {
    include_once "laporan_anggaran.php";
} else if ($page == "approval_sr") {
    include_once "approval_sr.php";
} else if ($page == "detail_sr") {
    include_once "detail_sr.php";
} else if ($page == "detail_srk") {
    include_once "detail_srk.php";
} else if ($page == "transaksi_so") {
    include_once "transaksi_so.php";
}
