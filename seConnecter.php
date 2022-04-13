<!DOCTYPE html>

<html>
    <?php 
        require_once 'includes/head.php'; //'includes/head_1.php';        
    
        session_start();
    ?>
    
    <body>
        <div class='container'>
            
            <?php
                
                $formAction = $_SERVER['PHP_SELF'];//"seConnecter.php";
            
                
                require_once 'includes/form_login_.php';
            ?>   
            
            <br>
            <br>
            
            <?php
                require_once 'includes/gestion-erreur.php';
                require_once 'includes/footer.php'
            ?>
            
        </div>
    </body>
</html>
