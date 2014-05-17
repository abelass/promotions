<?php

/**
 *  Fichier généré par la Fabrique de plugin v5
 *   le 2014-05-17 12:06:23
 *
 *  Ce fichier de sauvegarde peut servir à recréer
 *  votre plugin avec le plugin «Fabrique» qui a servi à le créer.
 *
 *  Bien évidemment, les modifications apportées ultérieurement
 *  par vos soins dans le code de ce plugin généré
 *  NE SERONT PAS connues du plugin «Fabrique» et ne pourront pas
 *  être recréées par lui !
 *
 *  La «Fabrique» ne pourra que régénerer le code de base du plugin
 *  avec les informations dont il dispose.
 *
**/

if (!defined("_ECRIRE_INC_VERSION")) return;

$data = array (
  'fabrique' => 
  array (
    'version' => 5,
  ),
  'paquet' => 
  array (
    'nom' => 'Promotions',
    'slogan' => 'Gestion de promotion',
    'description' => 'Un gestionnaire de promotion',
    'prefixe' => 'promotions',
    'version' => '1.0.0',
    'auteur' => 'Rainer',
    'auteur_lien' => 'http://websimple.be',
    'licence' => 'GNU/GPL',
    'categorie' => 'communication',
    'etat' => 'dev',
    'compatibilite' => '[3.0.16;3.0.*]',
    'documentation' => '',
    'administrations' => 'on',
    'schema' => '1.0.0',
    'formulaire_config' => 'on',
    'formulaire_config_titre' => '',
    'fichiers' => 
    array (
      0 => 'autorisations',
      1 => 'fonctions',
      2 => 'options',
      3 => 'pipelines',
    ),
    'inserer' => 
    array (
      'paquet' => '',
      'administrations' => 
      array (
        'maj' => '',
        'desinstallation' => '',
        'fin' => '',
      ),
      'base' => 
      array (
        'tables' => 
        array (
          'fin' => '',
        ),
      ),
    ),
    'scripts' => 
    array (
      'pre_copie' => '',
      'post_creation' => '',
    ),
    'exemples' => 'on',
  ),
  'objets' => 
  array (
    0 => 
    array (
      'nom' => 'Promotions',
      'nom_singulier' => 'Promotion',
      'genre' => 'feminin',
      'logo_variantes' => '',
      'table' => 'spip_promotions',
      'cle_primaire' => 'id_promotion',
      'cle_primaire_sql' => 'bigint(21) NOT NULL',
      'table_type' => 'promotion',
      'champs' => 
      array (
        0 => 
        array (
          'nom' => 'Titre',
          'champ' => 'titre',
          'sql' => 'varchar(255) NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
            2 => 'obligatoire',
          ),
          'recherche' => '8',
          'saisie' => 'input',
          'explication' => '',
          'saisie_options' => '',
        ),
        1 => 
        array (
          'nom' => 'Descriptif',
          'champ' => 'descriptif',
          'sql' => 'text NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '4',
          'saisie' => 'textarea',
          'explication' => '',
          'saisie_options' => 'li_class=haut, class=inserer_barre_edition, rows=4',
        ),
        2 => 
        array (
          'nom' => 'Date début',
          'champ' => 'date_debut',
          'sql' => 'datetime NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '',
          'saisie' => 'date',
          'explication' => '',
          'saisie_options' => '',
        ),
        3 => 
        array (
          'nom' => 'Date fin',
          'champ' => 'date_fin',
          'sql' => 'datetime NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '',
          'saisie' => 'date',
          'explication' => '',
          'saisie_options' => '',
        ),
        4 => 
        array (
          'nom' => 'Type Promotion',
          'champ' => 'type_promotion',
          'sql' => 'varchar(55) NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
            2 => 'obligatoire',
          ),
          'recherche' => '',
          'saisie' => 'selection',
          'explication' => '',
          'saisie_options' => '',
        ),
      ),
      'champ_titre' => 'titre',
      'champ_date' => 'date',
      'statut' => 'on',
      'chaines' => 
      array (
        'titre_objets' => 'Promotions',
        'titre_objet' => 'Promotion',
        'info_aucun_objet' => 'Aucune promotion',
        'info_1_objet' => 'Une promotion',
        'info_nb_objets' => '@nb@ promotions',
        'icone_creer_objet' => 'Créer une promotion',
        'icone_modifier_objet' => 'Modifier cette promotion',
        'titre_logo_objet' => 'Logo de cette promotion',
        'titre_langue_objet' => 'Langue de cette promotion',
        'titre_objets_rubrique' => 'Promotions de la rubrique',
        'info_objets_auteur' => 'Les promotions de cet auteur',
        'retirer_lien_objet' => 'Retirer cette promotion',
        'retirer_tous_liens_objets' => 'Retirer toutes les promotions',
        'ajouter_lien_objet' => 'Ajouter cette promotion',
        'texte_ajouter_objet' => 'Ajouter une promotion',
        'texte_creer_associer_objet' => 'Créer et associer une promotion',
        'texte_changer_statut_objet' => 'Cette promotion est :',
      ),
      'table_liens' => 'on',
      'roles' => '',
      'auteurs_liens' => '',
      'vue_auteurs_liens' => '',
      'echafaudages' => 
      array (
        0 => 'prive/squelettes/contenu/objets.html',
        1 => 'prive/objets/infos/objet.html',
        2 => 'prive/squelettes/contenu/objet.html',
      ),
      'autorisations' => 
      array (
        'objet_creer' => '',
        'objet_voir' => '',
        'objet_modifier' => '',
        'objet_supprimer' => '',
        'associerobjet' => '',
      ),
      'boutons' => 
      array (
        0 => 'menu_edition',
        1 => 'outils_rapides',
      ),
      'saisies' => 
      array (
        0 => 'objets',
      ),
    ),
  ),
  'images' => 
  array (
    'paquet' => 
    array (
      'logo' => 
      array (
        0 => 
        array (
          'extension' => 'png',
          'contenu' => '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',
        ),
      ),
    ),
    'objets' => 
    array (
      0 => 
      array (
      ),
    ),
  ),
);

?>