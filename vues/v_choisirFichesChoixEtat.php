<?php

/**
 * v_choisirFichesChoixEtat short summary.
 *
 * v_choisirFichesChoixEtat description.
 *
 * @version 1.0
 * @author Mathieu
 */
?>
<div class="form-inline">
    <label for="rbEtat" accesskey="m">Choisir l'etat : </label>
    <div class="form-group">
        <input type="radio" name="rbEtat" <?php if (!isset($etat) || $etat=="CL") echo "checked";?> value="CL" />
        <?php echo iconv('ISO-8859-1','UTF-8','Saisie clôturée')?>
        <br />
        <input type="radio" name="rbEtat" <?php if (isset($etat) && $etat =="VA") echo "checked";?> value="VA" />
        <?php echo iconv('ISO-8859-1','UTF-8','Validée et mise en paiement')?>
    </div>

</div>