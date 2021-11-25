<?php  

    include "../fungsi/koneksi.php";
	include "../fungsi/fungsi.php";


    if (isset($_GET['aksi']) && isset($_GET['id'])) {
        //die($id = $_GET['id']);
        $id = $_GET['id'];
        echo $id;

        if ($_GET['aksi'] == 'edit') {
            header("location:?p=transaksi_dbu&id=$id");
        } else if ($_GET['aksi'] == 'hapus') {
            header("location:?p=hapus_joborder&id=$id");
        } 
    }

        $query = mysqli_query($koneksi,  "SELECT * 
                                FROM bkk b
                                JOIN divisi d
                                ON d.id_divisi = b.id_divisi
                                JOIN anggaran a
                                ON b.id_anggaran = a.id_anggaran
                                ORDER BY b.kd_transaksi DESC  "); 
        
	

?>
<!-- Main content -->
<section class="content">
<!-- Small boxes (Stat box) -->
	<div class="row">
		<div class="col-sm-12">
			 <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Biaya Umum</h3>
                </div>                
                <div class="box-body">
                                       
                	<div class="table-responsive">
                    <table class="table text-center table table-striped table-hover" id=" ">
                			<thead  > 
	                			<tr>
                                    <th>No</th>	  
	                				<th>Kode Transaksi</th>
									<th>Tanggal Pengajuan</th>									
                                    <th>Divisi</th>
                                    <th>Kode Anggaran</th>
                                    <th>Keterangan</th>        					                				
                                    <th>Jumlah</th>                                    
	                				<th>Aksi</th>	                				
	                			</tr>
                			</thead>
                			<tbody>
                				<tr>
                					<?php 
                						$no =1 ;
                						if (mysqli_num_rows($query)) {
                							while($row=mysqli_fetch_assoc($query)):
                                            $angka_format = number_format($row['jml_bkk'],2,",",".");

                					 ?>
                						<td> <?= $no; ?> </td>      
										<td> <?= $row['kd_transaksi']; ?> </td>
                                        <td> <?= tanggal_indo($row['tgl_pengajuan']); ?> </td> 
                                        <td> <?= $row['nm_divisi']; ?> </td>
                                        <td> <?= $row['kd_anggaran'].' ['.$row['nm_item'].']'; ?> </td>
                						<td> <?= $row['keterangan']; ?> </td>                						
                                        <td> <span class="label label-success"><?= formatRupiah($row['jml_bkk']) ?> </span></td>                                                                                
                                        
                						<td>
                                            <a href="?p=transaksi_bu&aksi=edit&id=<?= $row['id_bkk']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button  class="btn btn-info"><i class="fa fa-search-plus"></i></button></span></a>                     
                                        </td>              					
                				</tr>
                            <?php 
                            $no++; endwhile; } ?>
                			</tbody>
                		</table>
                	</div>                	
                </div>
            </div>
		</div>
	</div>
</section>
<script>
    $(function(){
        $("#material").DataTable({
             "language": {
            "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
            "sEmptyTable": "Tidak ada data di database"
            }
        });
    });
</script> 