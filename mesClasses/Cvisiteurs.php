<?php


/* ************ Classe métier Cvisiteur et Classe de contrôle Cvisiteurs **************** */
require_once 'mesClasses/Ctri.php';
require_once 'mesClasses/Cdao.php';



// Quentin VAXELAIRE
class Cemploye
{
    public $id;
    public $login;
    public $nom;
    public $prenom;
    public $mdp;
    public $ville;

    function __construct($sid, $slogin, $snom, $sprenom, $mdp, $sville) 
    {

        $this->id = $sid;
        $this->login = $slogin;
        $this->nom = $snom;
        $this->prenom = $sprenom;
        $this->mdp = $mdp;
        $this->ville = $sville;
    }
}
class Cvisiteur extends Cemploye
{
    function __construct($sid, $slogin, $snom, $sprenom, $mdp, $sville)
    {
        parent::__construct($sid, $slogin, $snom, $sprenom, $mdp, $sville);
    }
}



class Cvisiteurs
{

    private $ocollVisiteurById;
    private $ocollVisiteurByLogin;
    private $ocollVisiteur;
    private $tabVilleVisiteur;

    public function __construct()
    {

        try {
            $query = "SELECT * FROM visiteur";
            $odao = new Cdao();
            $lesVisiteurs = $odao->gettabDataFromSql($query);

            foreach ($lesVisiteurs as $unVisiteur) {
                $ovisiteur = new Cvisiteur($unVisiteur['id'], $unVisiteur['login'], $unVisiteur['nom'], $unVisiteur['prenom'], $unVisiteur['mdp'], $unVisiteur['ville']);
                $this->ocollVisiteur[] = $ovisiteur;
                $this->ocollVisiteurById[$ovisiteur->id] = $ovisiteur;
                $this->ocollVisiteurByLogin[$ovisiteur->login] = $ovisiteur;
            }

            /*Les villes à partir de la base */
            /*$query = "SELECT distinct ville FROM visiteur order by ville ASC";
                            
                            $lesVilles = $odao->gettabDataFromSql($query);
                            
                            foreach ($lesVilles as $uneVille)
                            {
                                $this->tabVilleVisiteur[] = $uneVille['ville'];
                                
                            }
                            
                            /*Les villes à partir du tableau de tableau visiteur */



            foreach ($this->ocollVisiteur as $ovisiteur) {
                $this->tabVilleVisiteur[] = $ovisiteur->ville;
            }
            $this->tabVilleVisiteur = array_unique($this->tabVilleVisiteur);
            sort($this->tabVilleVisiteur);
        } catch (PDOException $e) {
            $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
            die($msg);
        }
    }

    //ici les méthodes à rajouter



    function getVisiteurById($sid)
    {
        if (array_key_exists($sid, $this->ocollVisiteurById)) {
            $ovisiteur = $this->ocollVisiteurById[$sid];
            return $ovisiteur;
        }
    }

    function getVisiteurByLogin($login)
    {
        if (array_key_exists($login, $this->ocollVisiteurByLogin)) {
            $ovisiteur = $this->ocollVisiteurByLogin[$login];
            return $ovisiteur;
        }
    }

    function verifierInfosConnexion($username, $pwd)
    {

        foreach ($this->ocollVisiteurById as $ovisiteur) {
            if ($ovisiteur->login == $username && $ovisiteur->mdp == $pwd) {
                return $ovisiteur;
            }
        }
        return null;
    }

    function getVisiteursTrie()
    {
        $otrie = new Ctri();
        $ocollVisiteursTrie = $otrie->TriTableau($this->ocollVisiteur, 'ville');
        return $ocollVisiteursTrie;
    }

    function getVilleVisiteur()
    {

        /*$otrie = new Ctri();
        $tabVille = $otrie->TriTableauObjetSurVille($this->ocollVisiteur);
        $tabVilleTrie = array();
        foreach($tabVille as $ovisiteur)
        {
            $tabVilleTrie[] = $ovisiteur->ville;
            
        }
        return $tabVilleTrie;*/
        return $this->tabVilleVisiteur;
    }

    function getTabVisiteursParNomEtVille($sdebutFin, $spartieNom, $sville)
    {
        $tabVisiteursByVilleNom = null;

        /*if($partieNom == '*' && $sville == 'toutes')
        {
            
            return $this->ocollVisiteur;
        }*/

        foreach ($this->ocollVisiteur as $ovisiteur) {

            if ((strtolower($ovisiteur->ville) == strtolower($sville)) || $sville == 'toutes') {
                if ($spartieNom != '*') {
                    if ($sdebutFin == "debut") {
                        $nomExtrait = substr($ovisiteur->nom, 0, strlen($spartieNom));

                        if (strtolower($nomExtrait) == strtolower($spartieNom)) {
                            $tabVisiteursByVilleNom[] = $ovisiteur;
                        }
                    }
                    if ($sdebutFin == "fin") {

                        $nomExtrait = substr($ovisiteur->nom, -strlen($spartieNom), strlen($spartieNom));

                        if (strtolower($nomExtrait) == strtolower($spartieNom)) {
                            $tabVisiteursByVilleNom[] = $ovisiteur;
                        }
                    }

                    if ($sdebutFin == "nimporte") {
                        $i = 0;
                        $tab = str_split($ovisiteur->nom);
                        foreach ($tab as $caract) {
                            $nomExtrait = substr($ovisiteur->nom, $i, strlen($spartieNom));

                            if (strtolower($nomExtrait) == strtolower($spartieNom)) {
                                $tabVisiteursByVilleNom[] = $ovisiteur;
                                break;
                            }

                            $i++;
                        }
                    }
                } else {
                    $tabVisiteursByVilleNom[] = $ovisiteur;
                }
            }
        }



        return $tabVisiteursByVilleNom;
    }
}
