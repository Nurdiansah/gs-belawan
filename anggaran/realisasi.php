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
                <th>Nominal Realisasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($dataRealisasi = mysqli_fetch_assoc($queryRealisasi)) :
            ?>
                <tr>
                    <td> <?= $no; ?> </td>
                    <td> <?= $dataRealisasi['nm_pt']; ?> </td>
                    <td> <?= $dataRealisasi['nm_user']; ?> </td>
                    <td> <?= $dataRealisasi['kd_programkerja'] . " [" . $dataRealisasi['nm_programkerja']; ?>]</td>
                    <td> <?= $dataRealisasi['kd_segmen']; ?> </td>
                    <td> <?= $dataRealisasi['no_coa']; ?> </td>
                    <td> <?= $dataRealisasi['nm_coa']; ?> </td>
                    <td> <?= $dataRealisasi['nm_item']; ?> </td>
                    <td><?= formatRupiah2($dataRealisasi['jumlah_realisasi']); ?></td>
                    <td>
                        <a href="index.php?p=edit_realisasi&id=<?= enkripRambo($dataRealisasi['id_anggaran']); ?>"><span data-placement='top' title='Rubah'><button class="btn btn-primary"><i class="fa fa-pencil"></i></button></span></a>
                    </td>
                </tr>
            <?php
                $no++;
            endwhile;
            ?>
        </tbody>
    </table>
</div>