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

		$promotions_noms = array ();
		$promotions_defs = array ();

		$promotions_dispos = isset($valeurs['promotions']) ? $valeurs['promotions'] : '';
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
						$promotions_noms[$nom] = $promotion['nom'];
				}
			}
		}

		$saisies = array (
			array (
				'saisie' => 'fieldset',
				'options' => array (
					'nom' => 'general',
					'label' => _T('promotion:label_parametres_generales')
				),
				'saisies' => array (
					array (
						'saisie' => 'input',
						'options' => array (
							'nom' => 'titre',
							'label' => _T('promotion:label_titre'),
							'obligatoire' => 'oui'
						)
					),
					array (
						'saisie' => 'textarea',
						'options' => array (
							'nom' => 'descriptif',
							'label' => _T('promotion:label_descriptif'),
							'li_class' => 'haut',
							'class' => 'inserer_barre_edition'
						)
					),
					array (
						'saisie' => 'date',
						'options' => array (
							'nom' => 'date_debut',
							'horaire' => 'oui',
							'label' => _T('promotion:label_date_debut')
						)
					),
					array (
						'saisie' => 'date',
						'options' => array (
							'nom' => 'date_fin',
							'horaire' => 'oui',
							'label' => _T('promotion:label_date_fin')
						)
					),
					array (
						'saisie' => 'input',
						'options' => array (
							'nom' => 'reduction',
							'label' => _T('promotion:label_reduction'),
							'obligatoire' => 'oui'
						)
					),
					array (
						'saisie' => 'selection',
						'options' => array (
							'nom' => 'type_reduction',
							'label' => _T('promotion:label_type_reduction'),
							'datas' => array (
								'pourcentage' => _T('promotion:pourcentage'),
								'absolu' => _T('promotion:absolu')
							),
							'obligatoire' => 'oui'
						)
					),
					array (
						'saisie' => 'radio',
						'options' => array (
							'nom' => 'prix_base',
							'label' => _T('promotion:label_prix_base'),
							'explication' => _T('promotion:explication_prix_base'),
							'datas' => array (
								'prix_original' => _T('promotion:prix_original'),
								'prix_reduit' => _T('promotion:prix_reduit')
							),
							'obligatoire' => 'oui',
							'afficher_si' => '@type_reduction@ == "pourcentage"'
						)
					),
					array (
						'saisie' => 'selection_multiple',
						'options' => array (
							'nom' => 'non_cumulable',
							'label' => _T('promotion:label_non_cumulable'),
							'datas' => $promotions_dispos,
							'class' => 'chosen'
						)
					),
					/*array(
					 'saisie' => 'selection',
					 'options' => array(
					 'nom' => 'rang',
					 'label' => _T('promotion:label_rang'),
					 'datas'=>$rangs,
					 'obligatoire'=>'oui'
					 )
					 ),	*/
					array (
						'saisie' => 'selection',
						'options' => array (
							'nom' => 'type_promotion',
							'label' => _T('promotion:label_type_promotion'),
							'obligatoire' => 'oui',
							'datas' => $promotions_noms,
							'class' => 'auto_submit'
						)
					)
				)
			)
		);

		$saisies = array_merge($saisies, $promotions_defs);

		if (isset($options['donnees_champs']) and $options['donnees_champs'] == 'oui') {
			$saisies = array_column($saisies, 'saisies');
			$saisies = $saisies[1];
		}

		return $saisies;
	}