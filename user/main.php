 <section class="content-header">
   <h1>
     Dashboard
     <small>Control panel</small>
   </h1>
   <ol class="breadcrumb">
     <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
     <li class="active">Dashboard</li>
   </ol>
 </section>

 <!-- Main content -->
 <section class="content">

   <!-- row -->
   <div class="row">
     <div class="col-lg-4 col-xs-6">
       <div class="list-group">
         <a href="#" class="list-group-item active">
           Biaya Umum
         </a>
         <!-- <a href="index.php?p=buat_biayanonops" class="list-group-item"> <i class="fa fa-edit"></i> Create <span class="badge label-danger"></span></a> -->
         <a href="#" onClick="alert('Untuk Biaya Umum saat ini bisa langsung melalui Kasir\n\nDengan memberikan Invoice & Kode Anggarannya.')" class="list-group-item"> <i class="fa fa-edit"></i> Create <span class="badge label-danger"></span></a>
         <a href="index.php?p=proses_biayanonops" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"><?php if ($dataProses['jumlah_proses'] >= 1) {
                                                                                                                                                  echo $dataProses['jumlah_proses'];
                                                                                                                                                } ?></span> </a>
         <a href="index.php?p=ditolak_biayanonops" class="list-group-item"> <i class="fa fa-close"></i> Ditolak <span class="badge label-danger"><?php if ($dataTolak['jumlah_ditolak'] >= 1) {
                                                                                                                                                    echo $dataTolak['jumlah_ditolak'];
                                                                                                                                                  } ?></span></a>
         <a href="index.php?p=lihat_bno" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"><?php if ($dataSelesai['jumlah_Selesai'] >= 1) {
                                                                                                                                                  echo $dataSelesai['jumlah_Selesai'];
                                                                                                                                                } ?></span></a>
       </div>
     </div>
     <div class="col-lg-4 col-xs-6">
       <div class="list-group">
         <a href="#" class="list-group-item label-warning">
           Material Request
         </a>
         <a href="index.php?p=buat_mr" class="list-group-item"> <i class="fa fa-edit"></i> Create <span class="badge label-danger"></span></a>
         <a href="index.php?p=proses_mr" class="list-group-item"><i class="fa fa-spinner"></i>Proses<span class="badge label-info"><?php if ($dataPM['jumlah_proses'] >= 1) {
                                                                                                                                      echo $dataPM['jumlah_proses'];
                                                                                                                                    } ?></span></a>
         <a href="index.php?p=tolak_mr" class="list-group-item"> <i class="fa fa-close"></i> Ditolak <span class="badge label-danger"><?php if ($dataTM['jumlah_proses'] > 0) {
                                                                                                                                        echo $dataTM['jumlah_proses'];
                                                                                                                                      } ?></span></a>
         <a href="#" class="list-group-item"><i class="fa fa-minimize"></i> <span class="badge label-info"></span> </a>
       </div>
     </div>
     <!-- <?= $dataTolak['jumlah_ditolak']; ?> -->
     <!-- <?php if ($dataPMR['jumlah'] == 1) {
            echo $dataPMR['jumlah'];
          } ?> -->
     <div class="col-lg-4 col-xs-6">
       <div class="list-group">
         <a href="#" class="list-group-item label-default">
           Service Request
           <a href="#" onClick="alert('Untuk Service Request bisa melalui Material Request\n\nAtau lebih lanjut bisa hubungi ke Pak Amos.')" class="list-group-item"> <i class="fa fa-edit"></i> Create <span class="badge label-danger"></span></a>
           <a href="index.php?p=proses_sr" class="list-group-item"><i class="fa fa-spinner"></i> Proses<span class="badge label-warning"><?= $totalSRSO > 0 ? $totalSRSO : ''; ?></span> </a>
           <a href="index.php?p=ditolak_sr" class="list-group-item"> <i class="fa fa-close"></i> Ditolak <span class="badge label-danger"><?php if ($totalTolakSRO > 0) {
                                                                                                                                            echo $totalTolakSRO;
                                                                                                                                          } ?></span></a>
           <a href="index.php?p=transaksi_sr" class="list-group-item"><i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-info"></span> </a>
       </div>
     </div>
     <!-- </div> -->
     <!-- /row -->
     <!-- row -->
     <!-- <div class="row"> -->
     <div class="col-lg-4 col-xs-6">
       <div class="list-group">
         <a href="#" class="list-group-item label-danger">
           Petty Cash
         </a>
         <a href="index.php?p=buat_petty" class="list-group-item"> <i class="fa fa-edit"></i> Create </a>
         <a href="index.php?p=proses_petty" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-warning"><?= $dataPTP['jumlah'] > 0 ? $dataPTP['jumlah'] : ''; ?></span></a>
         <a href="index.php?p=transaksi_pettycash" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"></span></a>
         <a href="#" class="list-group-item"><i class="fa fa-minimize"></i> <span class="badge label-info"></span> </a>
       </div>
     </div>
     <div class="col-lg-4 col-xs-6">
       <div class="list-group">
         <a href="#" class="list-group-item label-success">
           Kasbon
         </a>
         <a href="index.php?p=buat_kasbon" class="list-group-item"> <i class="fa fa-edit"></i> Create <span class="badge label-info"><?php if ($dataKC['jumlah'] >= 1) {
                                                                                                                                        echo $dataKC['jumlah'];
                                                                                                                                      } ?></span></span></a>
         <a href="index.php?p=kasbon_proses&sp=kp_purchasing" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"><?php echo $dataKP >= 1 ? $dataKP : ''; ?></span></a>
         <a href="index.php?p=ditolak_kasbon&sp=tolak_purchasing" class="list-group-item"> <i class="fa fa-close"></i> Ditolak <span class="badge label-danger"><?php if ($totalTolakKasbon > 0) {
                                                                                                                                                                  echo $totalTolakKasbon;
                                                                                                                                                                } ?></span></span></a>
         <a href="index.php?p=kasbon_transaksi" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"></span></a>
       </div>
     </div>
     <div class="col-lg-4 col-xs-6">
       <div class="list-group">
         <a href="#" class="list-group-item label-primary">
           PO
         </a>
         <a href="index.php?p=po_proses" class="list-group-item"> <i class="fa fa-spinner"></i> Proses <span class="badge label-info"><?php if ($dataPP['jumlah'] >= 1) {
                                                                                                                                        echo $dataPP['jumlah'];
                                                                                                                                      } ?></span> </a>
         <a href="index.php?p=ditolak_po" class="list-group-item"> <i class="fa fa-close"></i> Ditolak <span class="badge label-danger"><?php if ($dataTolakPO['jumlah'] > 0) {
                                                                                                                                          echo $dataTolakPO['jumlah'];
                                                                                                                                        } ?></span></a>
         <a href="index.php?p=transaksi_po" class="list-group-item"> <i class="fa fa-bar-chart-o"></i> Transaksi <span class="badge label-success"></span></a>
         <a href="#" class="list-group-item"><i class="fa fa-minimize"></i> <span class="badge label-info"></span> </a>
       </div>
     </div>
   </div>
   <!-- </div> -->
   <br>

 </section>