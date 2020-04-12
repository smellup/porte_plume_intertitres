<?php
/**
 * Utilisations de pipelines par Intertitres hierarchiques
 *
 * @plugin     Intertitres hierarchiques
 * @copyright  2016
 * @author     Mist. GraphX
 * @licence    GNU/GPL
 * @package    SPIP\Porte_plume_intertitres\Pipelines
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}


function porte_plume_intertitres_ieconfig_metas($table) {
	$table['porte_plume_intertitres']['titre'] = _T('porte_plume_intertitres:porte_plume_intertitres');
	$table['porte_plume_intertitres']['icone'] = 'prive/themes/spip/images/porte_plume_intertitres-16.png';
	$table['porte_plume_intertitres']['metas_serialize'] = 'porte_plume_intertitres';
	return $table;
}

// http://www.spip-contrib.net/Porte-Plume-documentation-technique
function porte_plume_intertitres_porte_plume_barre_pre_charger($barres) {
	static $base_level = false;
	if(!$base_level){
		$base_level = get_heading_base_level();
	}
	// Les références et titre on 5 niveau de hierarchie
	// on pars du niveau de départ de la globale et on incrément
	$max_level = 5;

	$barre = &$barres['edition'];

	$barre->set('header1', array(
		"dropMenu"    => array(
			array(
				"id"          => 'intertitre2',
				"name"        => _T('porte_plume_intertitres:barre_intertitre2'),
				"className"   => 'outil_intertitre'.($base_level+1),
				"openWith" => "\n{{{** ",
				"closeWith" => "}}}\n",
				"display"     => true,
				"selectionType" => "line",
			),
			array(
				"id"          => 'intertitre3',
				"name"        => _T('porte_plume_intertitres:barre_intertitre3'),
				"className"   => 'outil_intertitre'.($base_level+2),
				"openWith" => "\n{{{*** ",
				"closeWith" => "}}}\n",
				"display"     => true,
				"selectionType" => "line",
			),
			array(
				"id"          => 'intertitre4',
				"name"        => _T('porte_plume_intertitres:barre_intertitre4'),
				"className"   => 'outil_intertitre'.($base_level+3),
				"openWith" => "\n{{{**** ",
				"closeWith" => "}}}\n",
				"display"     => true,
				"selectionType" => "line",
			),
			array(
				"id"          => 'intertitre5',
				"name"        => _T('porte_plume_intertitres:barre_intertitre5'),
				"className"   => 'outil_intertitre'.($base_level+4),
				"openWith" => "\n{{{***** ",
				"closeWith" => "}}}\n",
				"display"     => true,
				"selectionType" => "line",
			)
		)
	));

	$barre->ajouterApres('header1', array(
					"id" => "sepgrp1",
					"separator" => "---------------",
					"display"   => true,
		));

	include_spip('inc/config');
	if (lire_config('porte_plume_intertitres/afficher_references', 0)) {

	$barre->ajouterApres('sepgrp1', array(
			"id" => "ref",
			"name"        => _T('barre_intertitre'),
			"className"   => 'outil_ref',
			"dropMenu"    => array(
			array(
				"id"          => 'ref1',
				"name"        => _T('barre_intertitre'),
				"className"   => 'outil_ref1',
				"openWith" => "\n{{{# ",
				"closeWith" => "}}}\n",
				"display"     => true,
				"selectionType" => "line",
			),
			array(
				"id"          => 'ref2',
				"name"        => _T('porte_plume_intertitres:barre_intertitre2'),
				"className"   => 'outil_ref2',
				"openWith" => "\n{{{## ",
				"closeWith" => "}}}\n",
				"display"     => true,
				"selectionType" => "line",
			),
			array(
				"id"          => 'ref3',
				"name"        => _T('porte_plume_intertitres:barre_intertitre3'),
				"className"   => 'outil_ref3',
				"openWith" => "\n{{{### ",
				"closeWith" => "}}}\n",
				"display"     => true,
				"selectionType" => "line",
			),
			array(
				"id"          => 'ref4',
				"name"        => _T('porte_plume_intertitres:barre_intertitre4'),
				"className"   => 'outil_ref4',
				"openWith" => "\n{{{#### ",
				"closeWith" => "}}}\n",
				"display"     => true,
				"selectionType" => "line",
			)
		)

	));

	$barre->ajouterApres('ref', array(
					"id" => "sepgrp2",
					"separator" => "---------------",
					"display"   => true,
		));
	}

	return $barres;
}

function porte_plume_intertitres_porte_plume_lien_classe_vers_icone($flux){
	// Récupérer le niveau de tag debut_intertitre
	$base_level = get_heading_base_level();
	$icones = array(
		'outil_header1' => array('intertitre_'.$base_level.'.png','0'), //'intertitre.png'
		'outil_intertitre1' => array('intertitre_1.png','0'), //'intertitre.png'
		'outil_intertitre2' => array('intertitre_2.png','0'),
		'outil_intertitre3' => array('intertitre_3.png','0'),
		'outil_intertitre4' => array('intertitre_4.png','0'),
		'outil_intertitre5' => array('intertitre_5.png','0'),
		'outil_intertitre6' => array('intertitre_6.png','0'),
		'outil_intertitre7' => array('intertitre_7.png','0'),
		'outil_ref' => array('ref.png','0'),
		'outil_ref1' => array('ref1.png','0'),
		'outil_ref2' => array('ref2.png','0'),
		'outil_ref3' => array('ref3.png','0'),
		'outil_ref4' => array('ref4.png','0')
	);
	 //var_dump($icones);
	return array_merge($flux, $icones);
}
