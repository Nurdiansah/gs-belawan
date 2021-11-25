<?php  

    include "../fungsi/koneksi.php";
	include "../fungsi/fungsi.php";


    if (isset($_GET['aksi']) && isset($_GET['id'])) {
        //die($id = $_GET['id']);
        $id = $_GET['id'];
        echo $id;

        if ($_GET['aksi'] == 'detail') {
            header("location:?p=detail_kaskeluar&id=$id");
        } else if ($_GET['aksi'] == 'hapus') {
            header("location:?p=hapus_joborder&id=$id");
        } 
    }

        $query = mysqli_query($koneksi, "SELECT * FROM bkk WHERE status_bkk='2' ORDER BY tgl_bkk DESC  ");    
        
    // } else {
    //     $query = mysqli_query($koneksi, "SELECT * FROM stokbarang");        
    // }

	

?>
<!-- Main content -->
<section class="content">
<!-- Small boxes (Stat box) -->
	<div class="row">
		<div class="col-sm-12">
			 <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Kas Keluar</h3>
                </div>                
                <div class="box-body">
                <div class="row">
                    <!-- <div class="col-md-2">
                        <a href="index.php?p=tambahmaterial" class=" btn btn-primary"><i class="fa fa-plus"></i> Tambah Data Stok</a><br>						
                </div>
					 -->
					<!-- <div class="col-md-2 pull-right">
						<a target="_blank" href="cetakstok.php?idjenis=<?= $id_jenis;  ?>" class="btn btn-success"><i class="fa fa-print"></i> Cetak Job Order</a><br>
					</div> -->
                    <br><br>
                </div>                        
                	<div class="table-responsive">
                    <table class="table text-center table table-striped table-hover" id="">
                			<thead  > 
	                			<tr>
	                				<th>No</th>	  
	                				<th>No BKK</th>
									<th>Tanggal Pengajuan BKK</th>									
                                    <th>Keterangan</th>        				
	                				<th>Nama Vendor</th>
                                    <th>Jumlah</th>
                                    <!-- <th>Status</th> -->
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
										<td> <?= $row['id_bkk']; ?> </td>
                                        <td> <?= tanggal_indo($row['tgl_pengajuan']); ?> </td>          					
                						<td> <?= $row['keterangan']; ?> </td>
                						<td> <?= $row['nm_vendor']; ?> </td>
                                        <td> <?= "Rp.".$angka_format; ?> </td>
                                        <!-- <td>                
                                            <?php echo '0', ' %';
                                            ?>                                        
                                         </td> -->
                                        
                                        
                						<td>
                                            <a href="?p=lihat_kaskeluar&aksi=detail&id=<?= $row['id_bkk']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button  class="btn btn-info">Detail</button></span></a>                     

                                            <!-- <a target="_blank" href="cetak_jobreportvessel.php" class="btn btn-success"><i class="fa fa-print"></i> Cetak </a> -->
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