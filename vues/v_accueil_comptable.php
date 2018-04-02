<?php
/**
 * Vue Accueil Comptable
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Mathieu Perroud
 * @copyright 2017 R�seau CERTA
 * @license   R�seau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte � Laboratoire GSB �
 */
?>
<div id="accueil">
    <h2>
        Gestion des frais
        <small>
            - Comptable :
            <?php
            echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']
            ?>
        </small>
    </h2>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-secondary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-bookmark"></span>
                    Navigation
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <a href="index.php?uc=gererFrais&action=validerFrais"
                            class="btn btn-success btn-lg" role="button">
                            <span class="glyphicon glyphicon-ok"></span>
                            <br />Valider les fiches de frais
                        </a>
                        <a href="index.php?uc=etatFrais&action=suiviPaiementFrais"
                            class="btn btn-secondary btn-lg" role="button">
                            <span class="glyphicon glyphicon-euro"></span>
                            <br />Suivre le paiement des fiches de frais
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>