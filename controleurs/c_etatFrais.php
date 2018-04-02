<?php
/**
 * Gestion de l'affichage des frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
//On récupère ci dessous toutes les variables stockées en Session
$idUtilisateur = $_SESSION['idUtilisateur'];
$statut = $_SESSION['statut'];
if(isset($_SESSION['lesVisiteurs'])) {//on vérifie pour chaque donnée inhérante à cette page si elles existent bien en session
    $lesVisiteurs = $_SESSION['lesVisiteurs'];
}
if(isset($_SESSION['lesMois'])) {
    $lesMois = $_SESSION['lesMois'];
}
if(isset($_SESSION['leVisiteur'])) {
    $idVisiteur = $_SESSION['leVisiteur'];
}
if(isset($_SESSION['leMois'])) {
    $leMois = $_SESSION['leMois'];
}

if (filter_input(INPUT_POST, 'okC', FILTER_SANITIZE_STRING)) {//on sécurise ici les actions concernant le choix du visiteur à traiter
    $action = setAction(filter_input(INPUT_POST, 'okC', FILTER_SANITIZE_STRING));
} else {
	$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
}

switch ($action) {
case 'selectionnerMois':
    $lesMois = $pdo->getLesMoisDisponibles($idUtilisateur);
    // Afin de sélectionner par défaut le dernier mois dans la zone de liste
    // on demande toutes les clés, et on prend la première,
    // les mois étant triés décroissants
    $lesCles = array_keys($lesMois);
    $moisASelectionner = $lesCles[0];
    include 'vues/v_listeMois.php';
    break;
case 'voirEtatFrais':
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois = $pdo->getLesMoisDisponibles($idUtilisateur);
    $moisASelectionner = $leMois;
    include 'vues/v_listeMois.php';
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur, $leMois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idUtilisateur, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUtilisateur, $leMois);
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    include 'vues/v_etatFrais.php';
    break;
    /*Ce cas est celui dans lequel nous allons choisir le visiteur à traiter*/
case 'suiviPaiementFrais':
    $lesVisiteurs =// on stocke ici les visiteurs dans une variable de session
    $_SESSION['lesVisiteurs']= $pdo->getVisiteursATraiter('VA','VA');// nous définissons les filtres des états des fiches des visiteurs à récupérer uniquement à 'Validé'
    if (isset($lesVisiteurs[0])) {
        require 'vues/v_choisirFichesOuvrir.php';//on récupère les visiteurs selon l'etat de leur fiche
        require 'vues/v_choisirFichesListeVisiteurs.php';
        require 'vues/v_choisirFichesValider.php';
    } else {
        ajouterSucces('Plus aucune fiche de visiteur à traiter');
        require 'vues/v_choisirFichesOuvrir.php';   //si la ligne ne retourne aucune valeur
        require 'vues/v_succes.php';                //alors tous les visiteurs ont été remboursés
        require 'vues/v_choisirFichesValider.php';
    }

    break;
    /*Ce cas est celui dans lequel nous allons cibler la fiche à traiter*/
case 'choixFiche':
    $lesVisiteurs =
    $_SESSION['lesVisiteurs']= $pdo->getVisiteursATraiter('VA','VA');
    $idVisiteur =
    $_SESSION['leVisiteur'] =filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $lesMois =
    $_SESSION['lesMois'] = $pdo->getMoisATraiter($idVisiteur,'VA');//on récupère les mois
    /* Nous stockons lesVisiteurs, leVisiteur, lesMois dans des variables de session afin de ne plus appeler les fonctions qui les ont récupérés
     * n'ayant plus besoin de les définir plus tard dans le programme*/
    if (isset($lesMois[0])) {// si la ligne est remplit
        require 'vues/v_choisirFichesOuvrir.php';
        require 'vues/v_choisirFichesListeVisiteurs.php';   //on affiche les fiches de frais à valider disponnibles
        require 'vues/v_choisirFichesListeMois.php';        //sous forme de mois à traiter
        require 'vues/v_choisirFichesValider.php';
    } else {// si la ligne est vide
    	ajouterErreur('Pas de fiche de frais validée pour ce visiteur');//cela veut dire que ce visiteur n'a pas de fiche de frais à remplir
        require 'vues/v_erreurs.php';
        require 'vues/v_choisirFichesOuvrir.php';
        require 'vues/v_choisirFichesListeVisiteurs.php';
        require 'vues/v_choisirFichesValider.php';
    }
    break;
    /*Dans ce cas nous affichons la fiche de frais à valider*/
case 'voirFicheFrais':
    $leMois =
    $_SESSION['leMois'] = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantAValider = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    //on récupère ci-dessus toutes les variables nécessaires au remplissage de la fiche de frais
    require 'vues/v_choisirFichesOuvrir.php';
    require 'vues/v_choisirFichesListeVisiteurs.php';   //on affiche ainsi la fiche de frais
    require 'vues/v_choisirFichesListeMois.php';        //avec pour seule variable modifiable
    require 'vues/v_choisirFichesValider.php';          //le taux de remboursement du visiteur
    require 'vues/v_etatFrais_comptable.php';
    break;
    /*Dans ce cas nous allons changer l'état de la fiche de frais à 'Remboursée'*/
case 'rembourserFiche':
    $montant =  filter_input(INPUT_POST, 'montant', FILTER_SANITIZE_STRING);//on récupère ici le montant, modifié ou non par le comptable, de la fiche de frais
    if ($montant == '') {//nous vérifions que la section n'est pas vide
        ajouterErreur('Le champ montant ne peut pas être vide');
    } elseif (!is_numeric($montant)) {// et que la valeur est bien numérique
        ajouterErreur('Le champ montant doit être numérique');
    }
    if (nbErreurs()>0)
    {
    	require 'vues/v_erreurs.php';// si ce n'est pas le cas on affiche une erreur
    } else {
        $pdo -> majFicheFrais($idVisiteur,$leMois,$montant,'RB');   //si tout s'est bien déroulé
        ajouterSucces('La fiche a été remboursée.');                //nous mettons à jour la base de donnée
        require 'vues/v_succes.php';                                //et affichons un message de succès du remboursement
    }
    $lesVisiteurs =
    $_SESSION['lesVisiteurs']= $pdo->getVisiteursATraiter('VA','VA');
    require 'vues/v_choisirFichesOuvrir.php';                       //On actualise la liste de visiteurs dans le cas où le visiteur actuel ait été entièrement traité
    require 'vues/v_choisirFichesListeVisiteurs.php';               //On affiche ainsi la liste de visiteurs
    require 'vues/v_choisirFichesValider.php';                      //afin de sélectionner une autre fiche à traiter
    break;
}
