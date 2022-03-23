<?php

include "koneksi.php";
include "koneksipusat.php";

function host()
{
    $srv = $_SERVER['SERVER_NAME'];
    $port = ":" .  $_SERVER['SERVER_PORT'];

    // $host = 'http://' . $srv .  '/sistem/gs-belawan/';
    $host = 'http://' . $srv . '/gs-belawan/';

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

    $split = explode('-', $tanggal);
    $tanggal_indo = $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];

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

function hapusItemMr($id)
{
    global $koneksi;
    $query = mysqli_query($koneksi, "DELETE FROM detail_biayaops WHERE id=$id");
    mysqli_query($koneksi, $query);

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

    $queryNomor = mysqli_query($koneksi, "SELECT MAX(nomor) from bkk_final WHERE MONTH(created_on_bkk)='$bulan' OR MONTH(release_on_bkk)='$bulan' AND YEAR(release_on_bkk) = '$tahun'");

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

    return  ucfirst(str_replace("_", " ", $data));
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

    $hasil = $data['nm_item'] . ' - [' . $data['program_kerja'] . ']';

    return $hasil;
}
