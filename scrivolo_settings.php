<?php 
/**
 * @package Scrivolo_Settings
 * @author Gianluca Failli
 * @version 0.3
 */
/*
Plugin Name: Scrivolo Settings
Plugin URI: http://www.scrivolo.it/#
Description: Questo plugin serve per gestire le personalizzazioni fatte al tema usato su Scrivolo.it
Author: Gianluca Failli
Version: 0.3
Author URI: http://www.scrivolo.it/
*/


// Hook for adding admin menus
add_action('admin_menu', 'Scrivolo_Settings');


function Scrivolo_Settings_firma($content){
	return ($content) . "
	<p class=\"firma-autore\">" .
	get_the_author() . 
	"</p>";

}
add_filter( "the_content", "Scrivolo_Settings_firma" );


function Scrivolo_Settings(){
	add_submenu_page('themes.php','Scrivolo Settings', 'Scrivolo Settings',  1, basename(__FILE__), 'Scrivolo_Settings_options'); 
	add_option('scrivolo_annuncio', '', null, 'yes');
	add_option('scrivolo_catannuncio', '', null, 'yes');
	add_option('scrivolo_vetro', '', null, 'yes');
	add_option('scrivolo_vetro_content', '', null, 'yes');
}
function Scrivolo_Settings_options() {

	if($_POST["what"] == "annuncio"){
		update_option('scrivolo_annuncio', $_POST["IDpost"]);
		echo "<div id=\"message\" class=\"updated fade\">L'ID dell'annuncio è stato salvato.</div>";
	}
	if($_POST["what"] == "catannuncio"){
		update_option('scrivolo_catannuncio', $_POST["cat_id"]);
		echo "<div id=\"message\" class=\"updated fade\">La categoria degli annunci è stato salvato.</div>";
	}
	if($_POST["what"] == "vetro"){
		update_option('scrivolo_vetro', $_POST["ckvetro"]);
		update_option('scrivolo_vetro_content', $_POST["scrivolo_vetrofonia_content"]);
		echo "<div id=\"message\" class=\"updated fade\">Le impostazioni della Vetrofania sono state salvate.</div>";
	}
  echo "<div class=\"wrap\">
  		<div id=\"scrivolo_catannuncio\">
  			<h2>Scelta categoria annunci</h2>
  			
  			
		<form action=\"?page=" . $_GET["page"] . "&action=save\" method=\"post\">
			<input type=\"hidden\" value=\"catannuncio\" name=\"what\" />
			<table>
				<tr>
					<th width=\"300\" align=\"right\">Categoria degli annuci: &nbsp; </th>
					<td width=\"100\">";
  wp_dropdown_categories( 'name=cat_id&hierarchical=1&selected=' . get_option('scrivolo_catannuncio') );
  echo "</td>
				</tr>
				<tr>
					<td align=\"center\">
			            <p class=\"submit\">
			                <input type=\"submit\" class=\"button-primary\" name=\"edit\" value=\"salva\" />
			                <input type=\"reset\" class=\"button-secondary\" value=\"annulla\" />";
	echo "		            </p>
					</td>
					<td></td>
				</tr>
			</table>
		</form>
  			
  			
  		</div>
  		<br />
  		<div id=\"scrivolo_annuncio\">
  			<h2>Post Annuncio</h2>
  			
  			
		<form action=\"?page=" . $_GET["page"] . "&action=save\" method=\"post\">
			<input type=\"hidden\" value=\"annuncio\" name=\"what\" />
			<table>
				<tr>
					<th width=\"300\" align=\"right\">Post di annuncio: &nbsp; </th>
					<td width=\"100\">";
  echo Scrivolo_Settings_dropdown_post();
  echo "</td>
				</tr>
				<tr>
					<td align=\"center\">
			            <p class=\"submit\">
			                <input type=\"submit\" class=\"button-primary\" name=\"edit\" value=\"salva\" />
			                <input type=\"reset\" class=\"button-secondary\" value=\"annulla\" />";
  if(get_option('scrivolo_annuncio') != 0)
	echo "		                <input type=\"submit\" class=\"button-secondary\" value=\"elimina annuncio\" onclick=\"document.getElementById('scrivolo_IDpost').value = 0;\" />";
	echo "		            </p>
					</td>
					<td></td>
				</tr>
			</table>
		</form>
  			
  			
  		</div>
  		<br />
  		<div id=\"scrivolo_vetro\">
  			<h2>Impostazioni Vetrofania</h2>
  			<form action=\"?page=" . $_GET["page"] . "&action=save\" method=\"post\">
			<input type=\"hidden\" value=\"vetro\" name=\"what\" />
			<table border=\"1\">
				<tr>
					<th width=\"300\" align=\"right\">Visualizzare Vetrofania: &nbsp; </th>
					<td><input type=\"checkbox\" name=\"ckvetro\" value=\"1\" ";
	if( get_option('scrivolo_vetro') )
	echo  "checked";
	echo "\" /></td>
				</tr>
				<tr valign=\"top\">
					<th width=\"300\" align=\"right\">Contenuto della Vetrofania: &nbsp; </th>
					<td ><textarea cols=\"100\" rows=\"10\" name=\"scrivolo_vetrofonia_content\">" . stripslashes(get_option('scrivolo_vetro_content')) . "</textarea></td>
				</tr>
				<tr>
					<td align=\"center\">
			            <p class=\"submit\">
			                <input type=\"submit\" class=\"button-primary\" name=\"edit\" value=\"salva\" />
			                <input type=\"reset\" class=\"button-secondary\" value=\"annulla\" />";
  if(get_option('scrivolo_annuncio') != 0)
	echo "		                <input type=\"submit\" class=\"button-secondary\" value=\"elimina annuncio\" onclick=\"document.getElementById('scrivolo_IDpost').value = 0;\" />";
	echo "		            </p>
					</td>
				</tr>
			</table>
		</form>
  		</div>
  </div>";
}


