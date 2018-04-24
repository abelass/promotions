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
 * @param array options:
 *                - champs_specifiques: si oui filtre le tableau pour obtenir uniquement les champs
 *                  spécifiques.
 *
 * @return array Les champs de la promotion.
 */
function promotions_definition_saisies($type_promotion, $valeurs = array(), $options = array()) {
	include_spip('inc/promotion');

	// Les promotions actives.
	$where = array(
		'statut !="poubelle"'
	);
	if ($id_promotion = _request('id_promotion')) {
		$where[] = 'id_promotion!=' . _request('id_promotion');
	}

	$sql = sql_select('id_promotion,rang,titre', 'spip_promotions', $where, '', 'rang');

	$promotions_actives = array(
		'toutes' => _T('promotion:toutes_promotions')
	);
	while ($data = sql_fetch($sql)) {
		$promotions_actives[$data['id_promotion']] = $data['titre'];
	}

	$rangs = isset($valeurs['rangs']) ? $valeurs['rangs'] : '';
	$nombre_promotions = isset($valeurs['nombre_promotions']) ? $valeurs['nombre_promotions'] : 0;
	$plugins_applicables_selection = isset($valeurs['plugins_applicables']) ? $valeurs['plugins_applicables'] : '';

	$promotions = chercher_definitions_promotions($valeurs);

	if (is_array($promotions)) {
		// Chercher les fichiers promotions
		$type_promotions_noms = array();
		$promotions_saisies = array();

		foreach ($promotions as $nom => $definition) {
			if (isset($definition['saisies'])) {
				$promotions_saisies[$nom] = array(
					array(
						'saisie' => 'fieldset',
						'options' => array(
							'nom' => 'specifique',
							'label' => _T('promotion:label_parametres_specifiques')
						),
						'saisies' => $definition['saisies']
					)
				);
			}
		}
	}

	// Obtenir uniquement les champs spécifiques
	if (isset($options['champs_specifiques'])) {
		if ($type_promotion and isset($promotions_saisies[$type_promotion])) {
		$saisies = $promotions_saisies[$type_promotion];
		}
	}
	// Sinon les champs généraux
	else {
		include_spip('inc/promotion');
		$saisies = promotions_champ_generaux($promotions_actives, $plugins_applicables, $plugins_applicables_nom);
	}

	return $saisies;
}

/**
 * Établit les types de promotion disponibles selon les plugins applicables.
 *
 * @param array $plugins_applicables_selection
 *        	Les plugins sélectionnés.
 * @param $valeurs $plug
 *        	Les données du contexte.
 *
 * @return array
 */
function promotion_types_promotions($plugins_applicables, $valeurs) {
	include_spip('inc/promotion');
	$promotions = chercher_definitions_promotions($valeurs);

	if (is_array($promotions)) {

		// Chercher les fichiers promotions
		$types_promotions = array();
		$promotions_defs = array();
		$plugins_applicables_all = array();

		foreach ($promotions as $nom => $definition) {
			// Lister les promotions dipsonibles
			if (isset($definition['nom'])) {
				$types_promotions[$nom] = $definition['nom'];
			}

			if (isset($definition['plugins_applicables'])) {
				$plugins_applicables_all[$nom] = $definition['plugins_applicables'];
			}
		}
	}

	foreach (array_keys($types_promotions) as $type) {
		if ((isset($plugins_applicables_all[$type]) and $plugin = $plugins_applicables_all[$type]) and (!$plugins_applicables or !in_array($plugin, $plugins_applicables))) {
			unset($types_promotions[$type]);
		}
	}

	return $types_promotions;
}
