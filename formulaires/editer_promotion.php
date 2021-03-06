<?php
/**
 * Gestion du formulaire de d'édition de promotion
 *
 * @plugin     Promotions
 * @copyright  2014 - 2021
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Promotions\Formulaires
 */
if (!defined('_ECRIRE_INC_VERSION'))
	return;

include_spip('inc/actions');
include_spip('inc/editer');

/**
 * Identifier le formulaire en faisant abstraction des paramètres qui ne représentent pas l'objet edité
 *
 * @param int|string $id_promotion
 *        	Identifiant du promotion. 'new' pour un nouveau promotion.
 * @param string $retour
 *        	URL de redirection après le traitement
 * @param int $lier_trad
 *        	Identifiant éventuel d'un promotion source d'une traduction
 * @param string $config_fonc
 *        	Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *        	Valeurs de la ligne SQL du promotion, si connu
 * @param string $hidden
 *        	Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return string Hash du formulaire
 */
function formulaires_editer_promotion_identifier_dist($id_promotion = 'new', $retour = '', $lier_trad = 0, $config_fonc = '', $row = array(), $hidden = '') {
	return serialize(array(
		intval($id_promotion)
	));
}

/**
 * Chargement du formulaire d'édition de promotion
 *
 * Déclarer les champs postés et y intégrer les valeurs par défaut
 *
 * @uses formulaires_editer_objet_charger()
 *
 * @param int|string $id_promotion
 *        	Identifiant du promotion. 'new' pour un nouveau promotion.
 * @param string $retour
 *        	URL de redirection après le traitement
 * @param int $lier_trad
 *        	Identifiant éventuel d'un promotion source d'une traduction
 * @param string $config_fonc
 *        	Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *        	Valeurs de la ligne SQL du promotion, si connu
 * @param string $hidden
 *        	Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array Environnement du formulaire
 */
function formulaires_editer_promotion_charger_dist($id_promotion = 'new', $retour = '', $lier_trad = 0, $config_fonc = '', $row = array(), $hidden = '') {
	include_spip('inc/saisies');
	$valeurs = formulaires_editer_objet_charger('promotion', $id_promotion, '', $lier_trad, $retour, $config_fonc, $row, $hidden);

	$type_promotion = _request('type_promotion') ?
		_request('type_promotion') :
		(isset($valeurs['type_promotion']) ?
			$valeurs['type_promotion'] :
			''
		);
	$valeurs['non_cumulable'] = _request('non_cumulable') ?
	_request('non_cumulable') :
		(isset($valeurs['non_cumulable']) ?
			unserialize($valeurs['non_cumulable']) :
			''
		);
	$valeurs['plugins_applicables'] = _request('plugins_applicables') ?
		_request('plugins_applicables') :
		(
			isset($valeurs['plugins_applicables']) ?
			unserialize($valeurs['plugins_applicables']) :
			''
		);

	$valeurs_promotion = $valeurs['_valeurs_promotion'] = unserialize($valeurs['valeurs_promotion']);

	return $valeurs;
}

/**
 * Vérifications du formulaire d'édition de promotion
 *
 * Vérifier les champs postés et signaler d'éventuelles erreurs
 *
 * @uses formulaires_editer_objet_verifier()
 *
 * @param int|string $id_promotion
 *        	Identifiant du promotion. 'new' pour un nouveau promotion.
 * @param string $retour
 *        	URL de redirection après le traitement
 * @param int $lier_trad
 *        	Identifiant éventuel d'un promotion source d'une traduction
 * @param string $config_fonc
 *        	Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *        	Valeurs de la ligne SQL du promotion, si connu
 * @param string $hidden
 *        	Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array Tableau des erreurs
 */
function formulaires_editer_promotion_verifier_dist($id_promotion = 'new', $retour = '', $lier_trad = 0, $config_fonc = '', $row = array(), $hidden = '') {
	include_spip('inc/saisies');
	include_spip('public/assembler');

	$saisies = promotions_definition_saisies(_request('type_promotion'));
	$erreurs = saisies_verifier($saisies);

	$verifier = charger_fonction('verifier', 'inc');

	foreach (array(
		'date_debut',
		'date_fin'
	) as $champ) {
		if (_request($champ)) {
			$normaliser = null;
			if ($erreur = $verifier(_request($champ), 'date', array(
				'normaliser' => 'datetime'
			), $normaliser)) {
				$erreurs[$champ] = $erreur;
				// si une valeur de normalisation a ete transmis, la prendre.
			}
			elseif (!is_null($normaliser)) {
				set_request($champ, $normaliser);
				// si pas de normalisation ET pas de date soumise, il ne faut pas tenter d'enregistrer ''
			}
			else {
				set_request($champ, '0000-00-00 00:00:00');
			}
		}
	}

	if (_request('date_debut') > '0000-00-00 00:00:00' and _request('date_fin') > '0000-00-00 00:00:00' and _request('date_debut') >= _request('date_fin'))
		$erreurs['date_fin'] = _T('promotion:erreur_datefin');

	// Préparer les données multis pour l'enregistrement.
	if (!$erreurs) {
		$function = charger_fonction(_request('type_promotion'), "promotions", true);
		$saisies = $function(calculer_contexte());
		$valeurs_promotion = saisies_lister_par_nom(
			array(
				array(
					'saisie' => 'fieldset',
					'options' => array(
						'nom' => 'specifique',
						'label' => _T('promotion:label_parametres_specifiques')
					),
					'saisies' => $saisies['saisies']
				)
			));

		$promotion = array();
		foreach ($valeurs_promotion as $champ) {
			if ($request = _request($champ['options']['nom'])) {
				$promotion[$champ['options']['nom']] = $request;
			}
		}

		set_request('valeurs_promotion', serialize($promotion));

		$non_cumulable = is_array(_request('non_cumulable')) ? serialize(_request('non_cumulable')) : serialize(array());

		set_request('non_cumulable', $non_cumulable);

		if ($plugins_applicables = _request('plugins_applicables') and
					isset($plugins_applicables[0]) and
					!empty($plugins_applicables[0])
				) {
			set_request('plugins_applicables', serialize($plugins_applicables));
		}
		else {
			set_request('plugins_applicables', '');
		}
	}

	return $erreurs;
}

/**
 * Traitement du formulaire d'édition de promotion
 *
 * Traiter les champs postés
 *
 * @uses formulaires_editer_objet_traiter()
 *
 * @param int|string $id_promotion
 *        	Identifiant du promotion. 'new' pour un nouveau promotion.
 * @param string $retour
 *        	URL de redirection après le traitement
 * @param int $lier_trad
 *        	Identifiant éventuel d'un promotion source d'une traduction
 * @param string $config_fonc
 *        	Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *        	Valeurs de la ligne SQL du promotion, si connu
 * @param string $hidden
 *        	Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array Retours des traitements
 */
function formulaires_editer_promotion_traiter_dist($id_promotion = 'new', $retour = '', $lier_trad = 0, $config_fonc = '', $row = array(), $hidden = '') {
	return formulaires_editer_objet_traiter('promotion', $id_promotion, '', $lier_trad, $retour, $config_fonc, $row, $hidden);
}
