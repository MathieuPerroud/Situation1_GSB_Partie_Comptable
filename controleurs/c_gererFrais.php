<?php
/**
 * Gestion des frais
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
if(isset($_SESSION['etat'])) {
    $etat = $_SESSION['etat'];
}
$mois = getMois(date('d/m/Y'));
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);
if (filter_input(INPUT_POST, 'okC', FILTER_SANITIZE_STRING)) {//ici nous définissons le cas qui va être choisi
    $action = setAction(filter_input(INPUT_POST, 'okC', FILTER_SANITIZE_STRING));  //au moment du choix du visiteur et du mois, c'est une sécurité
} else {                                                                                //afin de rendre la page plus fluide
	$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
}


switch ($action) {
case 'saisirFrais':
    if ($pdo->estPremierFraisMois($idVisiteur, $mois)) {
        $pdo->creeNouvellesLignesFrais($idVisiteur, $mois);
    }
    break;
case 'validerMajFraisForfait':
    $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
    if (lesQteFraisValides($lesFrais)) {
        if ($statut !='comptable') {
            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
        } else {
            $pdo->majFraisForfait($idVisiteur, $leMois, $lesFrais); // nous avons ajouté au code source de base une modification permettant de gérer la mise
            ajouterSucces('Les frais forfaitisés ont bien été mis à jour.');// à jour des frais forfaitisés par le comptable
            include 'vues/v_succes.php';
        }
    } else {
        ajouterErreur('Les valeurs des frais doivent être numériques');
        include 'vues/v_erreurs.php';
    }
    break;
case 'validerCreationFrais':
    $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_STRING);
    $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
    $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
    valideInfosFrais($dateFrais, $libelle, $montant);
    if (nbErreurs() != 0) {
        include 'vues/v_erreurs.php';
    } else {
        $pdo->creeNouveauFraisHorsForfait(
            $idVisiteur,
            $mois,
            $libelle,
            $dateFrais,
            $montant
        );
    }
    break;
case 'supprimerFrais':
    $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
    $pdo->supprimerFraisHorsForfait($idFrais);
    break;
    /*Dans ce cas nous proposons de choisir un visiteur et laissons la liberté de choisir entre deux états permettant une marge d'erreur aux comptables*/
case 'validerFrais':
    $pdo->cloturerFicheFrais($mois);
    $lesVisiteurs =
    $_SESSION['lesVisiteurs']= $pdo->getVisiteursATraiter('VA','CL');//Stocke dans une variable de session la liste des visiteurs ayant des fiches de frais à traiter
    require 'vues/v_choisirFichesOuvrir.php';
    require 'vues/v_choisirFichesChoixEtat.php';// on propose de choisir l'état de la fiche de frais qu'on veut traiter si le comptable se rend compte s'être trompé après validation par exemple
    require 'vues/v_choisirFichesListeVisiteurs.php';// on affiche la liste récupérée précédemment
    require 'vues/v_choisirFichesValider.php';
    break;
    /*Dans ce cas nous allons afficher les mois des fiches qu'il reste à valider ou à modifier la validation*/
case 'choixFiche':
    $lesVisiteurs =
    $_SESSION['lesVisiteurs']= $pdo->getVisiteursATraiter('VA','CL');// on rappelle ici la fonction getVisiteursATraiter dans le cas où le visiteur précédent n'aie plus de fiche traitable
    $etat =
    $_SESSION['etat'] =    filter_input(INPUT_POST, 'rbEtat', FILTER_SANITIZE_STRING);
    $idVisiteur =
    $_SESSION['leVisiteur'] =filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $lesMois =
    $_SESSION['lesMois'] = $pdo->getMoisATraiter($idVisiteur,$etat);
    /* Nous stockons lesVisiteurs, l'état, leVisiteur, lesMois dans des variables de session afin de ne plus appeler les fonctions qui les ont récupérés
     * n'ayant plus besoin de les définir plus tard dans le programme*/
    if (isset($lesMois[0])) {// on vérifie ici que la ligne retournée par la fonction getMoisATraiter n'est pas vide
        require 'vues/v_choisirFichesOuvrir.php';
        require 'vues/v_choisirFichesChoixEtat.php';
        require 'vues/v_choisirFichesListeVisiteurs.php';
        require 'vues/v_choisirFichesListeMois.php'; // on affiche la liste de mois disponibles du visiteur
        require 'vues/v_choisirFichesValider.php';
    } else {
        if ($etat == 'VA') {// si elle est vide alors on affiche ces messages d'erreur 4.a
    	    ajouterErreur('Pas de fiche de frais validée pour ce visiteur');
        } else {
    	    ajouterErreur('Pas de fiche de frais à valider pour ce visiteur');
        }
        require 'vues/v_erreurs.php';
        require 'vues/v_choisirFichesOuvrir.php';
        require 'vues/v_choisirFichesChoixEtat.php';
        require 'vues/v_choisirFichesListeVisiteurs.php';
        require 'vues/v_choisirFichesValider.php';
    }

    break;
    /*Dans ce cas ci nous allons afficher les fiches de frais forfaitisés et hors forfait du mois du visiteur séléctionnés précédemment*/
