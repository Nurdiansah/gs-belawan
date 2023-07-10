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

function bulanRealisasi($bln)
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

// fungsi untuk update realisasi anggaran
function updateRealisasi($id_anggaran, $qty, $DPP, $bulan)
{
    global $koneksi;

    $fieldRealisasi = bulanRealisasi($bulan);
    $queryJumlahAwal = mysqli_query($koneksi, "SELECT $fieldRealisasi as bulan , jumlah_realisasi, realisasi_kuantitas  from anggaran WHERE id_anggaran = '$id_anggaran' ");
    $rowJA = mysqli_fetch_assoc($queryJumlahAwal);
    $jml_akhir = $rowJA['bulan'] + $DPP;
    $jumlah_realisasi = $rowJA['jumlah_realisasi'] + $DPP;
    $qty_akhir = $rowJA['realisasi_kuantitas'] + $qty;


    $realisasi = mysqli_query($koneksi, "UPDATE anggaran SET $fieldRealisasi = '$jml_akhir' , jumlah_realisasi = $jumlah_realisasi ,realisasi_kuantitas = $qty_akhir
												WHERE id_anggaran ='$id_anggaran' ");
    return $realisasi;
}


function getSaldoAnggaran($id_anggaran)
{
    global $koneksi;

    $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_anggaran = '$id_anggaran'");
    $rowAnggaran = mysqli_fetch_assoc($queryAnggaran);
    $programKerjaID = $rowAnggaran['programkerja_id'];

    // cek nominal dari realisasi sementara
    $dRSem = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_anggaran, SUM(nominal) AS jumlah_rsem
                                                        FROM realisasi_sementara
                                                        WHERE id_anggaran = '$id_anggaran'
                                                        AND is_deleted = '0'
                                    "));
    $jumlahRsem = $dRSem['jumlah_rsem'];

    // cek jumlah nominal dari tabel anggatan per programkerja tertentu
    $dProgramKerja = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(januari_nominal) + SUM(februari_nominal) + SUM(maret_nominal) + SUM(april_nominal) + SUM(mei_nominal) + SUM(juni_nominal) + SUM(juli_nominal) + SUM(agustus_nominal) + SUM(september_nominal) + SUM(oktober_nominal) + SUM(november_nominal) + SUM(desember_nominal) AS jumlah_nominal,
                                                                    SUM(januari_realisasi) + SUM(februari_realisasi) + SUM(maret_realisasi) + SUM(april_realisasi) + SUM(mei_realisasi) + SUM(juni_realisasi) + SUM(juli_realisasi) + SUM(agustus_realisasi) + SUM(september_realisasi) + SUM(oktober_realisasi) + SUM(november_realisasi) + SUM(desember_realisasi) AS jumlah_realisasi
                                                                FROM anggaran
                                                                WHERE programkerja_id = '$programKerjaID'
                                                                GROUP BY programkerja_id
                                                    "));

    $jumlahNominal = $dProgramKerja['jumlah_nominal'];
    $jumlahRealisasi = $dProgramKerja['jumlah_realisasi'];

    // kalo bukan 2022 ke bawah realiasi di nol kan 
    if ($rowAnggaran['tahun'] < 2023) {
        $jumlahRealisasi = 0;
    }

    if ($rowAnggaran['unlock'] == "1") {
        $jumlahRealisasi = 0;
    }

    $jumlahAggPk = ($jumlahNominal - $jumlahRealisasi) - $jumlahRsem;

    return $jumlahAggPk;
}
