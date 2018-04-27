<?php
if (! defined("_ECRIRE_INC_VERSION"))
	return;

	// Définition des champs pour le détail du formulaire promotion du plugin promotions (https://github.com/abelass/promotions)
function promotions_code_simple_dist($flux) {
	if (isset($flux['plugins_applicables']) and
		$plugins_applicables = $flux['plugins_applicables']) {

		$plugins_definitions = array(
			'reservation_evenement' => array(
				'label' => _T('reservation_evenement:reservation_evenement_titre'),
				'tables' => array(
					array(
						'base' => 'promotions_reservations',
						'table' => 'spip_reservations'
					),
				),
			),
		);

		$saisies = array();
		set_request('forcer', true);
		foreach($plugins_applicables as $index => $plugin) {
			$saisies[ $index] = array (
			'saisie' => 'fieldset',
			'options' => array (
				'nom' => 'plugin',
				'label' => $plugins_definitions[$plugin]['label'],
				'obligatoire' => 'oui',
			)
		);

			foreach ($plugins_definitions[$plugin]['tables'] as  $definition) {

				if (isset($definition['base']) and
					$base = $definition['base'] and
					isset($definition['table']) and
					$table = $definition['table'] and
					$objet = lister_tables_objets_sql($table)) {
						$saisies[ $index]['saisies'][] = array (
							'saisie' => 'input',
							'options' => array (
								'nom' => 'code_promotion_' .$plugin,
								'label' => _T('promotion:label_code'),
								'obligatoire' => 'oui',
							)
						);

						// Si le champ code_promotion manque, on l'ajoute
						if (!isset($objet['field']['code_promotion'])) {
							include_spip('base/' . $base);
							$function = $base . '_declarer_champs_extras';

							$champs = $function();
							$sql = isset($champs[$table]['code_promotion']['options']['sql']) ?
								$champs[$table]['code_promotion']['options']['sql'] :
								'';

							if ($sql) {
								sql_alter("TABLE $table ADD code_promotion $sql");
							}
						}
					}

			}
		}

		return array (
			'nom' => _T('promotion:nom_code_simple'),
			'saisies' => $saisies,
		);
	}
}

// Définition de l'action de la promotion
function promotions_code_simple_action_dist($flux, $promotion = array()) {
	if (isset($promotion['valeurs_promotion']['code_promotion']) &&
				trim($promotion['valeurs_promotion']['code_promotion']) == trim(_request('code_promotion'))
			) {
		$flux['data']['applicable'] = 'oui';
	}

	return $flux;
}
