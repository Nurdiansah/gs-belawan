<?php

include "koneksi.php";
include "koneksipusat.php";

function host()
{
    $ssl = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
    $srv = $_SERVER['SERVER_NAME'];
    $port = ":" .  $_SERVER['SERVER_PORT'];

    // $host = 'http://' . $srv .  '/sistem/gs-belawan/';
    $host = $ssl . $srv . '/gs-belawan/';

    return $host;
}

//
// Tanggal
// 

function bulanArray($index)
{
    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );

    $hasil = $bulan[$index];
    return $hasil;
}

function enkripRambo($data)
{

    return $data = base64_encode(base64_encode(base64_encode($data)));
}

function dekripRambo($data)
{
    return $data = base64_decode(base64_decode(base64_decode($data)));
}

function getHari($hari)
{

    switch ($hari) {
        case 'Sun':
            $hari_ini = "Minggu";
            break;

        case 'Mon':
            $hari_ini = "Senin";
            break;

        case 'Tue':
            $hari_ini = "Selasa";
            break;

        case 'Wed':
            $hari_ini = "Rabu";
            break;

        case 'Thu':
            $hari_ini = "Kamis";
            break;

        case 'Fri':
            $hari_ini = "Jumat";
            break;

        case 'Sat':
            $hari_ini = "Sabtu";
            break;

        default:
            $hari_ini = "Tidak di ketahui";
            break;
    }

    return  $hari_ini;
}

function tanggal_indo($tanggal)
{
    $bulan = array(
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    );

    $tanggal_indo = date('d', strtotime($tanggal)) . ' ' . ($bulan[date('m', strtotime($tanggal))]) . ' ' . date('Y', strtotime($tanggal));

    // $split = explode('-', $tanggal);
    // $tanggal_indo = $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];

    return $tanggal_indo;
}

function formatTanggal($tanggal)
{
    $formatTanggal = date("d F Y", strtotime($tanggal));

    return $formatTanggal;
}

function formatTanggalHari($tanggal)
{
    $hari = date("D", strtotime($tanggal));
    $hari = getHari($hari);

    $formatTanggal = $hari . ', ' . date("d/m/Y", strtotime($tanggal));

    return $formatTanggal;
}

function formatTanggalWaktu($tanggal)
{
    $formatTanggal = date("d F Y H:i", strtotime($tanggal));

    return $formatTanggal;
}

// Terbilang
function Terbilang($nilai)
{
    $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    if ($nilai == 0) {
        return "";
    } elseif ($nilai < 12 & $nilai != 0) {
        return "" . $huruf[$nilai];
    } elseif ($nilai < 20) {
        return Terbilang($nilai - 10) . " Belas ";
    } elseif ($nilai < 100) {
        return Terbilang($nilai / 10) . " Puluh " . Terbilang($nilai % 10);
    } elseif ($nilai < 200) {
        return " Seratus " . Terbilang($nilai - 100);
    } elseif ($nilai < 1000) {
        return Terbilang($nilai / 100) . " Ratus " . Terbilang($nilai % 100);
    } elseif ($nilai < 2000) {
        return " Seribu " . Terbilang($nilai - 1000);
    } elseif ($nilai < 1000000) {
        return Terbilang($nilai / 1000) . " Ribu " . Terbilang($nilai % 1000);
    } elseif ($nilai < 1000000000) {
        return Terbilang($nilai / 1000000) . " Juta " . Terbilang($nilai % 1000000);
    } elseif ($nilai < 1000000000000) {
        return Terbilang($nilai / 1000000000) . " Milyar " . Terbilang($nilai % 1000000000);
    } elseif ($nilai < 100000000000000) {
        return Terbilang($nilai / 1000000000000) . " Trilyun " . Terbilang($nilai % 1000000000000);
    } elseif ($nilai <= 100000000000000) {
        return "Maaf Tidak Dapat di Prose Karena Jumlah nilai Terlalu Besar ";
    }
}
// Akhir terbilang

function getEmailAddress($id)
{
    global $koneksi;

    $data =  mysqli_query($koneksi, "SELECT email FROM user WHERE id_user  = $id");

    $return = mysqli_fetch_assoc($data);

    return $return['email'];
}

