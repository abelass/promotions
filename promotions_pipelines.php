<?php
/**
 * Utilisations de pipelines par Promotions
 *
 * @plugin     Promotions
 * @copyright  2014 - 2018
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Promotions\Pipelines
 */
if (! defined('_ECRIRE_INC_VERSION'))
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
	$flux['data'] += objet_optimiser_liens(array (
		'promotion' => '*'
	), '*');
	return $flux;
}

/**
 * Intervenir sur les détails d'une réservation du plug reservation_evenement
 *
 * @pipeline evenement_donnees_detail
 *
 * @param array $flux
 *        	Données du pipeline
 * @return array
 */
function promotions_reservation_evenement_donnees_details($flux) {
	if (! _request('exec')) {
		$date = date('Y-m-d H:i:s');
		$sql = sql_select('*', 'spip_promotions', 'statut=' . sql_quote('publie'), '', 'rang');
		$evenements_exclus = _request('evenements_exclus') ? _request('evenements_exclus') : array ();
		$id_evenement = $flux['data']['id_evenement'];

		while ($data = sql_fetch($sql)) {

			$non_cumulable = isset($data['non_cumulable']) ? unserialize($data['non_cumulable']) : array ();
			$id_promotion = $data['id_promotion'];
			$evenements_exclus_promotion = isset($evenements_exclus[$id_promotion]) ? $evenements_exclus[$id_promotion] : array ();
			$exclure_toutes = (isset($evenements_exclus['toutes'])) ? $evenements_exclus['toutes'] : '';
			if ($details = charger_fonction('action', 'promotions/' . $data['type_promotion'], true)
					and ($data['date_debut'] == '0000-00-00 00:00:00' or $data['date_debut'] <= $date)
					and ($data['date_fin'] == '0000-00-00 00:00:00' or $data['date_fin'] >= $date)
					and ! in_array($id_evenement, $evenements_exclus_promotion)
					and (! $exclure_toutes or ($exclure_toutes and $exclure_toutes[0] == $id_promotion))) {

				// Essaie de trouver le prix original
				$flux['data']['prix_original'] = isset($flux['data']['prix_original']) ? $flux['data']['prix_original'] : $flux['data']['prix_ht'];
				$data['valeurs_promotion'] = unserialize($data['valeurs_promotion']);

				// Pour l'enregistrement de la promotion
				$flux['data']['objet'] = 'reservations_detail';
				$flux['data']['table'] = 'spip_reservations_details';

				$reduction = $data['reduction'];
				$type_reduction = $data['type_reduction'];
				$flux['data']['applicable'] = 'non';

				// On passe à la fonction de la promotion pour établir si la promotion s'applique
				$flux = $details($flux, $data);

				// Si oui on modifie le prix
				if ($flux['data']['applicable'] == 'oui') {
					if (is_array($non_cumulable)) {
						foreach ($non_cumulable as $nc) {
							$evenements_exclus[$nc][] = $id_evenement;
							if ($nc == 'toutes')
								$evenements_exclus[$nc][0] = $id_promotion;
						}
					}
					set_request('evenements_exclus', $evenements_exclus);

					// On applique les réductions prévues
					// En pourcentage
					if ($type_reduction == 'pourcentage') {
						// Prix de base
						if (isset($data['prix_base'])) {
							if ($data['prix_base'] == 'prix_reduit')
								$prix_base = $flux['data']['prix_ht'];
							elseif ($data['prix_base'] == 'prix_original')
								$prix_base = $flux['data']['prix_original'];
						}

						if($flux['data']['prix_ht'] > 0) {
							$flux['data']['prix_ht'] = $flux['data']['prix_ht'] - ($prix_base / 100 * $reduction);
						}
						else {
							$flux['data']['prix_ht'] = 0;
						}
						$flux['data']['prix'] = 0;
					} // En absolu
					elseif ($type_reduction == 'absolu')
					if ($flux['data']['prix_ht'] > 0) {
						$flux['data']['prix_ht'] = $flux['data']['prix_ht'] - $reduction;
						$flux['data']['prix'] = 0;
					}
				}

				// On prépare l'enregistrement de la promotion
				set_request('donnees_promotion', array (
					'id_promotion' => $data['id_promotion'],
					'objet' => $flux['data']['objet'],
					'prix_original' => $flux['data']['prix_original'],
					'prix_promotion' => $flux['data']['prix_ht']
				));
				// On passe le nom de la table pour la pipeline post_insertion
				set_request('table', $flux['data']['table']);
			}
			else
				set_request('donnees_promotion', '');
		}
	}

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
function promotions_jqueryui_plugins($scripts) {
	$scripts[] = "jquery.ui.sortable";
	return $scripts;
}