case 'voirFicheFrais':
    $leMois = // ayant récupéré le mois de la fiche de frais on le stock dans une variable de session
    $_SESSION['leMois'] = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur,$leMois);//Nous récupérons toutes les données en rapport avec la fiche de frais à traiter et nous l'affichons
    require 'vues/v_choisirFichesOuvrir.php';
    require 'vues/v_choisirFichesChoixEtat.php';
    require 'vues/v_choisirFichesListeVisiteurs.php';
    require 'vues/v_choisirFichesListeMois.php';
    require 'vues/v_choisirFichesValider.php';
    require 'vues/v_correctionElementsForfaitises.php';
    require 'vues/v_correctionElementsHorsForfaits.php';
    break;
    /*Ce cas est celui de la mise à jour des lignes de frais hors forfait*/
case 'corrigerFraisHorsForfait':
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);//Tout d'abbord nous récupérons la valeur du bouton pressé
    $reponse = SupprimerLigne($id);     // nous récupérons les variables qui nous permettrons de savoir si on doit supprimer la/les ligne/s selectionnée/s
    $id = $reponse['id'];               //la/les reporter au mois suivant ou la corriger
    if ($id == $idVisiteur) {// si l'id récupéré est celui du visiteur alors ça veut dire que les boutons pressés sont ceux en rapport avec les checkbox
        foreach ($_POST['chk'] as $ligne) {
        	$lesLignes[$ligne]['montant'] = filter_input(INPUT_POST, $ligne.'montant', FILTER_SANITIZE_STRING);
        	$lesLignes[$ligne]['date'] = filter_input(INPUT_POST, $ligne.'date', FILTER_SANITIZE_STRING);
        	$lesLignes[$ligne]['libelle'] = filter_input(INPUT_POST, $ligne.'libelle', FILTER_SANITIZE_STRING);
        	$lesLignes[$ligne]['id'] = $ligne;
            //On remplit ici un tableau associatif définissant les lignes de frais hors forfait selectionnées
        }
        if ($reponse['isTrue']) {// Si le bouton pressé est celui de suppression
            $succes = 'Les lignes du : ';//alors
            foreach ($lesLignes as $ligne){//pour toutes les lignes sélectionnées
                $montant = $ligne['montant'];
                $date = dateFrancaisVersAnglais($ligne['date']);
                $libelle = estLibelleValide('REFUSE :'.$ligne['libelle']);  //8 - 8.b on va appliquer REFUSE : devant le libellé et vérifier qu'il ne dépasse pas la taille
                $idFrais = $ligne['id'];                                    //maximale autorisée
            	$pdo->majFraisHorsForfait($date,$libelle,$montant,$idFrais);// et mettre à jour chaque lignes dans la base de donnée
                $succes = $succes . $ligne['date'] .' ';
            }
            $succes = $succes . 'ont été supprimées.';
            ajouterSucces($succes);// on affiche que la suppression s'est bien déroulée
            include 'vues/v_succes.php';
        } else {// ici c'est le cas où l'on veut reporter la/les ligne/s selectionnée/s 7.a
            $moisSuivant = setMoisSuivant($leMois);// on définie ici le mois suivant de la fiche de frais
            $ficheMoisSuivant = $pdo -> getFicheFrais($idVisiteur,$moisSuivant);// on récupère cette fiche de frais
            if (!isset($ficheMoisSuivant[0])) {// et si elle n'existe pas
                $pdo ->creeNouvellesLignesFrais($idVisiteur,$moisSuivant);// on la crée
                $succes = 'Nouvelle fiche de frais créée.';// et on stock un message de succes
                ajouterSucces($succes);
            }
            foreach ($lesLignes as $ligne) {//Quoi qu'il arrive toutes les lignes sélectionnées vont être déplacées
                $idFrais = $ligne['id'];
                $pdo->moisSuivantHorsForfait($moisSuivant,$idFrais);// en changeant simplement le mois de la fiche de hors forfait
            }
            $succes = 'Les lignes de frais hors forfait ont été reportées.';//on ajoute un message de succes
            ajouterSucces($succes);
            include 'vues/v_succes.php';// et on affiche les messages de succès stockés
        }
    } else {
        //ce cas est celui de la modification d'une ligne de frais de hors forfait
        $montant = filter_input(INPUT_POST, $id.'montant', FILTER_SANITIZE_STRING);
        $date = filter_input(INPUT_POST, $id.'date', FILTER_SANITIZE_STRING);//on récupère les nouvelles informations de la ligne
        $libelle = filter_input(INPUT_POST, $id.'libelle', FILTER_SANITIZE_STRING);
        valideInfosFrais($date, $libelle, $montant);//on vérifie leurs validité
        $date = dateFrancaisVersAnglais($date);
        if (nbErreurs() != 0) {
            include 'vues/v_erreurs.php';// si elles ne sont pas valides on va alors afficher un message d'erreur
        } else {
            $pdo->majFraisHorsForfait($date,$libelle,$montant,$id);//sinon on va mettre à jour la base de donnée
            $succes = 'Les lignes de frais hors forfait ont été mis à jour.';
            ajouterSucces($succes);//et afficher un message de succès
            include 'vues/v_succes.php';
        }
    }
    break;
    /*Ce cas est celui de la validation de toute la fiche de frais*/
