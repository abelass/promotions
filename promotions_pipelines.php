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
						$data['valeurs_promotion']=unserialize($data['valeurs_promotion']);
						$flux = $details($flux,$data);
					}						
			}

	return $flux;
}

/*function promotions_formulaire_traiter($flux){
	$form = $flux['args']['form'];
	if ($form=='reservation' AND $nombre=_request('nombre_auteurs')){
		$noms=array(_request('nom'));
		//Enregistrement des champs additionnels
		$enregistrer=charger_fonction('reservation_enregistrer','inc');
		
		//Lister les messages de retour
		preg_match('/<h3>(.*?)<\/h3>/s',$flux['data']['message_ok'], $match);		
		$titre=$match[0];
					
		preg_match('/<p(.*?)<\/p>/s',$flux['data']['message_ok'], $match);		
		$intro=$match[0];		
		preg_match('/<table(.*?)<\/table>/s',$flux['data']['message_ok'], $match);  
			
		$message_ok=array($match[0]);
		if(function_exists('champs_extras_objet')){
			$champs_extras_auteurs=champs_extras_objet(table_objet_sql('auteur'));
		}
		else $champs_extras_auteurs=array();
		// ne pas créer de compte spip
		set_request('enregistrer','');	
		$i = 1;
		while ($i <= $nombre) {
			//recupérer les champs par défaut
			$nr=$i++;
			set_request('nom',_request('nom_'.$nr));
			set_request('email',_request('email_'.$nr));		
			$noms[]	= _request('nom');
    		//Vérifier les champs extras
			foreach($champs_extras_auteurs as $key =>$value){
									
				// récupérer les champs extras					
				set_request($value['options']['nom'],_request($value['options']['nom'].'_'.$nr));
				}
			set_request('nr_auteur',$nr);
			
			//Enregistrer				
			$flux['data']=$enregistrer('','','',$champs_extras_auteurs);
			preg_match('/<table(.*?)<\/table>/s',$flux['data']['message_ok'], $match);  
			$message_ok[]=$match['0'];
			$nr=0;
		}
			//Recopiler les messages de retour
			$m=$intro;
			$m.=$titre;			
			foreach($message_ok AS $message){
				$m.="<h4>$noms[$nr]</h4>";				
				$m.=$message;				
				$nr++;
			}		
			$flux['data']['message_ok']=$m;
	}
	return $flux;
}*/
?>