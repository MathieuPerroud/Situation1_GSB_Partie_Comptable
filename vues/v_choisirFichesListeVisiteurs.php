<?php
/**
 * Vue Liste des mois
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
?>



            <br />
            <div class="form-group">
                <label for="lstVisiteurs" accesskey="n">Choisir le visiteur : </label>
                <select id="lstVisiteurs" name="lstVisiteurs" class="form-control">
                    <?php
                    foreach ($lesVisiteurs as $unVisiteur) 
                    {
                        $nom = $unVisiteur['nom'];
                        $prenom = $unVisiteur['prenom'];
                        $id = $unVisiteur['id'];
                        if ($id == $idVisiteur) 
                        {
                        ?>
                            <option selected value="<?php echo $id ?>"><?php echo $nom . ' ' . $prenom ?>
                            </option>
                        <?php
                        } 
                        else 
                        {
                        ?>
                            <option value="<?php echo $id ?>"><?php echo $nom . ' ' . $prenom ?>
                            </option><?php
                        }
                    }
                                     ?>
                </select>
            </div>
            <input id="okC" name="okC" type="submit" value="Rechercher" class="btn btn-success" 
                   role="button">



