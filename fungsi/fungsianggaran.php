<?php
include "koneksi.php";

//
//
//
//      Fungsi Untuk Anggaran 
//
//
//

// total anggaran per kode anggaran level 2
function totalAnggaran($id_divisi, $tahun)
{
    global $koneksi;

    $queryTotal = mysqli_query($koneksi, " SELECT sum(jumlah_nominal) as total_anggaran
                                                FROM anggaran 
                                                WHERE id_divisi = '$id_divisi' AND tahun = '$tahun' ");

    $rowTotal = mysqli_fetch_assoc($queryTotal);

    return $rowTotal;
}

// fungsi untuk cek realisasi coa divisi
function realisasiAnggaran($id_divisi, $tahun)
{
    global $koneksi;

    $queryRealisasi = mysqli_query($koneksi, " SELECT SUM(jumlah_realisasi) as jumlah_realisasi
                                                FROM anggaran
                                                WHERE id_divisi = '$id_divisi' AND tahun = '$tahun'");
    $rowR = mysqli_fetch_assoc($queryRealisasi);
    $totalRealisasi = $rowR['jumlah_realisasi'];

    return $totalRealisasi;
}

// fungsi untuk cek realisasi coa divisi
function realisasiCoaDivisi($id_divisi, $noCoa, $tahun)
{
    global $koneksi;

    $queryRealisasi = mysqli_query($koneksi, " SELECT SUM(jumlah_realisasi) as jumlah_realisasi
                                                FROM anggaran
                                                WHERE id_divisi = '$id_divisi' AND no_coa = '$noCoa' AND tahun = '$tahun'
                                                GROUP BY no_coa ");
    $rowR = mysqli_fetch_assoc($queryRealisasi);
    $totalRealisasi = $rowR['jumlah_realisasi'];

    return $totalRealisasi;
}


// total anggaran per coa yang ada di anggaran
function totalAnggaranCoaDivisi($id_divisi, $noCoa, $tahun)
{
    global $koneksi;

    $queryTotal = mysqli_query($koneksi, " SELECT sum(jumlah_nominal) as total_anggaran, nama_coa
                                                FROM anggaran a
                                                JOIN coa c
                                                ON c.no_coa = a.no_coa
                                                WHERE a.id_divisi = '$id_divisi' AND a.no_coa='$noCoa' AND a.tahun = '$tahun' ");

    $rowTotal = mysqli_fetch_assoc($queryTotal);

    return $rowTotal;
}

function dataAnggaran($id_anggaran)
{
    global $koneksi;

    $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_anggaran = '$id_anggaran'");
    $rowAnggaran = mysqli_fetch_assoc($queryAnggaran);

    return $rowAnggaran;
}
