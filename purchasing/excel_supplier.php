<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$query = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id_supplier <> '0' ORDER BY nm_supplier ASC");

$total = mysqli_num_rows($query);

if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != "purchasing") {
    echo "<script>window.alert('Engga bisa cetak, login dulu!');
						location='../index.php'
					</script>";
} elseif ($total > 0) {
    // fungsi header dengan mengirimkan raw data excel
    header("Content-type: application/vnd-ms-excel");

    // membuat nama file ekspor "export-to-excel.xls"
    header("Content-Disposition: attachment; filename=supplier.xls");

    $no = 1;
?>

    <table border="1">
        <tr>
            <th>No</th>
            <th>Nama Supplier</th>
            <th>Nama PIC</th>
            <th>No Telpon</th>
            <th>No Fax</th>
            <th>Email</th>
            <th>Kategori</th>
            <th>Alamat</th>
        </tr>
        <?php while ($data = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $data['nm_supplier']; ?></td>
                <td><?= $data['pic_supplier']; ?></td>
                <td>'<?= $data['no_telponsupplier']; ?></td>
                <td>'<?= $data['no_faxsupplier']; ?></td>
                <td><?= $data['email_supplier']; ?></td>
                <td><?= $data['kategori_supplier']; ?></td>
                <td><?= $data['alamat_supplier']; ?></td>
            </tr>
        <?php $no++;
        } ?>
    </table>

<?php
} else {
    echo "<script>alert('Data yang ingin dicetak tidak ada!');
        location='index.php?p=supplier'
    </script>";;
}
