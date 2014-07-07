<?php

if (!defined("_ECRIRE_INC_VERSION")) return;

function action_ranger_dist($arg=''){
	include_spip('inc/autoriser');
	$securiser_action = charger_fonction('securiser_action', 'inc');
	$arg = $securiser_action();



	list($action,$lang,$id_selection_objet,$ordre,$objet_dest,$id_objet_dest,$load)=explode('-',$arg);


            
        //ranger avec drag and drop - liste des objets séléctionné sur la page de l'objet

            $ordre=explode(',',_request('ordre'));
            $rang=0;
            foreach($ordre AS $id_promotion){
               if($id_promotion) {
               	$rang++;
               	sql_updateq("spip_promotions", array("rang" => $rang),'id_promotion='.$id_promotion);
               }
               
                
            }
		include_spip('inc/invalideur');
        suivre_invalideur("id='promotion/$id_promotion'");    
        echo recuperer_fond('prive/objets/liste/promotions',$contexte);
		
		return $return;

}

?>
