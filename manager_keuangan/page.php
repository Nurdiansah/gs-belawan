 
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
} else if ($page == "verifikasi_mr") {
    include_once "verifikasi_mr.php";
} else if ($page == "verifikasi_dmr") {
    include_once "verifikasi_dmr.php";
} else if ($page == "lihat_itemmr") {
    include_once "lihat_itemmr.php";
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
} else if ($page == "rubah_password") {
    include_once "rubah_password.php";
} else if ($page == "verifikasi_dkasbon_user") {
    include_once "verifikasi_dkasbon_user.php";
} else if ($page == "approval_kasbon") {
    include_once "approval_kasbon.php";
} else if ($page == "approval_pettycash") {
    include_once "approval_pettycash.php";
} else if ($page == "approval_dpettycash") {
    include_once "approval_dpettycash.php";
} else if ($page == "ditolak_kasbon") {
    include_once "ditolak_kasbon.php";
} else if ($page == "dtl_kasbonditolak") {
    include_once "dtl_kasbonditolak.php";
} else if ($page == "ditolak_bno") {
    include_once "ditolak_bno.php";
} else if ($page == "dtl_ditolakbno") {
    include_once "dtl_ditolakbno.php";
} else if ($page == "transaksi_pettycash") {
    include_once "transaksi_pettycash.php";
} else if ($page == "transaksi_dpettycash") {
    include_once "transaksi_dpettycash.php";
} else if ($page == "proses_mr") {
    include_once "proses_mr.php";
} else if ($page == "proses_dmr") {
    include_once "proses_dmr.php";
} else if ($page == "ditolak_bkk") {
    include_once "ditolak_bkk.php";
} else if ($page == "dtl_bkkditolak") {
    include_once "dtl_bkkditolak.php";
} else if ($page == "dtl_kasbonditolak_user") {
    include_once "dtl_kasbonditolak_user.php";
} else if ($page == "proses_kasbon") {
    include_once "proses_kasbon.php";
} else if ($page == "proses_dkasbon") {
    include_once "proses_dkasbon.php";
} else if ($page == "kasbon_dproses_user") {
    include_once "kasbon_dproses_user.php";
} else if ($page == "kasbon_transaksi") {
    include_once "kasbon_transaksi.php";
} //kurang kasbon d proses
else if ($page == "po_proses") {
    include_once "po_proses.php";
} else if ($page == "po_dproses") {
    include_once "po_dproses.php";
} else if ($page == "po_transaksi") {
    include_once "po_transaksi.php";
} else if ($page == "po_dtransaksi") {
    include_once "po_dtransaksi.php";
} else if ($page == "proses_bkk") {
    include_once "proses_bkk.php";
} else if ($page == "transaksi_bkk") {
    include_once "transaksi_bkk.php";
} else if ($page == "ditolak_po") {
    include_once "ditolak_po.php";
} else if ($page == "dtl_ditolakpo") {
    include_once "dtl_ditolakpo.php";
} else if ($page == "kasbon_proses") {
    include_once "kasbon_proses.php";
} else if ($page == "laporan_anggaran") {
    include_once "laporan_anggaran.php";
} else if ($page == "approval_sr") {
    include_once "approval_sr.php";
} else if ($page == "detail_sr") {
    include_once "detail_sr.php";
} else if ($page == "ditolak_so") {
    include_once "ditolak_so.php";
} else if ($page == "detail_srk") {
    include_once "detail_srk.php";
} else if ($page == "dtl_ditolak_srk") {
    include_once "dtl_ditolak_srk.php";
} else if ($page == "proses_sr") {
    include_once "proses_sr.php";
} else if ($page == "dmr") {
    include_once "dmr.php";
} else if ($page == "verifikasi_biayaops") {
    include_once "verifikasi_biayaops.php";
} else if ($page == "verifikasi_dbo") {
    include_once "verifikasi_dbo.php";
} else if ($page == "verifikasi_refill") {
    include_once "verifikasi_refill.php";
} else if ($page == "verifikasi_drefill") {
    include_once "verifikasi_drefill.php";
} else if ($page == "verifikasi_bkm") {
    include_once "verifikasi_bkm.php";
} else if ($page == "proses_refill") {
    include_once "proses_refill.php";
} else if ($page == "dtl_prosesrefill") {
    include_once "dtl_prosesrefill.php";
} else if ($page == "proses_bno") {
    include_once "proses_bno.php";
} else if ($page == "dtl_prosesbno") {
    include_once "dtl_prosesbno.php";
} else if ($page == "proses_bkm") {
    include_once "proses_bkm.php";
}
