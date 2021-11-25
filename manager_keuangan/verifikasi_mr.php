<?php  

    include "../fungsi/koneksi.php";
	include "../fungsi/fungsi.php";


    if (isset($_GET['aksi']) && isset($_GET['id'])) {
        //die($id = $_GET['id']);
        $id = $_GET['id'];
        echo $id;

        if ($_GET['aksi'] == 'lihat') {
            header("location:?p=verifikasi_dmr&id=$id");
        } else if ($_GET['aksi'] == 'hapus') {
            header("location:?p=verifikasi_dmr&id=$id");
        } 
    }
        
        $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
        $rowUser=mysqli_fetch_assoc($queryUser);
        $idUser=$rowUser['id_user'];

        $query = mysqli_query($koneksi, "SELECT * 
                                            FROM biaya_ops bo
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi
                                            JOIN jenis_pengajuan j
                                            ON j.id_jenispengajuan = bo.id_jenispengajuan
                                            WHERE bo.status_biayaops = '5' ORDER BY bo.kd_transaksi DESC   ");                                                


	

?>
<!-- Main content -->
<section class="content">
<!-- Small boxes (Stat box) -->
	<div class="row">
		<div class="col-sm-12">
			 <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi Material Request</h3>
                </div>                                
                <div class="box-body">
                <div class="row">
                    <br><br>
                </div>                        
                <div class="table-responsive">
                		<table class="table text-center table table-striped table-hover" id=" ">
                			<thead> 
	                			<tr style="background-color :#B0C4DE;">
	                				<th>No</th>
                                    <th>Kode Transaksi</th>	  
									<th>Tanggal</th>
                                    <th>Jenis Pengajuan</th>
                                    <th>Divisi</th>	                                    							                                    
	                				<th>Aksi</th>	                				
	                			</tr>
                			</thead>
                			<tbody>
                				<tr>
                					<?php 
                						$no =1 ;
                						if (mysqli_num_rows($query)) {
                							while($row=mysqli_fetch_assoc($query)):                                            

                					 ?>
                						<td> <?= $no; ?> </td>      
                                        <td> <?= $row['kd_transaksi']; ?> </td>
                                        <td> <?= tanggal_indo($row['tgl_pengajuan']); ?> </td>          					                						                                        
                                        <td> <?= $row['kd_pengajuan']; ?> </td>
                                        <td> <?= $row['nm_divisi']; ?> </td>                                        
                						<td>
                                        <a href="?p=verifikasi_mr&aksi=lihat&id=<?= $row['kd_transaksi']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button  class="btn btn-info">Lihat</button></span></a>                     
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