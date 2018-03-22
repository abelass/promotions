<?php
/**
 * Utilisations de pipelines par Promotions
 *
 * @plugin     Promotions
 * @copyright  2014 - 2018
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Promotions\Inc\Promotions
 */
if (! defined('_ECRIRE_INC_VERSION'))
	return;

/**
 * Retourne les champs généraux d'une promotion.
 *
 * @param string $type_promotion Le type de promotion.
 * @param array $valeurs Des valeurs par défaut.
 *
 * @return array Les champs de la promotion.
 */
	// Définition des champs
	function promotions_champ_generaux($promotions_actives, $type_promotions) {

		return array (
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
							'label' => _T('forum:label_titre'),
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
							'datas' => $promotions_actives,
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
							'nom' => 'plugin_applicable',
							'label' => _T('promotion:label_plugin_applicable'),
							'obligatoire' => 'oui',
							'datas' => $plugin_applicable,
							'class' => 'auto_submit'
						)
					),
					array (
						'saisie' => 'selection',
						'options' => array (
							'nom' => 'type_promotion',
							'label' => _T('promotion:label_type_promotion'),
							'obligatoire' => 'oui',
							'datas' => $type_promotions
						)
					)
				)
			)
		);
	}