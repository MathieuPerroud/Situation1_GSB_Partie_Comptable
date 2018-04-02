<?php

/**
 * v_menu_comptable short summary.
 *
 * v_menu_comptable description.
 *
 * @version 1.0
 * @author Mathieu
 */
?>
<ul class="nav nav-pills secondary pull-right" role="tablist">
    <li <?php if (!$uc || $uc == 'accueil') { ?> class="active" <?php } ?>>
        <a href="index.php">
            <span class="glyphicon glyphicon-home"></span>
            Accueil
        </a>
    </li>
    <li <?php if ($uc == 'gererFrais') { ?> class="active" <?php } ?>>
        <a href="index.php?uc=gererFrais&action=validerFrais">
            <span class="glyphicon glyphicon-ok"></span>
            Valider les fiches de frais
        </a>
    </li>
    <li <?php if ($uc == 'etatFrais') { ?> class="active" <?php } ?>>
        <a href="index.php?uc=etatFrais&action=suiviPaiementFrais">
            <span class="glyphicon glyphicon-euro"></span>
            Suivre le paiement des fiches de frais
        </a>
    </li>
    <li
        <?php if ($uc == 'deconnexion') { ?> class="active" <?php } ?>>
        <a href="index.php?uc=deconnexion&action=demandeDeconnexion">
            <span class="glyphicon glyphicon-log-out"></span>
            <?php echo iconv('ISO-8859-1','UTF-8','Déconnexion') ?>
        </a>
    </li>
</ul>
