Promotions
==========

Permet de créer des promotions

## Compatibilité:

- __Réservations__ (Réservation d’événements - https://plugins.spip.net/reservation_evenement.html)
	avec le plugin __Promotion réservations__ (https://github.com/abelass/promotions_reservations)
- __Paniers__ ensemble avec __commandes__ en utilisant le plugin __Promotions commande__ (https://github.com/abelass/promotions_commandes)
	des promotions peuvent être déclarés dans un fichier promotions/ma_promotion.php

## Édition


Un formulaire permet de configurer ses promotions. Il est composé de champs généraux,
identiques à toutes les promotions. Ces champs contiennent entre autre une éventuelle date
limite de la promotion, ainsi que son montant et son type.

Si plusieurs promotions actives, ils sont par défaut cumulables. Ceci peut être modifé
dans l'édition de la promotion.

Chaque promotions peut également prévoir des champs spéficiques.

L'ordre d'éxecution des promotions dépend de son numéro titre.
Il peut être facilement modifié en déplacant une promotion dans la liste des promotions (drag & drop).
Si les promotions ne sont pas cumulables, seulement la première applicables sera exécutée.


## Étendre

Le plugin propose un API pour ajouter ses propres promotions.

Une promotion est définit dans un fichier `promotions/nomDeLaPromotion.php` qui contient
une fonction de définition des champs pour le formulaire d'édition, puis d'une fonction qui
définit l'action de la promotion.


```
// Définition des champs pour le détail du formulaire promotion du plugin promotions (https://github.com/abelass/promotions)
function promotions_nomDeLaPromotion_dist($flux) {
	return array (
		'nom' => _T('nomPlugin':Label');
	);
}


// Définition de l'action de la promotion
function promotions_nomDeLaPromotion_action_dist($flux, $promotion = array()) {
	$flux['data']['applicable'] = 'oui';
	return $flux;
}
```

Voir 2 examples:

- Promotion très basique, se basant sur les critères de base du plugins :
https://github.com/abelass/promotions/blob/master/promotions/promotion_simple.php
- Promotion plus complèxe : https://github.com/abelass/promotions_reservations/blob/master/promotions/multiples_evenements.php
	Cette promotion s'applique si il y a plusieurs réservation