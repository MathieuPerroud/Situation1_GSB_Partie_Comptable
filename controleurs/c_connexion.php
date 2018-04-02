<?php
/**
 * Gestion de la connexion
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

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if (!$uc) {
    $uc = 'demandeconnexion';
}

switch ($action) {
case 'demandeConnexion':
    include 'vues/v_connexion.php';
    break;
case 'valideConnexion':
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);
    $statut = getStatutUtilisateur($login);//on récupère ici le statut, visiteur ou comptable de l'utilisateur
    $utilisateur = $pdo->getInfosUtilisateur($login,$mdp,$statut);  //on a remplacé ici la méthode getVisiteur par getUtilisateur pour mieux fit aux comptables
    if (!is_array($utilisateur)) {                                  //qui ont un login normalisé avec un C à la fin de leur login
        ajouterErreur('Login ou mot de passe incorrect');
        include 'vues/v_erreurs.php';
        include 'vues/v_connexion.php';
    } else {
        $id = $utilisateur['id'];
        $nom = $utilisateur['nom'];
        $prenom = $utilisateur['prenom'];
        connecter($id, $nom, $prenom,$statut);
        header('Location: index.php');
    }
    break;
default:
    include 'vues/v_connexion.php';
    break;
}
