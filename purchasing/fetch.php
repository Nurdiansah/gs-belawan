<?php  
 //fetch.php  
 $connect = mysqli_connect("localhost", "root", "", "gs");  
 if(isset($_POST["employee_id"]))  
 {  
      $query = "SELECT * FROM sub_dbo WHERE id_subdbo = '".$_POST["employee_id"]."'";  
      $result = mysqli_query($connect, $query);  
      $row = mysqli_fetch_array($result);        
      echo json_encode($row);  
 }  
 ?>