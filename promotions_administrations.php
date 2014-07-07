<?php
/**
 * Fichier gérant l'installation et désinstallation du plugin Promotions
 *
 * @plugin     Promotions
 * @copyright  2014
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Promotions\Installation
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Fonction d'installation et de mise à jour du plugin Promotions.
 *
 * Vous pouvez :
 *
 * - créer la structure SQL,
 * - insérer du pre-contenu,
 * - installer des valeurs de configuration,
 * - mettre à jour la structure SQL 
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @param string $version_cible
 *     Version du schéma de données dans ce plugin (déclaré dans paquet.xml)
 * @return void
**/
function promotions_upgrade($nom_meta_base_version, $version_cible) {
	$maj = array();


	$maj['create'] = array(array('maj_tables', array('spip_promotions', 'spip_promotions_liens')));
	$maj['1.1.0'] = array(array('maj_tables', array('spip_promotions')));
	$maj['1.2.0'] = array(array('sql_alter','TABLE spip_promotions_liens CHANGE prix_normal prix_original bigint(21) DEFAULT \'0\' NOT NULL'),);		
	$maj['1.3.0'] = array(array('maj_tables', array('spip_promotions')));
	$maj['1.4.0'] = array(array('maj_tables', array('spip_promotions')));	
	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}


/**
 * Fonction de désinstallation du plugin Promotions.
 * 
 * Vous devez :
 *
 * - nettoyer toutes les données ajoutées par le plugin et son utilisation
 * - supprimer les tables et les champs créés par le plugin. 
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @return void
**/
function promotions_vider_tables($nom_meta_base_version) {


	sql_drop_table("spip_promotions");
	sql_drop_table("spip_promotions_liens");

	# Nettoyer les versionnages et forums
	sql_delete("spip_versions",              sql_in("objet", array('promotion')));
	sql_delete("spip_versions_fragments",    sql_in("objet", array('promotion')));
	sql_delete("spip_forum",                 sql_in("objet", array('promotion')));

	effacer_meta($nom_meta_base_version);
}

?>