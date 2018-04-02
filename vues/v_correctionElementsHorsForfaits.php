<?php
/**
* v_elementsHorsForfaits short summary.
*
* v_elementsHorsForfaits description.
*
* @version 1.0
* @author Mathieu
*/
?>
<br />
<form method="post"
      action="index.php?uc=gererFrais&action=corrigerFraisHorsForfait"
      role="form">
    <div class="panel panel-info">
        <div class="panel-heading secondary">
            Descriptif des <?php echo iconv('ISO-8859-1','UTF-8','éléments') ?> hors forfait

        </div>

        <div class="table table-color">
            <div class="tr">
                <span class="td td-color"><b>Date</b></span>
                <span class="td td-color"><b><?php echo iconv('ISO-8859-1','UTF-8','Libellé') ?></b></span>
                <span class="td td-color"><b>Montant</b></span>
                <span class="td td-color"></span>
            </div><?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $date = $unFraisHorsForfait['date'];
            $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
            $montant = $unFraisHorsForfait['montant'];
            $id = $unFraisHorsForfait['id'];
            $idVisiteur = $unFraisHorsForfait['idvisiteur'];
                  ?>

            <div class="tr" id="">

                <div class="td td-color">
                    <input type="text" id="date" name="<?php echo $id.'date'?>" value="<?php echo $date ?>" />
                </div>
                <div class="td td-color">
                    <input type="text" id="libelle" name="<?php echo $id.'libelle'?>" value="<?php echo $libelle ?>" />
                </div>
                <div class="td td-color">
                    <input type="text" id="montant" name="<?php echo $id.'montant'?>" value="<?php echo $montant ?>" />
                </div>
                <div class="td td-color">
                    <button class="btn btn-success" name="id" value="<?php echo $id ?>" type="submit">Corriger</button>
                    <button class="btn btn-danger" type="reset">
                        <?php echo iconv('ISO-8859-1','UTF-8','Réinitialiser') ?>
                    </button>
                    <input style="width : 30%" type="checkbox" name="chk[]" value="<?php echo $id ?>" />
                </div>
            </div><?php
            }
            ?>

        </div>
    </div>            
    <br />
    <button class="btn btn-success" name="id" value="<?php echo $idVisiteur ?>" type="submit">Reporter au mois suivant</button>
    <button class="btn btn-danger" name="id" value="<?php echo $idVisiteur.'Del' ?>" type="submit"><?php echo iconv('ISO-8859-1','UTF-8','Supprimer lignes sélectionnées') ?>
    </button>
</form>
<br />
<div>
    Nombre de justificatifs :
    <input value="<?php echo $nbJustificatifs ?>" />
</div>
<form method="post"
      action="index.php?uc=gererFrais&action=validerFicheFrais"
      role="form">
    <button style="float:right;" class="btn btn-success" type="submit">Valider la fiche de frais</button>
</form>

