<?php
/**
 * Vue �tat de Frais
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
<form method="post"
      action="index.php?uc=etatFrais&action=rembourserFiche"
      role="form">
    <hr />
    <div class="panel panel-secondary">
        <div class="panel-heading">
            Fiche de frais du mois<?php echo $numMois . '-' . $numAnnee ?> :
        </div>
        <div class="panel-body">
            <strong>Etat :</strong><?php echo $libEtat ?>
            depuis le <?php echo $dateModif ?>
            <br />
            <strong>Montant <?php echo iconv('ISO-8859-1','UTF-8',' � valider') ?> :</strong> <input type="text" name="montant" value="<?php echo $montantAValider ?>" />
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading secondary"><?php echo iconv('ISO-8859-1','UTF-8','El�ments forfaitis�s') ?></div>
        <table class="table table-bordered table-responsive table-color">
            <tr>                <?php
                foreach ($lesFraisForfait as $unFraisForfait) {
                $libelle = $unFraisForfait['libelle']; ?>
                <th class="td-color"><?php echo htmlspecialchars($libelle) ?>
                </th>                <?php
                }
                ?>
            </tr>
            <tr>                <?php
                foreach ($lesFraisForfait as $unFraisForfait) {
                $quantite = $unFraisForfait['quantite']; ?>
                <td class="qteForfait td-color"><?php echo $quantite ?>
                </td>                <?php
                }
                ?>
            </tr>
        </table>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading secondary">
            Descriptif des <?php echo iconv('ISO-8859-1','UTF-8','�l�ments') ?> hors forfait -<?php echo $nbJustificatifs ?> justificatifs <?php echo iconv('ISO-8859-1','UTF-8','re�us') ?>
        </div>
        <table class="table table-bordered table-responsive table-color">
            <tr>
                <th class="date td-color">Date</th>
                <th class="libelle td-color"><?php echo iconv('ISO-8859-1','UTF-8','Libell�') ?></th>
                <th class='montant td-color'>Montant</th>
            </tr>            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $date = $unFraisHorsForfait['date'];
            $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
            $montant = $unFraisHorsForfait['montant']; ?>
            <tr>
                <td class="td-color"><?php echo $date ?>
                </td>
                <td class="td-color"><?php echo $libelle ?>
                </td>
                <td class="td-color"><?php echo $montant ?>
                </td>
            </tr>            <?php
            }
                             ?>
        </table>
    </div>
    <br />
    <button style="float:right;" class="btn btn-success" type="submit">Rembourser la fiche de frais</button>
</form>