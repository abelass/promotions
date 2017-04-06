<?php
/**
 * Déclarations relatives à la base de données
 *
 * @plugin     Promotions
 * @copyright  2014
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Promotions\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Déclaration des alias de tables et filtres automatiques de champs
 *
 * @pipeline declarer_tables_interfaces
 * @param array $interfaces
 *     Déclarations d'interface pour le compilateur
 * @return array
 *     Déclarations d'interface pour le compilateur
 */
function promotions_declarer_tables_interfaces($interfaces) {

	$interfaces['table_des_tables']['promotions'] = 'promotions';

	return $interfaces;
}


/**
 * Déclaration des objets éditoriaux
 *
 * @pipeline declarer_tables_objets_sql
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function promotions_declarer_tables_objets_sql($tables) {

	$tables['spip_promotions'] = array(
		'type' => 'promotion',
		'principale' => "oui",
		'field'=> array(
			"id_promotion"       => "bigint(21) NOT NULL",
			"titre"              => "varchar(255) NOT NULL DEFAULT ''",
			"descriptif"         => "text NOT NULL DEFAULT ''",
			"date_debut"         => "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
			"date_fin"           => "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
			"reduction"          => "bigint(21) NOT NULL",
			"type_reduction"     => "varchar(20) NOT NULL DEFAULT ''",
			"prix_base"          => "varchar(20) NOT NULL DEFAULT ''",
			"type_promotion"     => "varchar(55) NOT NULL DEFAULT ''",
			"nombre_applicable"  => "int(11) NOT NULL",
			"valeurs_promotion"  => "text NOT NULL DEFAULT ''",
			"non_cumulable"      => "text NOT NULL DEFAULT ''",
			"rang"               => "bigint(21) NOT NULL",
			"date"               => "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
			"statut"             => "varchar(20)  DEFAULT '0' NOT NULL",
			"maj"                => "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_promotion",
			"KEY statut"         => "statut",
		),
		'titre' => "titre AS titre, '' AS lang",
		'date' => "date",
		'champs_editables'  => array('titre', 'descriptif', 'date_debut', 'date_fin', 'type_promotion','objet','id_objet','valeurs_promotion','reduction','type_reduction','prix_base','non_cumulable','rang'),
		'champs_versionnes' => array('titre', 'descriptif', 'date_debut', 'date_fin', 'type_promotion','reduction','type_reduction','prix_base','non_cumulable'),
		'rechercher_champs' => array("titre" => 8, "descriptif" => 4),
		'tables_jointures'  => array('spip_promotions_liens'),
		'statut_textes_instituer' => array(
			'prepa'    => 'texte_statut_en_cours_redaction',
			'prop'     => 'texte_statut_propose_evaluation',
			'publie'   => 'texte_statut_publie',
			'refuse'   => 'texte_statut_refuse',
			'poubelle' => 'texte_statut_poubelle',
		),
		'statut'=> array(
			array(
				'champ'     => 'statut',
				'publie'    => 'publie',
				'previsu'   => 'publie,prop,prepa',
				'post_date' => 'date',
				'exception' => array('statut','tout')
			)
		),
		'texte_changer_statut' => 'promotion:texte_changer_statut_promotion',


	);

	return $tables;
}


/**
 * Déclaration des tables secondaires (liaisons)
 *
 * @pipeline declarer_tables_auxiliaires
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function promotions_declarer_tables_auxiliaires($tables) {

	$tables['spip_promotions_liens'] = array(
		'field' => array(
			"id_promotion"       => "bigint(21) DEFAULT '0' NOT NULL",
			"id_objet"           => "bigint(21) DEFAULT '0' NOT NULL",
			"objet"              => "VARCHAR(25) DEFAULT '' NOT NULL",
			"prix_original"		 => "bigint(21) DEFAULT '0' NOT NULL",
			"prix_promotion"	 => "bigint(21) DEFAULT '0' NOT NULL",
			"vu"                 => "VARCHAR(6) DEFAULT 'non' NOT NULL"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_promotion,id_objet,objet",
			"KEY id_promotion"   => "id_promotion"
		)
	);

	return $tables;
}


?>