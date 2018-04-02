<?php
/**
 * Fonctions pour l'application GSB
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */
use PHPUnit_Framework_TestListener;

/**
 * Teste si un quelconque visiteur est connecté
 *
 * @return// vrai ou faux
 */
function estConnecte()
{
    return isset($_SESSION['idUtilisateur']);
}

/**
 * Enregistre dans une variable session les infos d'un utilisateur
 *
 * @param String $idUtilisateur ID de l'utilisateur
 * @param String $nom        Nom de l'utilisateur
 * @param String $prenom     Prénom de l'utilisateur
 * @param String $statut     Statut de l'utilisateur
 *
 * @return null
 */
function connecter($idUtilisateur, $nom, $prenom, $statut)
{
    $_SESSION['idUtilisateur'] = $idUtilisateur;
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
    $_SESSION['statut'] = $statut;

}

/**
 * Détruit la session active
 *
 * @return null
 */
function deconnecter()
{
    session_destroy();
}

/**
 * Transforme une date au format français jj/mm/aaaa vers le format anglais
 * aaaa-mm-jj
 *
 * @param String $maDate au format  jj/mm/aaaa
 *
 * @return //Date au format anglais aaaa-mm-jj
 */
function dateFrancaisVersAnglais($maDate)
{
    @list($jour, $mois, $annee) = explode('/', $maDate);
    return date('Y-m-d', mktime(0, 0, 0, $mois, $jour, $annee));
}

/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format
 * français jj/mm/aaaa
 *
 * @param String $maDate au format  aaaa-mm-jj
 *
 * @return //Date au format format français jj/mm/aaaa
 */
function dateAnglaisVersFrancais($maDate)
{
    @list($annee, $mois, $jour) = explode('-', $maDate);
    $date = $jour . '/' . $mois . '/' . $annee;
    return $date;
}

/**
 * Retourne le mois au format aaaamm selon le jour dans le mois
 *
 * @param String $date au format  jj/mm/aaaa
 *
 * @return String Mois au format aaaamm
 */
function getMois($date)
{
    @list($jour, $mois, $annee) = explode('/', $date);
    unset($jour);
    if (strlen($mois) == 1) {
        $mois = '0' . $mois;
    }
    return $annee . $mois;
}

/* gestion des erreurs */

/**
 * Indique si une valeur est un entier positif ou nul
 *
 * @param Integer $valeur Valeur
 *
 * @return Boolean vrai ou faux
 */
function estEntierPositif($valeur)
{
    return preg_match('/[^0-9]/', $valeur) == 0;
}

/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 *
 * @param Array $tabEntiers Un tableau d'entier
 *
 * @return Boolean vrai ou faux
 */
function estTableauEntiers($tabEntiers)
{
    $boolReturn = true;
    foreach ($tabEntiers as $unEntier) {
        if (!estEntierPositif($unEntier)) {
            $boolReturn = false;
        }
    }
    return $boolReturn;
}

/**
 * Vérifie si une date est inférieure d'un an à la date actuelle
 *
 * @param String $dateTestee Date à tester
 *
 * @return Boolean vrai ou faux
 */
function estDateDepassee($dateTestee)
{
    $dateActuelle = date('d/m/Y');
    @list($jour, $mois, $annee) = explode('/', $dateActuelle);
    $annee--;
    $anPasse = $annee . $mois . $jour;
    @list($jourTeste, $moisTeste, $anneeTeste) = explode('/', $dateTestee);
    return ($anneeTeste . $moisTeste . $jourTeste < $anPasse);
}

/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa
 *
 * @param String $date Date à tester
 *
 * @return Boolean vrai ou faux
 */
function estDateValide($date)
{
    $tabDate = explode('/', $date);
    $dateOK = true;
    if (count($tabDate) != 3) {
        $dateOK = false;
    } else {
        if (!estTableauEntiers($tabDate)) {
            $dateOK = false;
        } else {
            if (!checkdate($tabDate[1], $tabDate[0], $tabDate[2])) {
                $dateOK = false;
            }
        }
    }
    return $dateOK;
}

/**
 * Vérifie que le tableau de frais ne contient que des valeurs numériques
 *
 * @param Array $lesFrais Tableau d'entier
 *
 * @return Boolean vrai ou faux
 */
function lesQteFraisValides($lesFrais)
{
    return estTableauEntiers($lesFrais);
}

/**
 * Vérifie la validité des trois arguments : la date, le libellé du frais
 * et le montant
 *
 * Des message d'erreurs sont ajoutés au tableau des erreurs
 *
 * @param String $dateFrais Date des frais
 * @param String $libelle   Libellé des frais
 * @param Float  $montant   Montant des frais
 *
 * @return null
 */
function valideInfosFrais($dateFrais, $libelle, $montant)
{
    if ($dateFrais == '') {
        ajouterErreur('Le champ date ne doit pas être vide');
    } else {
        if (!estDatevalide($dateFrais)) {
            ajouterErreur('Date invalide');
        } else {
            if (estDateDepassee($dateFrais)) {
                ajouterErreur(
                    "date d'enregistrement du frais dépassé, plus de 1 an"
                );
            }
        }
    }
    if ($libelle == '') {
        ajouterErreur('Le champ description ne peut pas être vide');
    }
    if ($montant == '') {
        ajouterErreur('Le champ montant ne peut pas être vide');
    } elseif (!is_numeric($montant)) {
        ajouterErreur('Le champ montant doit être numérique');
    }
}

