<?php if(isset($_GET['tag'])):
    include('views/index.html');    
else: 
    include('views/preview.php');    
endif;