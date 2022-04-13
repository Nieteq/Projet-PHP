<?php

class Cdao
{
    private function getPDO()
    {
        $strConnection = 'mysql:host=localhost;dbname=gsb'; // DSN
        $arrExtraParam= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"); // demande format utf-8
        $pdo = new PDO($strConnection, 'root', '', $arrExtraParam); // Instancie la connexion
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// Demande la gestion d'exception car par défaut PDO ne la propose pas 
        return $pdo;
    }
    
    public function gettabDataFromSql($squery)
    {
        $pdo = $this->getPDO();        
        $lesVisiteurs = $pdo->query($squery);                       
        $this->ocollVisiteur = array();
        return $lesVisiteurs;
    }
}

