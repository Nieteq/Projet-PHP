<html>
    <?php 
        require_once 'includes/head.php';        
        require_once './mesClasses/Cvisiteurs.php'; 
        
        session_start();
    ?>
<body>
        <?php
        $ovisiteurs = new Cvisiteurs();
        $ocollTrie = $ovisiteurs->getVisiteursTrie();
        // je mémorise le nombre total de visiteur pour utilisation ligne 79 et s
        $_SESSION['nbTotalVisiteur'] = count($ocollTrie);
        
         // Pour conservation de la valeur choisie après un postBack grâce aux variables de session
        if(isset($_POST['debutFin']) && isset($_POST['ville']) && isset($_POST['partieNom'])){
            $_SESSION['debutFin'] = $_POST['debutFin'];
            $_SESSION['ville'] = $_POST['ville'];
            $_SESSION['partieNom'] = $_POST['partieNom'];
        }
        
        $ovisiteurs = new Cvisiteurs();
        $tabVilles = $ovisiteurs->getVilleVisiteur();
        if(isset($_SESSION['ville'])){var_dump($_SESSION['ville']);}
        //var_dump($ocollTrie);
        var_dump($tabVilles);
        ?>
        
    <div class="container">
      
        
        <header title="listevisiteur"></header>
      <div title="filtrage">  
        <form class="form-inline" method="POST" action=""> <!-- quand rien alors action sur la même page -->
            <div class="form-group ">
                <span class="glyphicon glyphicon-search"></span>
                &nbsp; 
                <label for="ville">Choisir la localité :</label>
                &nbsp;
                <select name="ville">
                    <?php
                    // Conservation de la valeur choisie après un postBack grâce aux variables de session
                            echo "<option value='toutes'>Toutes villes</option>";
                            // isset obligatoire car au premier chargement le tableau associatif ($_SESSION['ville]) n'est pas défini
                            if(isset($_SESSION['ville']))
                            {
                                foreach ($tabVilles as $ville) {
                                    echo "<option value='".$ville;
                                    if($_SESSION['ville'] == trim($ville)){echo "' selected >";}else{echo "' >";}
                                    echo $ville." </option>";
                                }
                            }else{
                                foreach ($tabVilles as $ville) {
                                    echo "<option value='".$ville."' >".$ville."</option>";
                                }
                                
                            }
                            
                            
                    ?>

                </select>
            </div>
            <br>
            <br>
            <br>
            <div class="form-group">
                <span class="glyphicon glyphicon-search"></span>
                &nbsp;
                <label for="Nom">Saisir tout ou partie du nom :</label>
                &nbsp;
                <!-- Conservation de la valeur choisie après un postBack grâce aux variables de session -->
                <input type="text" name="partieNom" value="<?=isset($_SESSION['partieNom'])?$_SESSION['partieNom']:''?>" class="form-control" required>
            </div>
                &nbsp;&nbsp;
            <!-- Conservation de la valeur choisie après un postBack grâce aux variables de session -->
            <?php if(isset($_SESSION['debutFin'])){
            echo '<div class="radio">' ?>
                <?php echo '<INPUT type= "radio" name="debutFin" value="debut"';
                if($_SESSION['debutFin'] == 'debut'){echo ' checked required> Début &nbsp;';}else{echo'required> Début &nbsp;';}
                echo '<INPUT type= "radio" name="debutFin" value="fin"';
                if($_SESSION['debutFin'] == 'fin'){echo ' checked required> Fin &nbsp;';}else{echo'required> Fin &nbsp;';}
                echo '<INPUT type= "radio" name="debutFin" value="nimporte"';
                if($_SESSION['debutFin'] == 'nimporte'){echo ' checked required> Dans la chaine &nbsp';}else{echo'required> Dans la chaine &nbsp;';}
            echo'</div>';
            ?>
            <?php }else{
                
            echo '<div class="radio">
                <INPUT type= "radio" name="debutFin" value="debut" checked required> Début &nbsp
                <INPUT type= "radio" name="debutFin" value="fin" required> Fin &nbsp
                <INPUT type= "radio" name="debutFin" value="nimporte" required> Dans La Chaine &nbsp
                </div>';               
            } 
            ?>
                &nbsp;&nbsp;
            <button type="submit" class="btnFiltrage">Filtrer</button>
         </form> 
      </div>  
        <?php
         
            if(isset($_POST['debutFin']) && isset($_POST['ville']) && isset($_POST['partieNom']))
            {
                
            
                $ovisiteurs = new Cvisiteurs();
                $tabVisiteurs = $ovisiteurs->getTabVisiteursParNomEtVille($_POST['debutFin'],$_POST['partieNom'], $_POST['ville']);
                $otrie = new Ctri();
                //$tabVisiteurs = $otrie->TriTableauObjetSurNom($tabVisiteurs);
                //var_dump($tabVisiteurs);
                
                if($tabVisiteurs != null)
                {
                    //dans le if car le tabelau ne doit pas être nul pour le tri
                    $tabVisiteurs = $otrie->TriTableau($tabVisiteurs, 'nom');
                            /* remet l'en-tête du tableau comme au début si le nombre de visiteur est le nombre total
                            sinon l'en-tête précise que le tableau est filtré' avec le nombre de visiteur dans le titre*/
                            if(count($tabVisiteurs) == $_SESSION['nbTotalVisiteur']){
                              echo '<h1><p title="tabvisiteur">liste des visiteurs médicaux ('.count($tabVisiteurs).')</p></h1>';  
                            }
                            else{
                            echo '<h1><p title="tabvisiteur">liste des visiteurs médicaux filtrés par nom et par ville ('.count($tabVisiteurs).')</p></h1>';   }        
                            echo '<table class="table table-condensed">
                                    <thead title="entetetabvisiteur">
                                        <tr>
                                          <th>ID</th>
                                          <th>LOGIN</th>
                                          <th>NOM</th>
                                          <th>PRENOM</th>
                                          <th>VILLE</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                    $i = 0;
                    foreach ($tabVisiteurs as $ovisiteur)
                    {
        ?>
                            <tr class="<?=$i%2===0?'':'ligneTabVisitColor'?>">
                            <td><?=$ovisiteur->id?></td>
                            <td><?=$ovisiteur->login?></td>
                            <td><?=$ovisiteur->nom?></td>
                            <td><?=$ovisiteur->prenom?></td>
                            <td><?=$ovisiteur->ville?></td>
                            </tr>
        <?php                     
                            $i++;
                         
                    }


                        echo "</tbody>";
                    echo "</table>";
                }
                if($tabVisiteurs == null)
                {
                    $errorMsg = "Il n'y a pas de visiteur répondant aux critères.";
                    
                    if(isset($errorMsg))
                    {
                         echo "<br><br><div class='alert alert-danger'>".$errorMsg."</div>";
                        
                    }
                    
                }
            }
            
            else {
        

        echo '<h1><p title="tabvisiteur">liste des visiteurs médicaux ('.count($ocollTrie).')</p></h1>';;           
        echo '<table class="table table-condensed">
                <thead title="entetetabvisiteur">
                    <tr>
                      <th>ID</th>
                      <th>LOGIN</th>
                      <th>NOM</th>
                      <th>PRENOM</th>
                      <th>VILLE</th>
                    </tr>
                </thead>
                <tbody>';
                      
                          $i=0;
                           foreach ($ocollTrie as $ovisiteur)
                           {
                               if($i % 2 == 1)
                               {
        ?>        
                              <tr class="ligneTabVisitColor">
                                  <td><?php echo $ovisiteur->id ?></td>
                                  <td> <?php echo $ovisiteur->login ?></td>
                                  <td><?php echo $ovisiteur->nom ?></td>
                                  <td><?php echo $ovisiteur->prenom?></td>
                                  <td><?php echo $ovisiteur->ville?></td>
                              </tr>
        <?php
                               }
                               else{
        ?>
                              <tr>
                                  <td><?php echo $ovisiteur->id ?></td>
                                  <td> <?php echo $ovisiteur->login ?></td>
                                  <td><?php echo $ovisiteur->nom ?></td>
                                  <td><?php echo $ovisiteur->prenom?></td>
                                  <td><?php echo $ovisiteur->ville?></td>
                              </tr>
                              
        <?php
                           
                                }
                            $i++;
                          }
        ?>      

                  </tbody>
            </table>
        <?php
            }
        ?>
    </div>
</body>
</html>
