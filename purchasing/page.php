 
<?php

$page = isset($_GET['p']) ? $_GET['p'] : "";

if ($page == 'formpesan') {
    include_once "formpesan.php";
} else if ($page == "") {
    include_once "main.php";
} else if ($page == "tambah_anggaran") {
    include_once "tambah_anggaran.php";
} else if ($page == "add_anggaran") {
    include_once "add_anggaran.php";
} else if ($page == "hapus_anggaran") {
    include_once "hapus_anggaran.php";
} else if ($page == "lihat_anggaran") {
    include_once "lihat_anggaran.php";
} else if ($page == "buat_biayanonops") {
    include_once "buat_biayanonops.php";
} else if ($page == "proses_biayanonops") {
    include_once "proses_biayanonops.php";
} else if ($page == "ditolak_biayanonops") {
    include_once "ditolak_biayanonops.php";
} else if ($page == "lihat_kaskeluar") {
    include_once "lihat_kaskeluar.php";
} else if ($page == "detail_biayanonops") {
    include_once "detail_biayanonops.php";
} else if ($page == "pjk_verifikasibno") {
    include_once "pjk_verifikasibno.php";
} else if ($page == "pjk_detailverifikasibno") {
    include_once "pjk_detailverifikasibno.php";
} else if ($page == "lihat_bno") {
    include_once "lihat_bno.php";
} else if ($page == "buat_mr") {
    include_once "buat_mr.php";
} else if ($page == "edit_item") {
    include_once "edit_item.php";
} else if ($page == "detail_item") {
    include_once "detail_item.php";
} else if ($page == "proses_mr") {
    include_once "proses_mr.php";
} else if ($page == "detail_mr") {
    include_once "detail_mr.php";
} else if ($page == "tolak_mr") {
    include_once "tolak_mr.php";
} else if ($page == "detail_tolakmr") {
    include_once "detail_tolakmr.php";
} else if ($page == "edit_item_tolak") {
    include_once "edit_item_tolak.php";
} else if ($page == "list_mr") {
    include_once "list_mr.php";
} else if ($page == "verifikasi_dmr") {
    include_once "verifikasi_dmr.php";
} else if ($page == "supplier") {
    include_once "supplier.php";
} else if ($page == "bidding_itemmr") {
    include_once "bidding_itemmr.php";
} else if ($page == "kasbon_process") {
    include_once "kasbon_process.php";
} else if ($page == "kasbon_dproses") {
    include_once "kasbon_dproses.php";
} else if ($page == "lpj_kasbon") {
    include_once "lpj_kasbon.php";
} else if ($page == "lpj_dkasbon") {
    include_once "lpj_dkasbon.php";
} else if ($page == "submit_po") {
    include_once "submit_po.php";
} else if ($page == "submit_dpo") {
    include_once "submit_dpo.php";
} else if ($page == "po_proses") {
    include_once "po_proses.php";
} else if ($page == "po_dproses") {
    include_once "po_dproses.php";
} else if ($page == "edit_rincianbarang") {
    include_once "edit_rincianbarang.php";
} else if ($page == "po_outstanding") {
    include_once "po_outstanding.php";
} else if ($page == "po_doutstanding") {
    include_once "po_doutstanding.php";
} else if ($page == "transaksi_kasbon") {
    include_once "transaksi_kasbon.php";
} else if ($page == "transaksi_dkasbon") {
    include_once "transaksi_dkasbon.php";
} else if ($page == "transaksi_po") {
    include_once "transaksi_po.php";
} else if ($page == "transaksi_dpo") {
    include_once "transaksi_dpo.php";
} else if ($page == "rubah_password") {
    include_once "rubah_password.php";
} else if ($page == "list_tolakmr") {
    include_once "list_tolakmr.php";
} else if ($page == "ditolak_kasbon") {
    include_once "ditolak_kasbon.php";
} else if ($page == "dtl_kasbonditolak") {
    include_once "dtl_kasbonditolak.php";
} else if ($page == "ditolak_mr") {
    include_once "ditolak_mr.php";
} else if ($page == "dtl_ditolakpo") {
    include_once "dtl_ditolakpo.php";
} else if ($page == "submit_kasbon") {
    include_once "submit_kasbon.php";
} else if ($page == "dtl_submitkasbon") {
    include_once "dtl_submitkasbon.php";
} else if ($page == "submit_kembali_po") {
    include_once "submit_kembali_po.php";
} else if ($page == "dtl_submitpo") {
    include_once "dtl_submitpo.php";
} else if ($page == "verifikasi_sr") {
    include_once "verifikasi_sr.php";
} else if ($page == "detail_sr") {
    include_once "detail_sr.php";
} else if ($page == "ditolak_sr") {
    include_once "ditolak_sr.php";
} else if ($page == "submit_kembali_so") {
    include_once "submit_kembali_so.php";
} else if ($page == "detail_so") {
    include_once "detail_so.php";
} else if ($page == "detail_srk") {
    include_once "detail_srk.php";
} else if ($page == "proses_petty") {
    include_once "proses_petty.php";
} else if ($page == "lpj_petty") {
    include_once "lpj_petty.php";
} else if ($page == "lpj_dpetty") {
    include_once "lpj_dpetty.php";
} else if ($page == "proses_sr") {
    include_once "proses_sr.php";
} else if ($page == "proses_petty") {
    include_once "proses_petty.php";
} else if ($page == "revisi_petty") {
    include_once "revisi_petty.php";
} else if ($page == "transaksi_pettycash") {
    include_once "transaksi_pettycash.php";
} else if ($page == "transaksi_dpettycash") {
    include_once "transaksi_dpettycash.php";
} else if ($page == "kasbon_dproses_sr") {
    include_once "kasbon_dproses_sr.php";
} else if ($page == "po_rtp") {
    include_once "po_rtp.php";
} else if ($page == "po_drtp") {
    include_once "po_drtp.php";
} else if ($page == "detail_pettycash") {
    include_once "detail_pettycash.php";
} else if ($page == "dtl_submitsr") {
    include_once "dtl_submitsr.php";
}
