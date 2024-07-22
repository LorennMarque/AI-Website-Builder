<?php 
    if(isset($_GET['tag'])){
        include('views/preview.html');    
    } else { 
        include('views/index.html');    
    }
?>