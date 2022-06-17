 
<?php

$page = isset($_GET['p']) ? $_GET['p'] : "";

if ($page == 'formpesan') {
    include_once "formpesan.php";
} else if ($page == "") {
    include_once "main.php";
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
} else if ($page == "lihat_bno") {
    include_once "lihat_bno.php";
} else if ($page == "lihat_detailbno") {
    include_once "lihat_detailbno.php";
} else if ($page == "payment_kasbon") {
    include_once "payment_kasbon.php";
} else if ($page == "payment_dkasbon") {
    include_once "payment_dkasbon.php";
} else if ($page == "verifikasi_kasbonlpj") {
    include_once "verifikasi_kasbonlpj.php";
} else if ($page == "verifikasi_dkasbonlpj") {
    include_once "verifikasi_dkasbonlpj.php";
} else if ($page == "pending_kasbon") {
    include_once "pending_kasbon.php";
} else if ($page == "pending_dkasbon") {
    include_once "pending_dkasbon.php";
} else if ($page == "transaksi_kasbon") {
    include_once "transaksi_kasbon.php";
} else if ($page == "transaksi_dkasbon") {
    include_once "transaksi_dkasbon.php";
} else if ($page == "verifikasi_po") {
    include_once "verifikasi_po.php";
} else if ($page == "verifikasi_dpo") {
    include_once "verifikasi_dpo.php";
} else if ($page == "payment_po") {
    include_once "payment_po.php";
} else if ($page == "payment_dpo") {
    include_once "payment_dpo.php";
} else if ($page == "list_po") {
    include_once "list_po.php";
} else if ($page == "list_dpo") {
    include_once "list_dpo.php";
} else if ($page == "list_dpo_outstanding") {
    include_once "list_dpo_outstanding.php";
} else if ($page == "outstanding_po") {
    include_once "outstanding_po.php";
} else if ($page == "outstanding_dpo") {
    include_once "outstanding_dpo.php";
} else if ($page == "transaksi_po") {
    include_once "transaksi_po.php";
} else if ($page == "transaksi_dpo") {
    include_once "transaksi_dpo.php";
} else if ($page == "laporan_bkk") {
    include_once "laporan-xls.php";
} else if ($page == "biaya_khusus") {
    include_once "biaya_khusus.php";
} else if ($page == "send_paymentkhusus") {
    include_once "send_paymentkhusus.php";
} else if ($page == "transaksi_biayakhusus") {
    include_once "transaksi_biayakhusus.php";
} else if ($page == "transaksi_bkk") {
    include_once "transaksi_bkk.php";
} else if ($page == "proses_bkk") {
    include_once "proses_bkk.php";
} else if ($page == "payment_pettycash") {
    include_once "payment_pettycash.php";
} else if ($page == "payment_dpettycash") {
    include_once "payment_dpettycash.php";
} else if ($page == "pending_pettycash") {
    include_once "pending_pettycash.php";
} else if ($page == "verifikasi_pettylpj") {
    include_once "verifikasi_pettylpj.php";
} else if ($page == "verifikasi_dpettylpj") {
    include_once "verifikasi_dpettylpj.php";
} else if ($page == "transaksi_pettycash") {
    include_once "transaksi_pettycash.php";
} else if ($page == "transaksi_dpettycash") {
    include_once "transaksi_dpettycash.php";
} else if ($page == "rubah_password") {
    include_once "rubah_password.php";
} else if ($page == "payment_dkasbon_user") {
    include_once "payment_dkasbon_user.php";
} else if ($page == "pending_dkasbon_user") {
    include_once "pending_dkasbon_user.php";
} else if ($page == "verifikasi_dkasbonlpj_user") {
    include_once "verifikasi_dkasbonlpj_user.php";
} else if ($page == "ditolak_biayakhusus") {
    include_once "ditolak_biayakhusus.php";
} else if ($page == "dtl_bkditolak") {
    include_once "dtl_bkditolak.php";
} else if ($page == "ditolak_bkk") {
    include_once "ditolak_bkk.php";
} else if ($page == "dtl_bkkditolak") {
    include_once "dtl_bkkditolak.php";
} else if ($page == "biayaumum_tempo") {
    include_once "biayaumum_tempo.php";
} else if ($page == "payment_sr") {
    include_once "payment_sr.php";
} else if ($page == "detail_sr") {
    include_once "detail_sr.php";
} else if ($page == "transaksi_sr") {
    include_once "transaksi_sr.php";
} else if ($page == "detail_pksr") {
    include_once "detail_pksr.php";
} else if ($page == "detail_vksr") {
    include_once "detail_vksr.php";
} else if ($page == "pending_dkasbon_sr") {
    include_once "pending_dkasbon_sr.php";
} else if ($page == "outstanding_cek") {
    include_once "outstanding_cek.php";
} else if ($page == "detail_bkk") {
    include_once "detail_bkk.php";
} else if ($page == "proses_payment") {
    include_once "proses_payment.php";
} else if ($page == "proses_dpayment") {
    include_once "proses_dpayment.php";
} else if ($page == "create_refill") {
    include_once "create_refill.php";
} else if ($page == "refill_show") {
    include_once "refill_show.php";
} else if ($page == "refill_edit") {
    include_once "refill_edit.php";
} else if ($page == "refill_proses") {
    include_once "refill_proses.php";
} else if ($page == "biaya_umum") {
    include_once "biaya_umum.php";
} else if ($page == "biayaumum_create") {
    include_once "biayaumum_create.php";
} else if ($page == "rubah_biayanonops") {
    include_once "rubah_biayanonops.php";
} else if ($page == "detail_biayaumum") {
    include_once "detail_biayaumum.php";
} else if ($page == "refill_transaksi") {
    include_once "refill_transaksi.php";
}
