<?php
if (! defined("_ECRIRE_INC_VERSION"))
	return;

// Définition des champs pour le détail du formulaire promotion du plugin promotions (https://github.com/abelass/promotions)
function promotions_promotion_simple_dist($flux = '') {
	return array (
		'nom' => _T('promotion:nom_promotion_simple')
	);
}

// Définition de l'action de la promotion
function promotions_promotion_simple_action_dist($flux, $promotion = array()) {

	$flux['data']['applicable'] = 'oui';

	return $flux;
}