/**
 * Ajoute le libellé d'une erreur au tableau des erreurs
 *
 * @param String $msg Libellé de l'erreur
 *
 * @return null
 */
function ajouterErreur($msg)
{
    if (!isset($_REQUEST['erreurs'])) {
        $_REQUEST['erreurs'] = array();
    }
    $_REQUEST['erreurs'][] = $msg;
}
/**
 * Ajoute le libellé d'un succes au tableau des succes
 *
 * @param String $msg Libellé du succes
 *
 * @return null
 */
function ajouterSucces($msg)
{
    if (!isset($_REQUEST['succes'])) {
        $_REQUEST['succes'] = array();
    }
    $_REQUEST['succes'][] = $msg;
}

/**
 * Retoune le nombre de lignes du tableau des erreurs
 *
 * @return Integer le nombre d'erreurs
 */
function nbErreurs()
{
    if (!isset($_REQUEST['erreurs'])) {
        return 0;
    } else {
        return count($_REQUEST['erreurs']);
    }
}
/**
 * Retoune le nombre de lignes du tableau des succes
 *
 * @return Integer le nombre de succes
 */
function nbSucces()
{
    if (!isset($_REQUEST['succes'])) {
        return 0;
    } else {
        return count($_REQUEST['succes']);
    }
}
/**
 * Retoune le statut de l'utilisateur
 *
 * @param String $login Login de l'utilisateur
 *
 * @return String le statut de l'utilisateur
 */
function getStatutUtilisateur($login)
{
    if ($login[strlen($login)-1] == 'C') {
    	return'comptable';
    }  else {
    	return'visiteur';
    }
}
/**
 * Retoune l'action à effectuer au moment du choix de la fiche à traiter
 * Selon la valeur du bouton pressé, ce qui permet de garder toutes ces variables dans
 * un seul formulaire
 *
 * @param String $value     valeur du bouton pressé
 *
 * @return String l'action à effectuer
 */
function setAction($value)
{
    if($value == 'Afficher') {
        return 'voirFicheFrais';
    } elseif ($value == 'Rechercher') {
        return 'choixFiche';
    }
}
/**
 * Récupère la valeur du bouton pressé sous forme d'un id
 * si 'Del' est en suffixe celà signifie que c'est le bouton qui
 * commande la suppression des lignes sélectionnées
 *
 * @param String $id     valeur de l'id du bouton pressé
 *
 * @return Array    tableau associatif contenant les informations de ciblage
 *                  de l'action à effectuer
 */
function SupprimerLigne($id)
{

    if ($id[strlen($id)-3].$id[strlen($id)-2].$id[strlen($id)-1] == 'Del') {
        $reponse['isTrue'] = true;
        $reponse['id'] = substr($id,0, -3);
    	return $reponse;
    } else {
        $reponse['isTrue'] = false;
        $reponse['id'] = $id;
    	return $reponse;
    }
}
/**
 * Récupère le libellé en paramètre et vérifie que sa longueur n'excède pas les
 * 100 caractères, si c'est le cas on le tronque par la fin
 *
 * @param String $libelle     le libellé à rentrer dans la base de donnée
 *
 * @return String             le libellé aux normes
 */
function estLibelleValide($libelle)
{
    $longeur = strlen($libelle);
    if ($longeur>100) {
    	return substr($libelle,0, 99 - $longeur);
    } else {
        return $libelle;
    }

}
/**
 * Récupère un mois et définie le mois suivant en prenant en compte le
 * changement potentiel d'année
 *
 * @param String $mois     le mois à incrémenter
 *
 * @return String          le mois incrémenté
 */
function setMoisSuivant($mois)
{
    $annee = substr($mois,0,-2);
    $leMois = substr($mois,-2);
    if ($leMois == '12') {
    	return intval($annee + 1).'01';
    } else {
        if (intval($leMois+1)>10){
            return $annee.intval($leMois+1);
        } else {
            return $annee.'0'.intval($leMois+1);
        }
    }
}
/**
 * Cette fonction permet de calculer le montant exact des frais à rembourser
 * selon les informations que le comptable aura mis à jour dans la section
 * 'Valider fiches de frais'
 * On gère ici l'exception du refus de comptabilité d'un frais 'REFUSE :'
 *
 * @param String $idVisiteur            l'Id du visiteur
 * @param String $mois                  le mois de la fiche de frais
 * @param Array  $fraisForfait          un Tableau associatif contenant les frais forfaitisés de la fiche du mois du visiteur
 * @param Array  $fraisHorsForfait      un Tableau associatif contenant les frais hors forfait de la fiche du mois du visiteur
 * @param Array  $montantFrais          un Tableau associatif contenant les coefficiants des frais forfaitisés
 *
 * @return String          le mois incrémenté
 */
function getMontantValide($idVisiteur, $mois,$fraisForfait, $fraisHorsForfait, $montantFrais)
{
    $montantValide = 0;
    foreach ($fraisForfait as $unFrais) {
        foreach ($montantFrais as $montant)
        {
        	if($unFrais['idfrais'] == $montant['idfrais']){
                if (substr($unFrais['libelle'],0,6) != 'REFUSE')
                {
                    $montantValide = $montantValide + ($montant['coeff']*$unFrais['quantite']);
                }
            }
        }
    }
    foreach ($fraisHorsForfait as $unFraisHorsForfait) {
        $montantValide = $montantValide + $unFraisHorsForfait['montant'];
    }

    return $montantValide;
}
