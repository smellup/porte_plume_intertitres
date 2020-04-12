<?php
/**
 * Callbacks Intertitres hierarchiques
 *
 * @plugin     Intertitres hierarchiques
 * @copyright  2016
 * @author     Mist. GraphX
 * @licence    GNU/GPL
 * @package    SPIP\Porte_plume_intertitres\wheels
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Callback de la wheel intertitre qui renvoie le tableau d'extraction de la regexp sous la forme :
 * 0 - la chaine complète
 * 1 - le groupe ouvrant
 * 2 - le type|level - ou string/contenu si intertitre spip
 * 3 - le contenu
 * 4 - le groupe fermant
 * 5 - attributs class|id
 *
 * @see http://zone.spip.org/trac/spip-zone/browser/_plugins_/todo/trunk/wheels/todo.php
 * @see http://lumadis.be/regex/test_regex.php?id=2938
 *
 * @param array $t
 *
 * @return string
 */
function intertitres($t) {

	static $base_level = 0;
	static $type = '';
	static $last_node = array();

	// Ajuster le niveau de l'item
	if(!$base_level){
		// Récupérer le niveau de base d'après la global
		preg_match('/[h](\d)/s',$GLOBALS['debut_intertitre'], $matches) ;
		$base_level = $matches[1];
	}
	$level = $t[2] ? $base_level + strlen($t[2]) - 1 : $base_level;

	// Déterminer le type de classe des titres : h ou r
	if (!$type) {
		$type = substr($t[2], 0, 1) == '#' ? 'r' : 'h';
	}

	// Calcul de la classe minimale de la balise hx
	$css = $type . $level;

	// Extraction du texte du titre
	$title = $t[3];

	// Numérotation du texte si demandé
	if ($type == 'r') {
		// Il faut numéroter les titres en considérant qu'ils sont détectés dans l'ordre.
		$imbric_level = $t[2] ? strlen($t[2]) - 1 : 0;
		if (isset($last_node[$imbric_level])) {
			// On rajoute un nouveau noeud
			$node = array_slice($last_node, 0, $imbric_level + 1);
			$node[$imbric_level] = isset($node[$imbric_level])
				? $node[$imbric_level] + 1
				: 1;
		} else {
			// On rajoute d'un nouveau niveau
			$node = $last_node;
			$node[$imbric_level] = 1;
		}
		$title = format_title($title, $node);
		$last_node = $node;
	}

	// Traitements des extenders : classes, id et propriétés additionnelles
	$id = '';
	$properties = '';
	if (isset($t[5])) {
		// Si il existe les extenders on les caractérise et on les insère dans le HTML final.
		$attributs = array(
			'css'        => '',
			'id'         => '',
			'properties' => '',
		);
		extract_attributes($t[5], $attributs);

		$css .= $attributs['css'];
		if ($attributs['id']) {
			$id = "id=\"{$attributs['id']}\"";
		}
		if($attributs['properties']) {
			$properties = ' ' . $attributs['properties'];
		}
	}

	// Calcul du HTML compilé pour le titre : on ne dépasse pas h6
	if($level < 7) {
		$html = "<h${level} ${id} class=\"${css}\"${properties}>" . $title . "</h$level>";
	}
	else {
		$html = "<div ${id} class=\"${css}\"${properties}>" . $title . "</div>";
	}

	return $html;
}

function format_title($title, $node) {

	$number = '';
	foreach ($node as $_key => $_value) {
		$number .= $number ? ".${_value}" : $_value;
	}
	$title = "${number} - ${title}";

	return $title;
}

/**
 * Détermine la liste des attributs additionnels à ajouter à la balise hx.
 *
 * @param string $extenders chaine extraite correspondant à des extenders possibles
 * @param array  $attributs Tableau des attributs contenant systématiquement les index `css`, `id`, `properties`
 *                          qui peuvent être vides.
 *
 * @return void
 */
function extract_attributes($extenders, &$attributs) {

	$extenders = preg_split('/[\s]+/', $extenders);
	foreach($extenders as $extender){
		// css
		if ($class = get_css($extender)) {
			$attributs['css'] .= ' ' . $class[1];
		}
		// id (un seul possible)
		elseif ($id = get_id($extender)) {
			$attributs['id'] = $id[1];
		}
		// properties
		elseif ($propertie = get_propertie($extender)) {
			$attributs['properties'] .= $propertie[1];
		}
	}
}

/**
 * Renvoie les classes additionnelles de la balise hx ou vide sinon.
 *
 * @see http://lumadis.be/regex/test_regex.php?id=2936
 *
 * @param string $str
 *
 * @return string
 */
function get_css($str) {
	$regex = '/\.([_a-zA-Z0-9-]+)/s';
	if(preg_match($regex,$str,$class_name))
		return $class_name;
	else
		return '';
}

/**
 * Renvoie l'id de la balise hx ou vide sinon.
 *
 * @param string $str
 *
 * @return string
 */
function get_id($str) {
	$regex = '/#([_a-zA-Z0-9-]+)/s';
	if(preg_match($regex,$str,$id))
		return $id;
	else
		return '';
}

/**
 * Renvoie les propriétés additionnelles de la balise hx ou vide sinon.
 *
 * @see http://lumadis.be/regex/test_regex.php?id=2937
 *
 * @param string $str
 *
 * @return string
 */
function get_propertie($str) {
	$regex = '/([_a-z-]+=[\"|\'].*?[\"|\'])/s';
	if(preg_match($regex,$str,$propertie))
		return $propertie;
	else
		return '';
}
