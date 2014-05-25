<?php
/**
 * Utilisations de pipelines par Promotions
 *
 * @plugin     Promotions
 * @copyright  2014
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Promotions\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;
	

/*
 * Un fichier de pipelines permet de regrouper
 * les fonctions de branchement de votre plugin
 * sur des pipelines existants.
 */




/**
 * Optimiser la base de données en supprimant les liens orphelins
 * de l'objet vers quelqu'un et de quelqu'un vers l'objet.
 *
 * @pipeline optimiser_base_disparus
 * @param  array $flux Données du pipeline
 * @return array       Données du pipeline
 */
function promotions_optimiser_base_disparus($flux){
	include_spip('action/editer_liens');
	$flux['data'] += objet_optimiser_liens(array('promotion'=>'*'),'*');
	return $flux;
}

//Intervenir sur les détails d'une réservation du plug reservation_evenement
function promotions_reservation_evenement_donnees_details($flux){
	
	$date=date('Y-m-d H:i:s');
	$sql=sql_select('*','spip_promotions','statut='.sql_quote('publie'));

		while ($data=sql_fetch($sql)){
			if(
				$details = charger_fonction('action', 'promotions/'.$data['type_promotion'], true) 
				AND
		 		($data['date_debut']=='0000-00-00 00:00:00' OR ($data['date_debut']!='0000-00-00 00:00:00' AND $data['date_debut']<=$date))
				AND
				($data['date_fin']=='0000-00-00 00:00:00' OR ($data['date_fin']!='0000-00-00 00:00:00' AND $data['date_fin']>=$date))
					){
						$flux['data']['prix_original']=$flux['data']['prix_ht'];
						$data['valeurs_promotion']=unserialize($data['valeurs_promotion']);
						
						//On passe à la fonction de lam promotion
						$flux = $details($flux,$data);
						
						//On prépare l'enregistrement de la promotion
						set_request('donnees_promotion',array(
								'id_promotion'=>$data['id_promotion'],
								'objet'=>$flux['data']['objet'],					
								'prix_original'=>$flux['data']['prix_original'],						
								'prix_promotion'=>$flux['data']['prix_ht']
								)							
							);
						//On passe le nom de la table pour la pipeline post_insertion	
						set_request('table',$flux['data']['table']);
						
						}
			else set_request('donnees_promotion','');
									
			}

	return $flux;
}

//Enregistrement des détails de la promotion accordée

function promotions_post_insertion($flux){
	$table=$flux['args']['table'];
	
	if($donnees_promotion=_request('donnees_promotion') AND $table==_request('table')){
		$promotions=_request('promotions')?_request('promotions'):array();
		$donnees_promotion['id_objet']=$flux['args']['id_objet'];
		
		//On liste toutes
		set_request('promotions',array_merge($promotions,array($donnees_promotion)));	

		sql_insertq('spip_promotions_liens',$donnees_promotion);		
	};

return $flux;	
}

/*function promotions_formulaire_traiter($flux){
	$form = $flux['args']['form'];
	if ($form=='reservation' AND $promotions=_request('promotions')){
		
		$promotion=$promotions[0];
		
		$sql=sql_select(
			'prix_original,titre',
			'spip_promotions_liens,spip_promotions',
			'id_objet='.$promotion['id_objet'].' AND objet='.sql_quote($promotion['objet']));
		$i=0;
		$titre=array();
		while($data=sql_fetch($sql)){
			$i++;			
			if($i==1)$prix_original=$data['prix_original'];
			$titre[]=extraire_multi($data['titre']);
		};

		
		//Afficher la promotion
		$replace=recuperer_fond('inclure/promotion_reservation',array('prix_original'=>$prix_original,'titre'=>implode(', ',$titre)));
					echo $replace;
		
		$message_ok=str_replace('<tr class="total_ttc">', $replace.'<tr class="total_ttc">', $flux['data']['message_ok']);
		$flux['data']['message_ok']=$message_ok;
	}
	return $flux;
}*/
?>