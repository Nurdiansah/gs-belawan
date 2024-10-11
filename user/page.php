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
    } else if ($page == "list_order") {
        include_once "list_order.php";
    } else if ($page == "verifikasi_dmr") {
        include_once "verifikasi_dmr.php";
    } else if ($page == "kasbon_proses") {
        include_once "kasbon_proses.php";
    } else if ($page == "kasbon_dproses") {
        include_once "kasbon_dproses.php";
    } else if ($page == "kasbon_transaksi") {
        include_once "kasbon_transaksi.php";
    } else if ($page == "kasbon_dtransaksi") {
        include_once "kasbon_dtransaksi.php";
    } else if ($page == "po_proses") {
        include_once "po_proses.php";
    } else if ($page == "po_dproses") {
        include_once "po_dproses.php";
    } else if ($page == "transaksi_po") {
        include_once "transaksi_po.php";
    } else if ($page == "transaksi_dpo") {
        include_once "transaksi_dpo.php";
    } else if ($page == "hapus_sdbo") {
        include_once "hapus_sdbo.php";
    } else if ($page == "hapus_sdboedit") {
        include_once "hapus_sdboedit.php";
    } else if ($page == "revisi_mr") {
        include_once "revisi_mr.php";
    } else if ($page == "buat_petty") {
        include_once "buat_petty.php";
    } else if ($page == "revisi_petty") {
        include_once "revisi_petty.php";
    } else if ($page == "lpj_petty") {
        include_once "lpj_petty.php";
    } else if ($page == "transaksi_pettycash") {
        include_once "transaksi_pettycash.php";
    } else if ($page == "transaksi_dpettycash") {
        include_once "transaksi_dpettycash.php";
    } else if ($page == "rubah_password") {
        include_once "rubah_password.php";
    } else if ($page == "release_petty") {
        include_once "release_petty.php";
    } else if ($page == "buat_kasbon") {
        include_once "buat_kasbon.php";
    } else if ($page == "kasbon_dproses_user") {
        include_once "kasbon_dproses_user.php";
    } else if ($page == "rubah_biayanonops") {
        include_once "rubah_biayanonops.php";
    } else if ($page == "ditolak_kasbon") {
        include_once "ditolak_kasbon.php";
    } else if ($page == "dtl_kasbonditolak") {
        include_once "dtl_kasbonditolak.php";
    } else if ($page == "ditolak_po") {
        include_once "ditolak_po.php";
    } else if ($page == "dtl_ditolakpo") {
        include_once "dtl_ditolakpo.php";
    } else if ($page == "laporan_anggaran") {
        include_once "laporan_anggaran.php";
    } else if ($page == "buat_sr") {
        include_once "buat_sr.php";
    } else if ($page == "edit_sr") {
        include_once "edit_sr.php";
    } else if ($page == "ditolak_sr") {
        include_once "ditolak_sr.php";
    } else if ($page == "vk_sr") {
        include_once "vk_sr.php";
    } else if ($page == "proses_sr") {
        include_once "proses_sr.php";
    } else if ($page == "edit_so") {
        include_once "edit_so.php";
    } else if ($page == "transaksi_sr") {
        include_once "transaksi_sr.php";
    } else if ($page == "proses_petty") {
        include_once "proses_petty.php";
    } else if ($page == "detail_dmr") {
        include_once "detail_dmr.php";
    } else if ($page == "buat_bkm") {
        include_once "buat_bkm.php";
    } else if ($page == "proses_bkm") {
        include_once "proses_bkm.php";
    } else if ($page == "ditolak_bkm") {
        include_once "ditolak_bkm.php";
    } else if ($page == "transaksi_bkm") {
        include_once "transaksi_bkm.php";
    } else if ($page == "laporan_programkerja") {
        include_once "laporan_programkerja.php";
    } else if ($page == "dtl_laporanpk") {
        include_once "dtl_laporanpk.php";
    } else if ($page == "dtl_pettycash") {
        include_once "dtl_pettycash.php";
    } else if ($page == "kasbon_detail") {
        include_once "kasbon_detail.php";
    } else if ($page == "laporan_bkk") {
        include_once "laporan_bkk.php";
    } else if ($page == "laporan_pettycash") {
        include_once "laporan_pettycash.php";
    } else if ($page == "detail_bkk") {
        include_once "detail_bkk.php";
    } else if ($page == "detail_pettycash") {
        include_once "detail_pettycash.php";
    } else if ($page == "rekening_bank") {
        include_once "rekening_bank.php";
    }
