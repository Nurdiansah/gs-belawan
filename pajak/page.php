 
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
} else if ($page == "verifikasi_kasbon") {
    include_once "verifikasi_kasbon.php";
} else if ($page == "verifikasi_dkasbon") {
    include_once "verifikasi_dkasbon.php";
} else if ($page == "verifikasi_dkasbon_user") {
    include_once "verifikasi_dkasbon_user.php";
} else if ($page == "verifikasi_itemmr") {
    include_once "verifikasi_itemmr.php";
} else if ($page == "detail_verifikasibno") {
    include_once "detail_verifikasibno.php";
} else if ($page == "setuju_bno") {
    include_once "setuju_bno.php";
} else if ($page == "tolaktax_bno ") {
    include_once "tolaktax_bno.php";
} else if ($page == "tolakuser_bno ") {
    include_once "tolakuser_bno.php";
} else if ($page == "verifikasi_po") {
    include_once "verifikasi_po.php";
} else if ($page == "verifikasi_dpo") {
    include_once "verifikasi_dpo.php";
} else if ($page == "rubah_password") {
    include_once "rubah_password.php";
} else if ($page == "ditolak_kasbon") {
    include_once "ditolak_kasbon.php";
} else if ($page == "dtl_kasbonditolak") {
    include_once "dtl_kasbonditolak.php";
} else if ($page == "ditolak_po") {
    include_once "ditolak_po.php";
} else if ($page == "dtl_ditolakpo") {
    include_once "dtl_ditolakpo.php";
} else if ($page == "verifikasi_sr") {
    include_once "verifikasi_sr.php";
} else if ($page == "detail_sr") {
    include_once "detail_sr.php";
} else if ($page == "detail_srk") {
    include_once "detail_srk.php";
} else if ($page == "verifikasi_lpj") {
    include_once "verifikasi_lpj.php";
} else if ($page == "verifikasi_dkasbon_lpj") {
    include_once "verifikasi_dkasbon_lpj.php";
} else if ($page == "verifikasi_bkm") {
    include_once "verifikasi_bkm.php";
} else if ($page == "ditolak_bkm") {
    include_once "ditolak_bkm.php";
}
