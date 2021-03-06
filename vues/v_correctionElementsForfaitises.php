<?php
/**
 * Vue Liste des frais au forfait
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    R�seau CERTA <contact@reseaucerta.org>
 * @author    Jos� GIL <jgil@ac-nice.fr>
 * @copyright 2017 R�seau CERTA
 * @license   R�seau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte � Laboratoire GSB �
 */
?>
<div class="row">
    <h2 class="text-secondary">
        Valider la fiche de frais
    </h2>
    <h3><?php echo iconv('ISO-8859-1','UTF-8','El�ments forfaitis�s') ?></h3>
    <div class="col-md-3">
        <form method="post"
            action="index.php?uc=gererFrais&action=validerMajFraisForfait"
            role="form">
            <fieldset>
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite']; ?>
                <div class="form-group">
                    <label for="idFrais">
                        <?php echo $libelle ?>
                    </label>
                    <input type="text" id="idFrais"
                        name="lesFrais[<?php echo $idFrais ?>]"
                        size="10" maxlength="5"
                        value="<?php echo $quantite ?>"
                        class="form-control" />
                </div>
                <?php
                }
                ?>
                <button class="btn btn-success" type="submit">Corriger</button>
                <button class="btn btn-danger" value="Reset" type="reset"><?php echo iconv('ISO-8859-1','UTF-8','R�initialiser') ?></button>
            </fieldset>
        </form>
    </div>
</div>
