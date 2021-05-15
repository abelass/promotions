<?php
/**
 * Utilisations de pipelines par Promotions
 *
 * @plugin     Promotions
 * @copyright  2014 - 2021
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Promotions\Pipelines
 */
if (!defined('_ECRIRE_INC_VERSION'))
	return;

/**
 * Optimiser la base de données en supprimant les liens orphelins
 * de l'objet vers quelqu'un et de quelqu'un vers l'objet.
 *
 * @pipeline optimiser_base_disparus
 *
 * @param array $flux
 *        	Données du pipeline
 * @return array Données du pipeline
 */
function promotions_optimiser_base_disparus($flux) {
	include_spip('action/editer_liens');
	$flux['data'] += objet_optimiser_liens(array(
		'promotion' => '*'
	), '*');
	return $flux;
}


/**
 * Intervient après l'insetion d'un objet
 *
 * @pipeline post_insertion
 *
 * @param array $flux
 *        	Données du pipeline
 * @return array
 */
function promotions_post_insertion($flux) {
	$table = $flux['args']['table'];

	if ($donnees_promotion = _request('donnees_promotion') and $table == _request('table')) {

		$donnees_promotion['id_objet'] = $flux['args']['id_objet'];

		sql_insertq('spip_promotions_liens', $donnees_promotion);
	}
	;

	return $flux;
}

/**
 * Active des modules de jquery ui
 *
 * @pipeline jqueryui_plugins
 *
 * @param array $scripts
 *        	Données du pipeline
 * @return array
 */
function promotions_jqueryui_plugins($scripts) {
	$scripts[] = "jquery.ui.sortable";
	return $scripts;
}
