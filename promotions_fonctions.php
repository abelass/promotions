<?php
/**
 * Utilisations de pipelines par Promotions
 *
 * @plugin     Promotions
 * @copyright  2014
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Promotions\Fonctions
 */
if (! defined('_ECRIRE_INC_VERSION'))
	return;

/**
 * Retourne les champs spécifique d'une promotion.
 *
 * @param string $type_promotion Le type de promotion.
 * @param array $valeurs Des valeurs par défaut.
 * @options array options:
 *                - donnees_champs: si oui filtre le tableau pour obtenir uniquement les champs
 *                  spécifiques.

 * @return array Les champs de la promotion.
 */
	// Définition des champs
	function promotions_definition_saisies($type_promotion, $valeurs = array(), $options = array()) {

		// Chercher les fichiers promotions
		$promotions = find_all_in_path("promotions/", '^');

		$type_promotions_noms= array ();
		$promotions_defs = array ();

		$promotions_actives= isset($valeurs['promotions']) ? $valeurs['promotions'] : '';
		$rangs = isset($valeurs['rangs']) ? $valeurs['rangs'] : '';
		$nombre_promotions = isset($valeurs['nombre_promotions']) ? $valeurs['nombre_promotions'] : 0;

		if (is_array($promotions)) {
			foreach ($promotions as $fichier => $chemin) {
				list ($nom, $extension) = explode('.', $fichier);
				// Charger la définition des champs
				if ($defs = charger_fonction($nom, "promotions", true)) {
					$promotion = $defs($valeurs);
					if ($type_promotion == $nom and isset($promotion['saisies'])) {
						$promotions_defs = array (
							array (
								'saisie' => 'fieldset',
								'options' => array (
									'nom' => 'specifique',
									'label' => _T('promotion:label_parametres_specifiques')
								),
								'saisies' => $promotion['saisies']
							)
						);
					}
					// Lister les promotions dipsonibles
					if (isset($promotion['nom']))
						$type_promotions_noms[$nom] = $promotion['nom'];
				}
			}
		}
		if (isset($options['donnees_champs']) and $options['donnees_champs'] == 'oui') {
			$champs_specifiques = array_column($promotions_defs, 'saisies');
			$saisies = $champs_specifiques[0];
		}
		else {
			include_spip('inc/promotion');
			$saisies = promotions_champ_generaux($promotions_actives, $type_promotions_noms);
			$saisies = array_merge($saisies, $promotions_defs);
		}


		return $saisies;
	}