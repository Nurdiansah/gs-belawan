<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];


$query =  mysqli_query($koneksi, "SELECT * FROM bkk_final bf                                                                                       
                                            JOIN po p
                                            ON p.id_po = bf.id_kdtransaksi
                                            JOIN biaya_ops bo 
                                            ON p.kd_transaksi = bo.kd_transaksi
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi 
                                            JOIN detail_biayaops dbo
                                            ON p.id_dbo = dbo.id
                                            JOIN supplier s
                                            ON s.id_supplier = dbo.id_supplier
                                            JOIN anggaran a
                                            ON dbo.id_anggaran = a.id_anggaran
                                            JOIN pph pp
                                            ON p.id_pph = pp.id_pph
                                            WHERE bf.id ='$id' ");
$data2 = mysqli_fetch_assoc($query);
$id_po = $data2['id_po'];
$id_supplier = $data2['id_supplier'];
$id_anggaran = $data2['id_anggaran'];
$totalPengajuan = $data2['total_po'];
$totalPersen = $data2['persentase_pembayaran1'] + $data2['persentase_pembayaran2'];

if ($data2['nominal_pembayaran2'] == 0) {
    $nominalPembayaran = $data2['nominal_pembayaran1'];
    $pembayaranKe = '1';
    $persen = $data2['persentase_pembayaran1'];
} else {
    $nominalPembayaran = $data2['nominal_pembayaran2'];
    $pembayaranKe = '2';
    $persen = $data2['persentase_pembayaran2'];
}


$id_dbo = $data2['id_dbo'];

$querybf =  mysqli_query($koneksi, "SELECT * FROM bkk_final
                                            WHERE id='$id' ");
$bf = mysqli_fetch_assoc($querybf);


$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo");


?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <!-- <div class="col-md-2">
                            <a href="index.php?p=list_mr" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div> -->
                    <br><br>
                </div>

                <!-- Detail Job Order -->
                <div class="col-sm-offset-10">
                    <a target="_blank" href="cetak_bkkfinal.php?id=<?= $bf['id']; ?>" class="btn btn-success"><i class="fa fa-print"></i> BKK </a>
                    <!-- <a target="_blank" href="cetak_po.php?id=<?= $id_po; ?>" class="btn btn-success "><i class="fa fa-print"></i> PO </a>                                                                                                 -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#payment"> Payment </button></span></a>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Payment PO</h3>
                </div>
                <form method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['nm_divisi'];  ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Pembayaran <?= $pembayaranKe ?></label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $persen; ?>">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatTanggal($data2['tgl_pengajuan']); ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Nominal Pembayaran <?= $pembayaranKe ?></label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatRupiah($nominalPembayaran); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-1 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data2['keterangan']; ?></textarea>
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">PO Number</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['po_number']; ?>">
                            </div>
                        </div>
                        <br>
                    </div>
                </form>

                <!--  -->
                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-dark table-hover ">
                        <thead style="background-color :#B0C4DE;">
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Kode Anggaran</th>
                            <th>Merk</th>
                            <th>Supplier/Vendor</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                        </thead>
                        <tr>
                            <tbody>
                                <tr>
                                    <?php
                                    // $no =1 ;
                                    // if (mysqli_num_rows($queryBo)) {
                                    //     while($row=mysqli_fetch_assoc($queryBo)):                                                    

                                    ?>
                                    <td> 1 </td>
                                    <td> <?= $data2['nm_barang']; ?> </td>
                                    <td> <?= $data2['kd_anggaran'] . ' ' . $data2['nm_item']; ?> </td>
                                    <td> <?= $data2['merk']; ?> </td>
                                    <td> <?= $data2['nm_supplier']; ?> </td>
                                    <td> <?= $data2['satuan']; ?> </td>
                                    <td> <?= $data2['jumlah']; ?> </td>
                                    <td>Rp. <?= number_format($data2['grand_totalpo'], 0, ",", "."); ?> </td>
                                </tr>
                                <!-- <?php
                                        // $no++; endwhile; } 
                                        ?> -->
                            </tbody>
                    </table>
                </div>
                <br>
                <div class="box-header with-border">
                    <h3 class="text-center">Rincian Barang</h3>
                </div>
                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-dark table-hover ">
                        <thead style="background-color :#B0C4DE;">
                            <th>No</th>
                            <th>Deskripsi</th>
                            <th>QTY</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                        </thead>
                        <tr>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    $total = 0;
                                    if (mysqli_num_rows($querySbo)) {
                                        while ($row = mysqli_fetch_assoc($querySbo)) :

                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['sub_deskripsi']; ?> </td>
                                            <td> <?= $row['sub_qty']; ?> </td>
                                            <td> <?= $row['sub_unit']; ?> </td>
                                            <td> <?= formatRupiah($row['sub_unitprice']); ?> </td>
                                            <td><?= formatRupiah($row['total_price']); ?></td>
                                </tr>
                        <?php
                                            $total += $row['total_price'];
                                            $no++;
                                        endwhile;
                                    } ?>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="5"><b>Sub Total</b></td>
                            <td><b> <?= formatRupiah($data2['sub_totalpo']); ?></b></td>
                        </tr>
                        <tr>
                            <td colspan="5"><b>Diskon </b></td>
                            <td><b> <?= formatRupiah($data2['diskon_po']); ?></b></td>
                        </tr>
                        <?php
                        $total = $data2['sub_totalpo'] - $data2['diskon_po'];
                        $grandTotal = $total + $data2['nilai_ppn'];
                        ?>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="5"><b>Total </b></td>
                            <td><b> <?= formatRupiah($data2['total_po']); ?></b></td>
                        </tr>
                        <tr>
                            <td colspan="5"><b> PPN 10% </b></td>
                            <td><b> <?= formatRupiah($data2['nilai_ppn']); ?></b></td>
                        </tr>
                        <tr>
                            <td colspan="5"><b> Nilai <?= $data2['nm_pph']; ?> </b></td>
                            <td><b> (<?= formatRupiah($data2['nilai_pph']); ?>)</b></td>
                        </tr>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="5"><b> Grand Total </b></td>
                            <td><b> <?= formatRupiah($data2['grand_totalpo']); ?></b></td>
                        </tr>
                            </tbody>
                    </table>
                </div>
                <br>
                <?php
                $foto = $data2['foto_item'];
                if ($foto === '0') { ?>
                    <h3 class="text-center">Foto Barang</h3>
                    <br>
                    <div class="row ">
                        <div class="col-sm-offset-">
                            <h5 class="text-center">Tidak Ada Foto</h5>
                        </div>
                    </div>
                <?php } else { ?>
                    <h3 class="text-center">Foto Barang</h3>
                    <br>
                    <div class="row ">
                        <div class="col-sm-offset-2">
                            <img src="../file/foto/<?= $data2['foto_item']; ?>" width="80%" alt="...">
                            <!-- <h5 class="text-center">Tidak Ada Foto</h5> -->
                        </div>
                    </div>
                <?php } ?>
                <br>
                <!-- Embed Document -->
                <div class="box-header with-border">
                    <h3 class="text-center">Document Penawaran</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/doc_penawaran/<?php echo $data2['doc_penawaran']; ?> "></iframe>
                    </div>
                </div>
                <br>
                <!--  -->

                <!--  -->
            </div>
            <br>
        </div>
    </div>
    </div>

    <!--  -->
    <div id="payment" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Konfirmasi Payment </h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="send_paymentpo.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <div class="col-sm-4">
                                    <input type="hidden" value="<?= $data2['id_po']; ?>" class="form-control" name="id_po" readonly>
                                    <input type="hidden" value="<?= $data2['persentase_pembayaran1']; ?>" class="form-control" name="persentase_pembayaran1" readonly>
                                    <input type="hidden" value="<?= $bf['id']; ?>" class="form-control" name="id_bkk" readonly>
                                    <input type="hidden" value="<?= $data2['id_anggaran']; ?>" class="form-control" name="id_anggaran" readonly>
                                    <input type="hidden" value="<?= $data2['jumlah']; ?>" class="form-control" name="qty" readonly>
                                    <input type="hidden" value="<?= $bf['nominal']; ?>" class="form-control" name="nominal" readonly>
                                    <input type="hidden" value="<?= $bf['nilai_barang']; ?>" class="form-control" name="nilai_barang" readonly>
                                    <input type="hidden" value="<?= $bf['nilai_jasa']; ?>" class="form-control" name="nilai_jasa" readonly>
                                    <input type="hidden" value="<?= $bf['nilai_pph']; ?>" class="form-control" name="nilai_pph" readonly>
                                    <input type="hidden" value="<?= $totalPersen; ?>" class="form-control" name="total_persen" readonly>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="nominal" disabled value="<?= formatRupiah($nominalPembayaran); ?>">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label id="tes" for="dari_bank" class="col-sm-4 control-label">Bank</label>
                                <div class="col-sm-4">
                                    <select name="dari_bank" class="form-control">
                                        <option value="">--Pilih Bank--</option>
                                        <?php
                                        $queryBank = mysqli_query($koneksi, "SELECT * FROM bank ORDER BY nm_bank ASC");
                                        if (mysqli_num_rows($queryBank)) {
                                            while ($rowBank = mysqli_fetch_assoc($queryBank)) :
                                        ?>
                                                <option value="<?= $rowBank['id_bank']; ?>" type="checkbox"><?= $rowBank['nm_bank']; ?></option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label id="tes" for="dari_rekening" class="col-sm-4 control-label">Rekening</label>
                                <div class="col-sm-4">
                                    <select name="dari_rekening" class="form-control">
                                        <option value="">--Pilih Rekening--</option>
                                        <?php
                                        $queryRekening = mysqli_query($koneksi, "SELECT * FROM rekening ORDER BY no_rekening ASC");
                                        if (mysqli_num_rows($queryRekening)) {
                                            while ($rowRekening = mysqli_fetch_assoc($queryRekening)) :
                                        ?>
                                                <option value="<?= $rowRekening['id_rekening']; ?>" type="checkbox"><?= $rowRekening['no_rekening']; ?></option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="bukti_pembayaran" class="col-sm-offset-1 col-sm-3 control-label">Bukti Pembayaran </label>
                                <div class="col-sm-5">
                                    <div class="input-group input-file" name="bukti_pembayaran" required>
                                        <input type="text" class="form-control" required />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-choose" type="button">Browse</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="submit">Kirim</button></span></a>
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--  -->

</section>

<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
    });

    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
                    element.attr("name", $(this).attr("name"));
                    element.change(function() {
                        element.next(element).find('input').val((element.val()).split('\\').pop());
                    });
                    $(this).find("button.btn-choose").click(function() {
                        element.click();
                    });
                    $(this).find("button.btn-reset").click(function() {
                        element.val(null);
                        $(this).parents(".input-file").find('input').val('');
                    });
                    $(this).find('input').css("cursor", "pointer");
                    $(this).find('input').mousedown(function() {
                        $(this).parents('.input-file').prev().click();
                        return false;
                    });
                    return element;
                }
            }
        );
    }

    $(function() {
        bs_input_file();
    });
</script>