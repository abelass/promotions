<?php
if (! defined("_ECRIRE_INC_VERSION"))
	return;

	// Définition des champs pour le détail du formulaire promotion du plugin promotions (https://github.com/abelass/promotions)
function promotions_code_simple_dist($flux) {
	if (isset($flux['plugins_applicables']) and $plugins_applicables = $flux['plugins_applicables']) {
		foreach($plugins_applicables AS $plugin) {
			if ($plugin != 'commandes' and $objet = lister_tables_objets_sql('spip_' . $plugin)) {
				$saisies = array(
					array (
						'saisie' => 'input',
						'options' => array (
							'nom' => 'code_promotion',
							'label' => _T('promotion:label_code'),
							'obligatoire' => 'oui',
						)
					)
				);

				// nécessite un champ extra "code_promotion"
				if (!isset($objet['field']['code_promotion'])) {
					$saisies[]['options']['disable'] = 'disabled';
					$saisies[]['options']['explication'] = _T('promotion:explication_code_champ_manquant');
				}
				return array (
					'nom' => _T('promotion:nom_code_simple'),
					'saisies' => $saisies,
				);
			}
		}
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