case 'validerFicheFrais' :
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);//pour celà nous devons récupérer les frais forfaitisés
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);// et les hors forfait
    $montantfrais = $pdo ->getLesMontantFrais();//récupérer les coefficients de paiement des frais forfaitisés
    //si un frais est refusé c'est dans la méthode ci-dessous que l'exception est notée
    $montant = getMontantValide($idVisiteur,$leMois,$lesFraisForfait,$lesFraisHorsForfait,$montantfrais);//et calculer ainsi le montant total des frais à rembourser
    $pdo -> majFicheFrais($idVisiteur,$leMois,$montant,'VA');//puis on met à jour la fiche dans la base de donnée et on passe la fiche à l'état Validé en attente de Rembousement
    ajouterSucces('La fiche de frais a été validée.');//on affiche ainsi un message de succès
    include 'vues/v_succes.php';
    require 'vues/v_choisirFichesOuvrir.php';
    require 'vues/v_choisirFichesChoixEtat.php';
    require 'vues/v_choisirFichesListeVisiteurs.php';
    require 'vues/v_choisirFichesValider.php';
    break;
}
if ($statut !='comptable') {
	$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur, $mois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idUtilisateur, $mois);
    require 'vues/v_listeFraisForfait.php';
    require 'vues/v_listeFraisHorsForfait.php';
} else {
    if ($action == 'validerMajFraisForfait'||$action == 'corrigerFraisHorsForfait') {//c'est ici qu'on affiche toute la page de validation des frais
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);//pendant que les données sont en cours de modification
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur,$leMois);
        require 'vues/v_choisirFichesOuvrir.php';
        require 'vues/v_choisirFichesChoixEtat.php';
        require 'vues/v_choisirFichesListeVisiteurs.php';
        require 'vues/v_choisirFichesListeMois.php';
        require 'vues/v_choisirFichesValider.php';
        require 'vues/v_correctionElementsForfaitises.php';
        require 'vues/v_correctionElementsHorsForfaits.php';
    }
}

