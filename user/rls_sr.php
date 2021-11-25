<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['release'])) {

    $tanggal  = dateNow();

    $link = "url=index.php?p=approval_sr&lvl=manager";
    // Deklarasi variabel
    $id = $_POST['id'];
    $id_manager = $_POST['id_manager'];
    $userName = getUserName($_POST['id_user']);
    $emailManager = getEmailAddress($id_manager);
    $nameManager = getUserName($id_manager);

    $queryData = mysqli_query($koneksi, "SELECT * -- email, nama, nm_divisi
                                            FROM sr s                                            
                                            JOIN divisi d
                                            ON d.id_divisi = s.id_divisi
                                            WHERE s.id_sr = '$id'
                                            ");
    $data = mysqli_fetch_assoc($queryData);

    $subject = "Approval Service Request " . $_POST['nm_barang'];
    $body = addslashes("<font style='font-family: Courier;'>
                            Dear Bapak/Ibu <b>$nameManager</b>,<br><br>
                            Diberitahukan bahwa <b>" . $userName . "</b> telah membuat pengajuan Service Request, dengan rincian sbb:<br>
                            <table>
                                <tr>
                                    <td style='font-family: Courier;'>Tanggal</td>
                                    <td style='font-family: Courier;'>: " . $data['created_at'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Divisi</td>
                                    <td style='font-family: Courier;'>: " . $data['nm_divisi'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Barang/Alat</td>
                                    <td style='font-family: Courier;'>: " . $data['nm_barang'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Keterangan</td>
                                    <td style='font-family: Courier;'>: " . $data['keterangan'] . "</td>
                                </tr>                                                             
                            </table>
                            <br>
                            Mohon untuk melakukan <i>Approval</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                            Best Regards,<br>
                            This email auto generate by system.
                        </font>");

    // BEGIN/START TRANSACTION        
    mysqli_begin_transaction($koneksi);

    // insert queue email
    $queue = createQueueEmail($nameManager, $emailManager, $subject, $body);

    // UPDATE Table sr
    $query = mysqli_query($koneksi, "UPDATE sr
              SET status = '1',
              updated_at = '$tanggal'
              WHERE id_sr ='$id' ");

    // Artinya kita sudah bisa melakukan COMMIT
    if ($queue && $query) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'SR berhasil di release!', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');

        header("location:index.php?p=buat_sr");
    } else {
        die("Error Karena : " . mysqli_error($koneksi));
        #jika ada query yang gagal
        mysqli_rollback($koneksi);


        setcookie('pesan', 'SR Gagal di release  !', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');

        header("location:index.php?p=buat_sr");
    }
}

?>
<!-- pindah -->
<!--  -->