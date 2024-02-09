<?php
/**
Planning Biblio, Plugin Planning Hebdo
Licence GNU/GPL (version 2 et au dela)
Voir les fichiers README.md et LICENSE
Copyright (C) 2011-2015 - Jérôme Combes

Fichier : importEDT.php
Création : 21 février 2015
Dernière modification : 4 mai 2016
@author Jérôme Combes <jerome@planningbiblio.fr>

Description :
Importe les emplois du temps de la table personnel dans la table planningHebdo

Utilisation : 
- placer ce fichier à la racine du dossier planning
- se connecter à http://serveur/planning/importEDT.php?debut=xxxxx&fin=yyyyy
      (remplacer xxxx par la date de début et yyyy par la date de fin de validité des plannings au format AAAA-MM-JJ)

*/

ini_set("display_errors",1);
error_reporting(999);

session_start();

$version="importEDT";

include "include/config.php";

$CSRFToken = CSRFToken();

$debut=filter_input(INPUT_GET,"debut",FILTER_SANITIZE_STRING);
$fin=filter_input(INPUT_GET,"fin",FILTER_SANITIZE_STRING);

$debut=$debut?$debut:date("Y")."-01-01";
$fin=$fin?$fin:date("Y")."-12-31";

$nb_semaine = $config['nb_semaine'];

$insert=new dbh();
$insert->prepare("INSERT INTO `{$dbprefix}planning_hebdo` (`perso_id`,`debut`,`fin`,`valide`,`validation`,`actuel`,`temps`,`nb_semaine`) VALUES 
  (:perso_id,'$debut','$fin',1,SYSDATE(),1,:temps,$nb_semaine);");


$db=new db();
$db->select("personnel","id,temps");
$insert->CSRFToken = $CSRFToken;
if($db->result){
  foreach($db->result as $elem){
    $insert->execute(array(":perso_id"=>$elem['id'], ":temps"=>$elem['temps']));
  }
}


?>