function getUserName($id)
{
    global $koneksi;

    $data =  mysqli_query($koneksi, "SELECT nama FROM user WHERE id_user  = $id");

    $return = mysqli_fetch_assoc($data);

    return $return['nama'];
}

// Get romawi
function getRomawi($bln)
{
    switch ($bln) {
        case 1:
            return "I";
            break;
        case 2:
            return "II";
            break;
        case 3:
            return "III";
            break;
        case 4:
            return "IV";
            break;
        case 5:
            return "V";
            break;
        case 6:
            return "VI";
            break;
        case 7:
            return "VII";
            break;
        case 8:
            return "VIII";
            break;
        case 9:
            return "IX";
            break;
        case 10:
            return "X";
            break;
        case 11:
            return "XI";
            break;
        case 12:
            return "XII";
            break;
    }
}

// Get not romawi
function getNotRomawi($bln)
{
    switch ($bln) {
        case "I":
            return "Januari";
            break;
        case "II":
            return "Februari";
            break;
        case "III":
            return "Maret";
            break;
        case "IV":
            return "April";
            break;
        case "V":
            return "Mei";
            break;
        case "VI":
            return "Juni";
            break;
        case "VII":
            return "Juli";
            break;
        case "VIII":
            return "Agustus";
            break;
        case "IX":
            return "September";
            break;
        case "X":
            return "Oktober";
            break;
        case "XI":
            return "November";
            break;
        case "XII":
            return "Desember";
            break;
    }
}

function getNumberRomawi($bln)
{
    switch ($bln) {
        case "I":
            return "01";
            break;
        case "II":
            return "02";
            break;
        case "III":
            return "03";
            break;
        case "IV":
            return "04";
            break;
        case "V":
            return "05";
            break;
        case "VI":
            return "06";
            break;
        case "VII":
            return "07";
            break;
        case "VIII":
            return "08";
            break;
        case "IX":
            return "09";
            break;
        case "X":
            return "10";
            break;
        case "XI":
            return "11";
            break;
        case "XII":
            return "12";
            break;
    }
}

function fieldRealisasi($bln)
{
    switch ($bln) {
        case 1:
            return "januari_realisasi";
            break;
        case 2:
            return "februari_realisasi";
            break;
        case 3:
            return "maret_realisasi";
            break;
        case 4:
            return "april_realisasi";
            break;
        case 5:
            return "mei_realisasi";
            break;
        case 6:
            return "juni_realisasi";
            break;
        case 7:
            return "juli_realisasi";
            break;
        case 8:
            return "agustus_realisasi";
            break;
        case 9:
            return "september_realisasi";
            break;
        case 10:
            return "oktober_realisasi";
            break;
        case 11:
            return "november_realisasi";
            break;
        case 12:
            return "desember_realisasi";
            break;
    }
}

