<?php
/**
 * Gestion du formulaire de d'édition de promotion
 *
 * @plugin     Promotions
 * @copyright  2014
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Promotions\Formulaires
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('inc/actions');
include_spip('inc/editer');

// Définition des champs
function definition_saisies($type_promotion,$valeurs=array()){
	
	//Chercher les fichiers promotions
 	$promotions = find_all_in_path("promotions/", '^');

 
	 $promotions_noms=array();
	 $promotions_defs=array();
	 if(is_array($promotions)){
	 	foreach($promotions AS $fichier=>$chemin){
	 		list($nom,$extension)=explode('.',$fichier);
			//Charger la définition des champs
			 if ($defs = charger_fonction($nom, "promotions", true)){
			 	$promotion=$defs($valeurs);
	           	if($type_promotion==$nom AND isset($promotion['saisies'])){
	           		$promotions_defs = array(array(
	           			'saisie' => 'fieldset',			
						'options' => array(
							'nom' => 'specifique',
							'label' => _T('promotion:label_parametres_specifiques')
						),
						'saisies' => $promotion['saisies']));									
				}
				//Lister les promotions dipsonible
				if(isset($promotion['nom']))$promotions_noms[$nom] =$promotion['nom'];	
		 	}		 
	 	}
	 }	
	
	$saisies=array(
	array(
		'saisie' => 'fieldset',
		'options' => array(
			'nom' => 'general',
			'label' => _T('promotion:label_parametres_generales'),
		),
		'saisies' => array(
			array(
				'saisie' => 'input',
				'options' => array(
					'nom' => 'titre',
					'label' => _T('promotion:label_titre'),
					'obligatoire'=>'oui'
				)
			),
			array(
				'saisie' => 'textarea',
				'options' => array(
					'nom' => 'descriptif',
					'label' => _T('promotion:label_descriptif'),
					'li_class'=>'haut', 
					'class'=>'inserer_barre_edition',
				)
			),
			array(
				'saisie' => 'date',
				'options' => array(
					'nom' => 'date_debut',
					'horaire'=>'oui',					
					'label' => _T('promotion:label_date_debut')
				)
			),
			array(
				'saisie' => 'date',
				'options' => array(
					'nom' => 'date_fin',
					'horaire'=>'oui',
					'label' => _T('promotion:label_date_fin')
				)
			),
			array(
				'saisie' => 'input',
				'options' => array(
					'nom' => 'reduction',
					'label' => _T('promotion:label_reduction'),
					'obligatoire'=>'oui'
				)
			),
			array(
				'saisie' => 'selection',
				'options' => array(
					'nom' => 'type_reduction',
					'label' => _T('promotion:label_type_reduction'),
					'datas'=>array(
						'pourcentage'=>_T('promotion:pourcentage'),
						'absolu'=>_T('promotion:absolu')							
						), 
					'obligatoire'=>'oui'							
				)
			),
			array(
				'saisie' => 'radio',
				'options' => array(
					'nom' => 'prix_base',
					'label' => _T('promotion:label_prix_base'),
					'explication' => _T('promotion:explication_prix_base'),					
					'datas'=>array(
						'prix_original'=>_T('promotion:prix_original'),
						'prix_reduit'=>_T('promotion:prix_reduit')							
						), 
					'obligatoire'=>'oui',
					 'afficher_si'=> '@type_reduction@ == "pourcentage"' 							
				)
			),			
			array(
				'saisie' => 'selection',
				'options' => array(
					'nom' => 'type_promotion',
					'label' => _T('promotion:label_type_promotion'),
					'obligatoire'=>'oui',
					'datas'=>$promotions_noms,
					'class'=>'auto_submit'
					)
				),								
			)
		),
	);

	$saisies=array_merge($saisies,$promotions_defs);
	
	
	return $saisies;
}

/**
 * Identifier le formulaire en faisant abstraction des paramètres qui ne représentent pas l'objet edité
 *
 * @param int|string $id_promotion
 *     Identifiant du promotion. 'new' pour un nouveau promotion.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un promotion source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du promotion, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return string
 *     Hash du formulaire
 */
