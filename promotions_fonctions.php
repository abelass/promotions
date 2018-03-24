<?php
/**
 * Utilisations de pipelines par Promotions
 *
 * @plugin     Promotions
 * @copyright  2014 - 2018
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Promotions\Fonctions
 */
if (!defined('_ECRIRE_INC_VERSION'))
	return;

/**
 * Retourne les champs spécifique d'une promotion.
 *
 * @param string $type_promotion
 *        	Le type de promotion.
 * @param array $valeurs
 *        	Des valeurs par défaut.
 * @options array options:
 *                - champs_specifiques: si oui filtre le tableau pour obtenir uniquement les champs
 *                  spécifiques.
 *
 * @return array Les champs de la promotion.
 */
function promotions_definition_saisies($type_promotion, $valeurs = array(), $options = array()) {

	// Chercher les fichiers promotions
	$promotions = find_all_in_path("promotions/", '^');
	$type_promotions_noms = array ();
	$promotions_defs = array ();
	$plugins_applicables_nom = array ();

	$promotions_actives = isset($valeurs['promotions']) ? $valeurs['promotions'] : '';
	$rangs = isset($valeurs['rangs']) ? $valeurs['rangs'] : '';
	$nombre_promotions = isset($valeurs['nombre_promotions']) ? $valeurs['nombre_promotions'] : 0;
	$plugins_applicables_selection = isset($valeurs['plugins_applicables']) ? $valeurs['plugins_applicables'] : '';

	if (is_array($promotions)) {
		foreach ( $promotions as $fichier => $chemin ) {
			list ( $nom, $extension ) = explode('.', $fichier);
			// Charger la définition des champs
			$promotions_definitions = array ();
			if ($defs = charger_fonction($nom, "promotions", true)) {
				$promotion = $defs($valeurs);
				$promotions_definitions[$nom] = $promotion;
				if (isset($promotion['saisies'])) {
					$promotions_saisies[$nom] = array (
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
				if (isset($promotion['nom'])) {
					$type_promotions_noms[$nom] = $promotion['nom'];
				}

				if (isset($promotion['plugin_applicable'])) {
					$plugins_applicables_nom[$nom] = $promotion['plugin_applicable'];
				}
			}
		}
	}

	// Obtenir uniquement les champs spécifiques
	if (isset($options['champs_specifiques'])) {
		if ($type_promotion) {
			$saisies = $promotions_saisies[$type_promotion];
		}
	}	// Sinon les champs généraux
	else {
		include_spip('inc/promotion');
		$type_promotions = promotion_types_promotions($type_promotions_noms, $plugins_applicables_selection, $plugins_applicables_nom);
		$saisies = promotions_champ_generaux($promotions_actives, $type_promotions, $plugins_applicables, $plugins_applicables_nom);
	}

	return $saisies;
}