function hapusItemMr($id, $foto)
{
    global $koneksi;
    $query = mysqli_multi_query($koneksi, "DELETE FROM detail_biayaops WHERE id = '$id';
                                            DELETE FROM sub_dbo WHERE id_dbo = '$id';
                                ");

    unlink("../file/foto/$foto");

    if ($query) {
        header("location:index.php?p=buat_mr");
    } else {
        echo 'gagal';
    }
}

function dateNow()
{
    date_default_timezone_set('Asia/Jakarta');

    return date("Y-m-d H:i:s");
}

// insert queue email
function createQueueEmail($name, $email, $subject, $body)
{
    global $koneksi;
    $tanggal = dateNow();
    // $isi = mysqli_real_escape_string($body);
    return mysqli_query($koneksi, "INSERT INTO queue_email (name_email, address_email, subject_email, body, created_at, updated_at) VALUES
                                                       ('$name', '$email','$subject', '$body', '$tanggal', '$tanggal'); ");
}

// insert queue email tempo
function createQueueEmailTempo($name, $email, $subject, $body, $tanggal_tempo)
{
    global $koneksi;
    $tanggal = dateNow();
    // $isi = mysqli_real_escape_string($body);
    return mysqli_query($koneksi, "INSERT INTO queue_email_tempo (name_email, address_email, subject_email, body, tanggal_tempo, created_at, updated_at) VALUES
                                                            ('$name', '$email', '$subject', '$body', '$tanggal_tempo', '$tanggal', '$tanggal'); ");
}

function batasiKata($text, $limit = 50)
{
    if (strlen($text) > $limit) {
        $word = mb_substr($text, 0, $limit - 3) . ".....";
    } else {
        $word = $text;
    }
    return $word;
}

function formatRupiah($data)
{
    if (strpos($data, '.') !== false) {
        // echo 'ada titik';
        $rupiah = 'Rp. ' . number_format($data, 2, ",", ".");
    } else {
        // echo 'tidak ada titik';
        $rupiah = 'Rp. ' . number_format($data, 0, ",", ".");
    }
    return $rupiah;
}

function formatRupiah2($data)
{
    if (strpos($data, '.') !== false) {
        // echo 'ada titik';
        $rupiah = number_format($data, 2, ",", ".");
    } else {
        // echo 'tidak ada titik';
        $rupiah = number_format($data, 0, ",", ".");
    }
    return $rupiah;
}

function formatRibuan($data)
{

    // echo 'tidak ada titik';
    $rupiah = number_format($data, 0, ",", ".");

    return $rupiah;
}

function penghilangTitik($nilai)
{
    $nilai = str_replace(".", "", $nilai);

    return $nilai;
}

function kurungMinus($nilai)
{
    $minus = substr($nilai, 0, 1);

    return $nilai = $minus == "-" ? "(" . formatRupiah2(substr($nilai, 1)) . ")" : formatRupiah2($nilai);
}

function tahunSekarang()
{
    $tahun = date('Y');

    return $tahun;
}

function dataUser($username)
{
    global $koneksi;
    $dataUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = $username");

    $dataUser = mysqli_fetch_assoc($dataUser);

    return $dataUser;
}

function detikToString($detik)
{
    $hari = floor($detik / 86400);
    $modHari = $detik % 86400;
    $jam = floor($modHari / 3600);
    $mod = $detik % 3600;
    $menit = floor($mod / 60);
    if ($hari > 0) {
        #jika lebih dari 1 hari
        $hasil = $hari . ' hari ' . $jam . ' jam ' . $menit . ' menit';
    } else if ($jam > 0) {
        # Jika lebih dari 1 jam
        $hasil = $jam . ' jam ' . $menit . ' menit';
    } else {
        # Jika kurang dari 1 jam
        $hasil = $menit . ' menit';
    }

    return $hasil;
}

function bodyEmail($nama, $id, $link)
{
    echo "Kepada Yth,
    Bapak/Ibu Lastono
    
    Auditor/Pengamat atas nama Suyamto telah membuat Aspek Pengamatan Perilaku dengan Nomor Laporan : PINTER2106170006.
    Berikut URL untuk melihat Aspek Pengamatan Perilaku yang sudah dibuat:
    
    Nomor Laporan : PINTER2106170006
    URL : http://103.21.206.170/hse/trx0200_view.php?id=5633&site=4&nctype=3
    
    
    ";
}

function getNoBkk()
{
    global $koneksi;

    $bulan    = date('n');
    $romawi    = getRomawi($bulan);
    $tahun     = date('Y');
    $nomor     = "/GS-GK/" . $romawi . "/" . $tahun;

    $queryNomor = mysqli_query($koneksi, "SELECT MAX(nomor) from bkk_final WHERE month(release_on_bkk)='$bulan' ");

    $nomorMax = mysqli_fetch_array($queryNomor);
    if ($nomorMax) {

        $nilaikode = substr($nomorMax[0], 0);
        $kode = (int) $nilaikode;

        //setiap kode ditambah 1
        $kode = $kode + 1;
        $nomorAkhir = "" . str_pad($kode, 3, "0", STR_PAD_LEFT);
    } else {
        $nomorAkhir = "001";
    }

    $nomorBkk = $nomorAkhir . $nomor;

    return $nomorBkk;
}

function getNomorBkk($data)
{
    global $koneksi;

    $bulan    = $data;
    $romawi    = getRomawi($bulan);
    $tahun     = date('Y');
    $nomor     = "/GS-GK/" . $romawi . "/" . $tahun;

    $queryNomor = mysqli_query($koneksi, "SELECT MAX(nomor) from bkk_final WHERE month(created_on_bkk)='$bulan' OR month(release_on_bkk)='$bulan' ");

    $nomorMax = mysqli_fetch_array($queryNomor);
    if ($nomorMax) {

        $nilaikode = substr($nomorMax[0], 0);
        $kode = (int) $nilaikode;

        //setiap kode ditambah 1
        $kode = $kode + 1;
        $nomorAkhir = "" . str_pad($kode, 3, "0", STR_PAD_LEFT);
    } else {
        $nomorAkhir = "001";
    }

    $nomorBkk = $nomorAkhir . $nomor;

    return $nomorBkk;
}


function nomorBkkNew($tanggal)
{
    global $koneksi;

    $bulan    = date('n', strtotime($tanggal));
    $romawi    = getRomawi($bulan);
    $tahun     = date('Y', strtotime($tanggal));
    $nomor     = "/GS-GK/" . $romawi . "/" . $tahun;

    $queryNomor = mysqli_query($koneksi, "SELECT MAX(nomor) from bkk_final WHERE MONTH(release_on_bkk)='$bulan' AND YEAR(release_on_bkk) = '$tahun'");

    $nomorMax = mysqli_fetch_array($queryNomor);
    if ($nomorMax) {

        $nilaikode = substr($nomorMax[0], 0);
        $kode = (int) $nilaikode;

        //setiap kode ditambah 1
        $kode = $kode + 1;
        $nomorAkhir = "" . str_pad($kode, 3, "0", STR_PAD_LEFT);
    } else {
        $nomorAkhir = "001";
    }

    $nomorBkk = $nomorAkhir . $nomor;

    return $nomorBkk;
}

function manipulasiTanggal($tgl, $jumlah = 1, $format = 'days')
{
    $currentDate = $tgl;
    return date("Y-m-d H:i:s", strtotime($jumlah . ' ' . $format, strtotime($currentDate)));
}


function datetimeHtml($data)
{
    $result = str_replace("T", " ", $data);

    return $result;
}

function convertDatetimeLocal($data)
{
    $result = str_replace(" ", "T", $data);

    return $result;
}


function kataJenis($data)
{

    return ucwords(str_replace("_", " ", $data));
}

function orderNumber($data)
{

    return sprintf("%04d", $data);
}
function addressBuktiPembayaranBU($kd_transaksi)
{
    global $koneksi;

    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM bkk_ke_pusat WHERE pengajuan = 'BIAYA UMUM' AND id_kdtransaksi = '$kd_transaksi' "));
    $buktiPembayaran = $data['bukti_pembayaran'];

    return "<iframe class='embed-responsive-item' src='../../gs-system/file/bukti_pembayaran/" . $buktiPembayaran . "'></iframe>";
}

function kodeProgramKerja($id_anggaran)
{
    global $koneksi;

    $query =  mysqli_query($koneksi, "SELECT *, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_item
                                        FROM anggaran a
                                        LEFT JOIN program_kerja
                                            ON programkerja_id = id_programkerja
                                        JOIN cost_center cc
                                            ON costcenter_id = id_costcenter
                                        JOIN pt pt
                                            ON pt_id = id_pt
                                        JOIN divisi dvs
                                            ON divisi_id = dvs.id_divisi
                                        JOIN parent_divisi pd
                                            ON parent_id = id_parent
                                        -- end buat ambil PK
                                        WHERE a.id_anggaran = '$id_anggaran'  ");

    $data = mysqli_fetch_assoc($query);

    $hasil = '[' . $data['program_kerja'] . ']';

    return $hasil;
}

function nomorAwal($data)
{
    $nomorAwal = substr($data, 0, 3);

    return $nomorAwal;
}

function kodeAnggaran($id_anggaran)
{
    global $koneksi;

    $query =  mysqli_query($koneksi, "SELECT *, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_item
                                        FROM anggaran a
                                        LEFT JOIN program_kerja
                                            ON programkerja_id = id_programkerja
                                        JOIN cost_center cc
                                            ON costcenter_id = id_costcenter
                                        JOIN pt pt
                                            ON pt_id = id_pt
                                        JOIN divisi dvs
                                            ON divisi_id = dvs.id_divisi
                                        JOIN parent_divisi pd
                                            ON parent_id = id_parent
                                        -- end buat ambil PK
                                        WHERE a.id_anggaran = '$id_anggaran'  ");

    $data = mysqli_fetch_assoc($query);

    // jika dibawah tahun 2023 maka nampil foramt 2022
    if ($data['tahun'] < "2023") {
        $hasil = $data['nm_item'] . ' - [' . $data['program_kerja'] . ']';
    } else {
        $hasil = $data['kd_anggaran'] . ' [' . $data['nm_item'] . ']';
    }

    return $hasil;
}

function warnaSurplus($budget, $realisasi, $pra_nota)
{
    $warna = $budget < ($realisasi + $pra_nota) ? "style='color: red;'" : "";

    return $warna;
}

function kurungSurplus($nominal, $realisasi)
{
    $total = $nominal - $realisasi;
    $kurung = $nominal > $realisasi ?  formatRupiah($total) : "(" . formatRupiah($total) . ")";

    return $kurung;
}

function kurungSurplus2($nominal, $realisasi)
{
    $total = $nominal - $realisasi;
    $kurung = $nominal > $realisasi ?  formatRupiah2($total) : "(" . formatRupiah2($total) . ")";

    return $kurung;
}

function nomorBKM($tanggal, $divisi)
{
    global $koneksi;

    $bulan    = date('n', strtotime($tanggal));
    $romawi    = getRomawi($bulan);
    $tahun     = date('Y', strtotime($tanggal));
    $nomor     = "/GS-GM/" . $romawi . "/" . $tahun;

    // nomor BKM didivisi billing berbeda dengan divisi lain, permintaan Pak Amin kasir GS Belawan : 20231122
    // jadi klo billing : 001, 002, 003, dst
    // kasir : 001, 002, 003
    // divisi accounting (lanjut) : 004, 005
    // divisi pajak (lanjut) : 006, 007, dst
    if ($divisi == "3") {
        $queryNomor = mysqli_query($koneksi, "SELECT MAX(nomor) FROM bkm WHERE MONTH(tgl_bkm) = '$bulan' AND YEAR(tgl_bkm) = '$tahun' AND id_divisi = '3'");
    } else {
        $queryNomor = mysqli_query($koneksi, "SELECT MAX(nomor) FROM bkm WHERE MONTH(tgl_bkm) = '$bulan' AND YEAR(tgl_bkm) = '$tahun' AND id_divisi <> '3'");
    }

    $nomorMax = mysqli_fetch_array($queryNomor);
    if ($nomorMax) {

        $nilaikode = substr($nomorMax[0], 0);
        $kode = (int) $nilaikode;

        //setiap kode ditambah 1
        $kode = $kode + 1;
        $nomorAkhir = "" . str_pad($kode, 3, "0", STR_PAD_LEFT);
    } else {
        $nomorAkhir = "001";
    }

    $nomorBKM = $nomorAkhir . $nomor;

    return $nomorBKM;
    // return $nomorAkhir;
}

function bulanLoop()
{
    $bulan = array(
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    );

    return $bulan;
}

$nm_bln = array(
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember',
);

function cekPersenNew($realisasi, $anggaran)
{
    if ($realisasi > 0 && $anggaran == 0) {
        $hasil = 0;
    } else if ($realisasi == 0 && $anggaran == 0) {
        $hasil = 0;
    } else {
        $hasil = round($realisasi / $anggaran * 100);
    }

    return $hasil;
}

function insRealisasiSem($pengajuan, $id_kdtransaksi, $id_anggaran, $nominal)
{
    global $koneksi;

    $insert = mysqli_query($koneksi, "INSERT INTO realisasi_sementara (pengajuan, id_kdtransaksi, id_anggaran, nominal, created_at, created_by) VALUES
                                                                      ('$pengajuan', '$id_kdtransaksi', '$id_anggaran', '$nominal', NOW() ,'System');");

    return $insert;
}

function UpdRealisasiSem($id_kdtransaksi, $pengajuan, $nominal, $delete)
{
    global $koneksi;

    $realSem = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM realisasi_sementara WHERE pengajuan = '$pengajuan' AND id_kdtransaksi = '$id_kdtransaksi' "));

    if ($realSem > 0) {

        if ($pengajuan == 'PO') {
            # code...
            $updReaSem = mysqli_query($koneksi, "UPDATE realisasi_sementara SET nominal = nominal - $nominal, is_deleted = '$delete', updated_at = NOW() WHERE pengajuan = '$pengajuan' AND id_kdtransaksi = '$id_kdtransaksi' ");
        } else {
            $updReaSem = mysqli_query($koneksi, "UPDATE realisasi_sementara SET nominal = 0, is_deleted = '1', updated_at = NOW() WHERE pengajuan = '$pengajuan' AND id_kdtransaksi = '$id_kdtransaksi' ");
        }
    } else {
        $updReaSem = 'Berhasil';
    }

    return $updReaSem;
}

function DelRealisasiSem($id_kdtransaksi, $pengajuan)
{
    global $koneksi;

    $delRealSem = mysqli_query($koneksi, "DELETE FROM realisasi_sementara WHERE id_kdtransaksi = '$id_kdtransaksi' AND pengajuan = '$pengajuan'");

    return $delRealSem;
}

function pathPusat()
{
    $path = '../../gs-system/file/';
    return $path;
}

function pathBelawan()
{
    $path = '../file/';
    return $path;
}

function nomorKasbon()
{
    global $koneksi;

    $tahun = date("y");

    $queryHight = mysqli_query($koneksi, "SELECT MAX(id_kasbon)
                                            FROM kasbon
                                            WHERE DATE_FORMAT(tgl_kasbon, '%y') = '$tahun'
                                            AND SUBSTRING(id_kasbon, 3, 2) = '$tahun'");

    $max_kode = mysqli_fetch_array($queryHight);


    if ($max_kode) {

        $nilaikode = substr($max_kode[0], 5);
        $kode = (int) $nilaikode;

        //setiap kode ditambah 1
        $kode = $kode + 1;
        $kode = "KS" . $tahun . "-" . str_pad($kode, 4, "0", STR_PAD_LEFT);
    } else {
        $kode = "KS" . $tahun . "-0001";
    }

    return $kode;
}

function nomorBiayaUmum()
{
    global $koneksi;

    $tahun = date("y");

    $queryHight = mysqli_query($koneksi, "SELECT MAX(kd_transaksi)
                                            FROM bkk
                                            WHERE DATE_FORMAT(tgl_pengajuan, '%y') = '$tahun'
                                            AND SUBSTRING(kd_transaksi, 3, 2) = '$tahun'");

    $max_kode = mysqli_fetch_array($queryHight);


    if ($max_kode) {

        $nilaikode = substr($max_kode[0], 5);
        $kode = (int) $nilaikode;

        //setiap kode ditambah 1
        $kode = $kode + 1;
        $kode = "BU" . $tahun .  "-" . str_pad($kode, 4, "0", STR_PAD_LEFT);
    } else {
        $kode = "BU" . $tahun . "-0001";
    }

    return $kode;
}

function nomorMR()
{
    global $koneksi;

    $tahun = date("y");

    $queryHight = mysqli_query($koneksi, "SELECT MAX(kd_transaksi)
                                            FROM biaya_ops
                                            WHERE DATE_FORMAT(tgl_pengajuan, '%y') = '$tahun'
                                            AND SUBSTRING(kd_transaksi, 3, 2) = '$tahun'");

    $max_kode = mysqli_fetch_array($queryHight);


    if ($max_kode) {

        $nilaikode = substr($max_kode[0], 5);
        $kode = (int) $nilaikode;

        //setiap kode ditambah 1
        $kode = $kode + 1;
        $kode = "MR" . $tahun . "-" . str_pad($kode, 4, "0", STR_PAD_LEFT);
    } else {
        $kode = "MR" . $tahun . "-0001";
    }

    return $kode;
}

function nomorPettycash()
{
    global $koneksi;

    $tahun = date("y");

    $queryHight = mysqli_query($koneksi, "SELECT MAX(kd_pettycash)
                                            FROM transaksi_pettycash
                                            WHERE DATE_FORMAT(created_pettycash_on, '%y') = '$tahun'
                                            AND SUBSTRING(kd_pettycash, 3, 2) = '$tahun'");

    $max_kode = mysqli_fetch_array($queryHight);


    if ($max_kode) {

        $nilaikode = substr($max_kode[0], 5);
        $kode = (int) $nilaikode;

        //setiap kode ditambah 1
        $kode = $kode + 1;
        $kode = "PC" . $tahun . "-" .  str_pad($kode, 4, "0", STR_PAD_LEFT);
    } else {
        $kode = "PC" . $tahun . "-0001";
    }

    return $kode;
}
