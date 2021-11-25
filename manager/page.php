 
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
}
// halaman baru
else if ($page == "approval_biayanonops") {
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
} else if ($page == "approval_bno") {
    include_once "approval_bno.php";
} else if ($page == "approval_detailbno") {
    include_once "approval_detailbno.php";
} else if ($page == "setuju_bno") {
    include_once "setuju_bno.php";
} else if ($page == "tolaktax_bno") {
    include_once "tolaktax_bno.php";
} else if ($page == "tolakuser_bno") {
    include_once "tolakuser_bno.php";
} else if ($page == "lihat_bno") {
    include_once "lihat_bno.php";
} else if ($page == "approval_mr") {
    include_once "approval_mr.php";
} else if ($page == "app_dmr") {
    include_once "app_dmr.php";
} else if ($page == "proses_mr") {
    include_once "proses_mr.php";
} else if ($page == "proses_dmr") {
    include_once "proses_dmr.php";
} else if ($page == "dmr") {
    include_once "dmr.php";
} else if ($page == "approval_pettycash") {
    include_once "approval_pettycash.php";
} else if ($page == "approval_dpettycash") {
    include_once "approval_dpettycash.php";
} else if ($page == "proses_pettycash") {
    include_once "proses_pettycash.php";
} else if ($page == "proses_dpettycash") {
    include_once "proses_dpettycash.php";
} else if ($page == "transaksi_pettycash") {
    include_once "transaksi_pettycash.php";
} else if ($page == "proses_kasbon") {
    include_once "proses_kasbon.php";
} else if ($page == "proses_dkasbon") {
    include_once "proses_dkasbon.php";
} else if ($page == "transaksi_kasbon") {
    include_once "transaksi_kasbon.php";
} else if ($page == "po_proses") {
    include_once "po_proses.php";
} else if ($page == "po_dproses") {
    include_once "po_dproses.php";
} else if ($page == "po_transaksi") {
    include_once "po_transaksi.php";
} else if ($page == "po_dtransaksi") {
    include_once "po_dtransaksi.php";
} else if ($page == "rubah_password") {
    include_once "rubah_password.php";
} else if ($page == "approval_kasbon") {
    include_once "approval_kasbon.php";
} else if ($page == "ditolak_kasbon") {
    include_once "ditolak_kasbon.php";
} else if ($page == "dtl_kasbonditolak") {
    include_once "dtl_kasbonditolak.php";
} else if ($page == "ditolak_bno") {
    include_once "ditolak_bno.php";
} else if ($page == "dtl_ditolakbno") {
    include_once "dtl_ditolakbno.php";
}
// SR
else if ($page == "approval_sr") {
    include_once "approval_sr.php";
} else if ($page == "detail_sr") {
    include_once "detail_sr.php";
} else if ($page == "proses_sr") {
    include_once "proses_sr.php";
}
