<div class="table-responsive">
    <table class='table text-center table table-striped table-hover' id='material'>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama PT</th>
                <th>Nama User</th>
                <th>Program Kerja</th>
                <th>Segmen</th>
                <th>No COA</th>
                <th>Nama COA</th>
                <th>Deskripsi Anggaran</th>
                <th>Nominal Budget</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($dataBudget = mysqli_fetch_assoc($queryBudget)) :
            ?>
                <tr>
                    <td> <?= $no; ?> </td>
                    <td> <?= $dataBudget['nm_pt']; ?> </td>
                    <td> <?= $dataBudget['nm_user']; ?> </td>
                    <td> <?= $dataBudget['kd_programkerja'] . " [" . $dataBudget['nm_programkerja']; ?>]</td>
                    <td> <?= $dataBudget['kd_segmen']; ?> </td>
                    <td> <?= $dataBudget['no_coa']; ?> </td>
                    <td> <?= $dataBudget['nm_coa']; ?> </td>
                    <td> <?= $dataBudget['nm_item']; ?> </td>
                    <td><?= formatRupiah2($dataBudget['jumlah_nominal']); ?></td>
                    <td>
                        <!-- <a href="?p=anggaran&aksi=lihat&id=<?= $dataBudget['id_anggaran']; ?>&divisi=<?= $divisi; ?>&tahun=<?= $tahun; ?>"><span data-placement='top' title='Lihat'><button class="btn btn-primary"><i class="fa fa-search-plus"></i></button></span></a> -->
                        <a href="index.php?p=edit_anggaran&id=<?= enkripRambo($dataBudget['id_anggaran']); ?>"><span data-placement='top' title='Rubah'><button class="btn btn-warning"><i class="fa fa-pencil"></i></button></span></a>
                        <a href="hapus_anggaran.php?id=<?= enkripRambo($dataBudget['id_anggaran']); ?>&thn=<?= enkripRambo($tahun); ?>&dvs=<?= enkripRambo($divisi); ?>"><span data-placement='top' title='Hapus' onclick="javascript: return confirm('Anda yakin hapus anggaran <?= $dataBudget['nm_item']; ?>?')"><button class="btn btn-danger" onclick=”return confirm(‘Yakin Hapus?’)”><i class="fa fa-trash"></i></button></span></a>
                    </td>
                </tr>
            <?php
                $no++;
            endwhile;
            ?>
        </tbody>
    </table>
</div>