function formulaires_editer_promotion_identifier_dist($id_promotion='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	return serialize(array(intval($id_promotion)));
}

/**
 * Chargement du formulaire d'édition de promotion
 *
 * Déclarer les champs postés et y intégrer les valeurs par défaut
 *
 * @uses formulaires_editer_objet_charger()
 *
 * @param int|string $id_promotion
 *     Identifiant du promotion. 'new' pour un nouveau promotion.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un promotion source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du promotion, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Environnement du formulaire
 */
function formulaires_editer_promotion_charger_dist($id_promotion='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	$valeurs = formulaires_editer_objet_charger('promotion',$id_promotion,'',$lier_trad,$retour,$config_fonc,$row,$hidden);

	$type_promotion=_request('type_promotion')?_request('type_promotion'):(isset($valeurs['type_promotion'])?$valeurs['type_promotion']:'');
	
	$valeurs_promotion=$valeurs['valeurs_promotion']=unserialize($valeurs['valeurs_promotion']);
	$valeurs['_saisies']=definition_saisies($type_promotion,$valeurs);
	
	
	//initialiser les donnees spécifiques de la promotion
	if(isset($valeurs['_saisies'][1]['saisies'])){
		foreach($valeurs['_saisies'][1]['saisies'] AS $saisie){
			if(isset($saisie['options']['nom'])){
				$valeurs[$saisie['options']['nom']]=_request($saisie['options']['nom'])?_request($saisie['options']['nom']):
				(isset($valeurs_promotion[$saisie['options']['nom']])?$valeurs_promotion[$saisie['options']['nom']]:'');
			}
		}
	}

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
 *     Identifiant du promotion. 'new' pour un nouveau promotion.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un promotion source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du promotion, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Tableau des erreurs
 */
function formulaires_editer_promotion_verifier_dist($id_promotion='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	
	include_spip('inc/saisies');
	
	$saisies=definition_saisies(_request('type_promotion'));

	$erreurs = saisies_verifier($saisies);
	
	$verifier = charger_fonction('verifier', 'inc');
	

		foreach (array('date_debut', 'date_fin') AS $champ)
		{
			if(_request($champ)){
			$normaliser = null;
			if ($erreur = $verifier(_request($champ), 'date', array('normaliser'=>'datetime'), $normaliser)) {
				$erreurs[$champ] = $erreur;
			// si une valeur de normalisation a ete transmis, la prendre.
			} elseif (!is_null($normaliser)) {
				set_request($champ, $normaliser);
			// si pas de normalisation ET pas de date soumise, il ne faut pas tenter d'enregistrer ''
			} else {
				set_request($champ, '0000-00-00 00:00:00');
			}
		}		
	}

		


	
	if(_request('date_debut')>'0000-00-00 00:00:00' AND _request('date_fin')>'0000-00-00 00:00:00' AND _request('date_debut')>=_request('date_fin'))$erreurs['date_fin']=_T('promotion:erreur_datefin');
	
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
 *     Identifiant du promotion. 'new' pour un nouveau promotion.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un promotion source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du promotion, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Retours des traitements
 */
function formulaires_editer_promotion_traiter_dist($id_promotion='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	
	$type_promotion=_request('type_promotion');
	
	$promotion = charger_fonction($type_promotion, "promotions", true);
	
	$valeurs_promotion=$promotion();
	
	if(isset($valeurs_promotion['saisies'])){
		$promotion=array();
		
		foreach($valeurs_promotion['saisies'] AS $champ){
			spip_log(_request($champ['options']['nom']),'teste');
			$promotion[$champ['options']['nom']]=_request($champ['options']['nom']);
		}			
	}
	
	set_request('valeurs_promotion',serialize($promotion));
	
	return formulaires_editer_objet_traiter('promotion',$id_promotion,'',$lier_trad,$retour,$config_fonc,$row,$hidden);
}

?>