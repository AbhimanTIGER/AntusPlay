<?php 

    // db deta //
    $host ="localhost";
    $user ="root";
    $pass ="";
    $dbname ="antusplay_db";

    //connection creat//

    $conn = mysqli_connect($host, $user, $pass, $dbname);
    
    //connction chek//
    if (!$conn){
        die("connection failed : " . mysqli_connect_error());
    }
    //echo "connected successfully";//
?>
