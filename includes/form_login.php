
    <?php        
        require_once './mesClasses/Cvisiteurs.php';   
                
        if(isset($_POST['username']) && isset($_POST['pwd']))
        {
            $lesVisiteurs = new Cvisiteurs();
            $ovisiteur = $lesVisiteurs->verifierInfosConnexion($_POST['username'], $_POST['pwd']);
            print_r($ovisiteur);
            if($ovisiteur)
            {
                header('Location: liste_visiteur.php');
            }
            else
            {
               $errorMsg = "Login/Mot de passe incorrect";
            }            
        }
        
    ?>  
    




<h2>Bienvenue sur le site de GSB</h2>
<div class="bg-img">
    
    <div  class="containerForm">
        <form action="" method="post">
          <h1>Login</h1>

          <label for="username"><b>Login</b></label>
          <input type="text" id="username" placeholder="Entrez un login" name="username" required="">

          <label for="pwd"><b>Password</b></label>
          <input type="password" id="pwd" placeholder="Entrez un mot de passe" name="pwd" required="">
          <button type="submit" class="btn">Login</button>
        </form>
    </div>
    
</div>
