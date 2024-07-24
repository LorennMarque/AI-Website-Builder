<?php 
    if(isset($_GET['id'])){
        include('views/preview.php');    
    } else { 
        include('views/index.php');    
    }
?>