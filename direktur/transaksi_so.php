<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahun = date("Y");

$queryData =  mysqli_query($koneksi, "SELECT *, s.created_by as yg_buat
                                        FROM so s
                                        JOIN anggaran a
                                            ON a.id_anggaran = s.id_anggaran
                                        JOIN divisi d
                                            ON d.id_divisi = s.id_divisi
                                        WHERE status IN ('1', '2', '3', '4', '5', '6', '202', '303', '404', '505', '606')
                                        ORDER BY created_at DESC
                ");

?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">

                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Service Order</h3>
                </div>

                <div class="box-header with-border">
                    <!-- Tombol untuk menampilkan modal-->
                </div>

                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-hover" id="material">
                        <tr style="background-color :#B0C4DE;">
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama</th>
                            <th rowspan="2">Divisi</th>
                            <th rowspan="2">Tanggal</th>
                            <th rowspan="2">Nama Barang</th>
                            <th rowspan="2">Keterangan</th>
                            <th rowspan="2">Kode Anggaran</th>
                            <th rowspan="2">Total</th>
                            <th rowspan="2">Status</th>
                        </tr>
                        <tr>
                            <tbody>

                                <?php
                                $no = 1;
                                if (mysqli_num_rows($queryData)) {
                                    while ($row = mysqli_fetch_assoc($queryData)) :

                                ?>
                                        <td> <?= $no; ?> </td>
                                        <td> <?= $row['yg_buat']; ?> </td>
                                        <td> <?= $row['nm_divisi']; ?> </td>
                                        <td> <?= formatTanggal($row['created_at']); ?> </td>
                                        <td> <?= $row['nm_barang']; ?> </td>
                                        <td> <?= $row['keterangan']; ?> </td>
                                        <td> <?= $row['kd_anggaran'] . " - " . $row['nm_item']; ?> </td>
                                        <td> <?= formatRupiah($row['grand_total']); ?> </td>
                                        <td>
                                            <?php if ($row['status'] == 1) { ?>
                                                <span class="label label-primary">Approve Manager GA</span>
                                            <?php } elseif ($row['status'] == 2) { ?>
                                                <span class="label label-success">Verifikasi Pajak</span>
                                            <?php } elseif ($row['status'] == 3) { ?>
                                                <span class="label label-default">Approve Manager Finance</span>
                                            <?php } elseif ($row['status'] == 4) { ?>
                                                <span class="label label-success">Approve Direktur</span>
                                            <?php } elseif ($row['status'] == 5) { ?>
                                                <span class="label label-warning">Payment Kasir</span>
                                            <?php } elseif ($row['status'] == 6) { ?>
                                                <span class="label label-success">BKK</span>
                                            <?php } elseif ($row['status'] == 606) { ?>
                                                <span class="label label-danger">Direject Direktur</span>
                                            <?php } elseif ($row['status'] == 505) { ?>
                                                <span class="label label-danger">Direject Manager Finance</span>
                                            <?php } elseif ($row['status'] == 404) { ?>
                                                <span class="label label-danger">Direject Pajak</span>
                                            <?php } elseif ($row['status'] == 303) { ?>
                                                <span class="label label-danger">Direject Manager GA</span>
                                            <?php } elseif ($row['status'] == 202) { ?>
                                                <span class="label label-danger">Direject Purchasing</span>
                                            <?php } else {
                                                echo "-";
                                            } ?>
                                        </td>
                        </tr>

                <?php
                                        $no++;
                                    endwhile;
                                } ?>
                </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
        $(".add-more").click(function() {
            var html = $(".copy").html();
            $(".after-add-more").after(html);
        });
        $("body").on("click", ".remove", function() {
            $(this).parents(".control-group").remove();
        });
    });

    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost' accept='application/pdf' style='visibility:hidden; height:0'>");
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