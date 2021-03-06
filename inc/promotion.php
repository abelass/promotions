<?php
/**
 * Utilisations de pipelines par Promotions
 *
 * @plugin     Promotions
 * @copyright  2014 - 2021
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Promotions\Inc\Promotions
 */
if (! defined('_ECRIRE_INC_VERSION'))
	return;

/**
 * Retourne les champs généraux d'une promotion.
 *
 * @param string $promotions_actives
 *        	Les mpromotions nactives.
 * @param array $plugins_applicables
 *        	Les plugins applicables par définition.
 *
 * @return array Les champs de la promotion.
 */
function promotions_champ_generaux($promotions_actives, $plugins_applicables) {
	if (isset($GLOBALS['promotion_plugin']) && count($GLOBALS['promotion_plugin']) > 0) {
		$plugins_applicables = array(
			'saisie' => 'selection_multiple',
			'options' => array(
				'nom' => 'plugins_applicables',
				'label' => _T('promotion:label_plugin_applicable'),
				'datas' => $GLOBALS['promotion_plugin'],
			)
		);
	}

	return array(
		array(
			'saisie' => 'fieldset',
			'options' => array(
				'nom' => 'general',
				'label' => _T('promotion:label_parametres_generales')
			),
			'saisies' => array(
				array(
					'saisie' => 'input',
					'options' => array(
						'nom' => 'titre',
						'label' => _T('ecrire:info_titre'),
						'obligatoire' => 'oui'
					)
				),
				array(
					'saisie' => 'textarea',
					'options' => array(
						'nom' => 'descriptif',
						'label' => _T('promotion:label_descriptif'),
						'li_class' => 'haut',
						'class' => 'inserer_barre_edition'
					)
				),
				array(
					'saisie' => 'date',
					'options' => array(
						'nom' => 'date_debut',
						'horaire' => 'oui',
						'label' => _T('promotion:label_date_debut')
					)
				),
				array(
					'saisie' => 'date',
					'options' => array(
						'nom' => 'date_fin',
						'horaire' => 'oui',
						'label' => _T('promotion:label_date_fin')
					)
				),
				array(
					'saisie' => 'input',
					'options' => array(
						'nom' => 'reduction',
						'label' => _T('promotion:label_reduction'),
						'obligatoire' => 'oui'
					)
				),
				array(
					'saisie' => 'selection',
					'options' => array(
						'nom' => 'type_reduction',
						'label' => _T('promotion:label_type_reduction'),
						'data' => array(
							'pourcentage' => _T('promotion:pourcentage'),
							'absolu' => _T('promotion:absolu')
						),
						'obligatoire' => 'oui'
					)
				),
				array(
					'saisie' => 'radio',
					'options' => array(
						'nom' => 'prix_base',
						'label' => _T('promotion:label_prix_base'),
						'explication' => _T('promotion:explication_prix_base'),
						'data' => array(
							'prix_original' => _T('promotion:prix_original'),
							'prix_reduit' => _T('promotion:prix_reduit')
						),
						'obligatoire' => 'oui',
						'afficher_si' => '@type_reduction@ == "pourcentage"'
					)
				),
				array(
					'saisie' => 'selection_multiple',
					'options' => array(
						'nom' => 'non_cumulable',
						'label' => _T('promotion:label_non_cumulable'),
						'data' => $promotions_actives,
						'class' => 'chosen'
					)
				),
				$plugins_applicables
			)
		)
	);
}

/**
 * Cherche les définitions des promotions disponibles.
 *
 * @param array $valeurs
 *
 * @return array]
 */
function chercher_definitions_promotions($valeurs) {
	$definitions_promotions = find_all_in_path("promotions/", '^');
	$promotions = array();
	if (is_array($definitions_promotions)) {
		foreach ($definitions_promotions as $fichier => $chemin) {
			list ($nom, $extension) = explode('.', $fichier);
			// Charger la définition des champs

			if ($defs = charger_fonction($nom, "promotions", true)) {
				if (is_string($valeurs)) {
					$valeurs = unserialize($valeurs);
				}
				$promotion = $defs($valeurs);
				$promotions[$nom] = $promotion;
			}
		}
	}

	return $promotions;
}

/**
 * Retourne un tableau des promotions enregistrés
 *
 * @param array $options
 * @return array
 */
function promotions_enregistres($options = array()) {
	$select = '*';
	$where = 'statut = "publie"';
	$groupby = '';
	$orderby = 'rang';
	$limit = '';

	// Surcharge des valeurs par défaut si défini dans $options.
	foreach ($options as $option => $valeur) {
		$$option = $valeur;
	}

	$sql = sql_select($select, 'spip_promotions', $where, $groupby, $orderby, $limit);

	$promotions = array();
	$i = 0;
	while ($data = sql_fetch($sql)) {
		$index = $i;
		$i ++;
		if (isset($data['id_promotion'])) {
			$index = $data['id_promotion'];
		}

		$promotions[$index] = $data;
	}

	return $promotions;
}

/**
 * Détermine si la promotion code_simple est actif pour un plugin déterminé
 *
 * @param string $plugin
 *        	Nom du plugin
 *
 * @return boolean
 */
function promotion_code_simple_actif_plugin($formulaire) {
	$code_simple_actif = false;

	if (!$code_simple_actif = _request('forcer')) {
		$promotions_actives = promotions_enregistres(array(
			'select' => 'valeurs_promotion',
			'where' => array(
				'type_promotion="code_simple"',
				'statut="publie"'
			)
		));

		//print 'code_promotion_' . $formulaire;
		foreach ($promotions_actives as $promotion) {
			$valeurs_promotion = unserialize($promotion['valeurs_promotion']);
			if (isset($valeurs_promotion['code_promotion_' .$formulaire])) {
				$code_simple_actif = true;
				break;
			}
		}
	}

	return $code_simple_actif;
}
