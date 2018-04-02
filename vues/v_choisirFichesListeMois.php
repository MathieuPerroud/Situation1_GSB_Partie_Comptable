<?php
/**
 * Vue Liste des mois
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
                <label for="lstMois" accesskey="n">Mois : </label>
                <select id="lstMois" name="lstMois" class="form-control">
                    <?php
                    foreach ($lesMois as $unMois) {
                        $mois = $unMois['mois'];
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        if ($mois == $leMois) {
                    ?>
                            <option selected value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        }
                    }
                            ?>    
                </select>
            <input id="okC" name="okC" type="submit" value="Afficher"  class="btn btn-success" 
            role="button">