/**
 * Retrieve or display list of pages as a dropdown (select list).
 *
 * @since 2.1.0
 *
 * @param array|string $args Optional. Override default arguments.
 * @return string HTML content, if not displaying.
 */
function Scrivolo_Settings_dropdown_post($args = '') {
//	$option_name = "Scrivolo_Settings_cat";
//	$book_config_cat = get_option($option_name);
	$id_annuncio = get_option("scrivolo_annuncio");
	$id_catannuncio = get_option("scrivolo_catannuncio");
	$defaults = array(
	/*	'depth' => 0, 'child_of' => 0,
		'selected' => 0, 'echo' => 1,
		'name' => 'page_id', 'show_option_none' => '', 'show_option_no_change' => '',
		'option_none_value' => '' */
		'category' => $id_catannuncio,
		'offset' => 0,
		'limits' => 0
	);

	$r = wp_parse_args( $args, $defaults );

	extract( $r, EXTR_SKIP );

	$arg = '';
	foreach($r as $key=>$value){
		if($arg != ''){
			$arg .= "&";
		}
		$arg .= $key . "=" . $value; 
	}
//	echo $arg;
	$pages = get_posts($arg);
	$output = '';
	
//print_r($pages);

	if ( ! empty($pages) ) {
		$output = "<select name=\"IDpost\" id=\"scrivolo_IDpost\">\n";
		$output .= "\t<option value=\"0\"></option>\n";
		
		foreach($pages as $post) {
//		print_r($post);
			$output .= "\t<option value=\"" . $post->ID . "\"";
		
			if($post->ID == $id_annuncio):
				$output .= " selected ";
			endif;
			
			$output .= ">" . $post->post_title . "</option>\n";
		}
		$output .= "</select>\n";
	}

	$output = apply_filters('wp_dropdown_pages', $output);

		echo $output;

}

?>
