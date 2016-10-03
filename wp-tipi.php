<?php
/**
 * Plugin Name: Tipi Client
 * Plugin URI: http://enreda.coop
 * Description: This plugin take Stats from Tipi.
 * Version: 1.0.0
 * Author: Jose Enrique Ruiz Navarro
 * Author URI: http://www.systerminal.com
 * License: GPL2
 */
if ( !defined('ABSPATH') )
	die('-1');
require_once ABSPATH . '/wp-content/plugins/wp-tipi/widget.php';
add_action( 'widgets_init', function(){
     register_widget( 'My_Widget' );
});	

add_action('admin_menu', 'admin_manage');
add_filter('admin_init', 'display_tipi_panel_fields');
add_action('admin_enqueue_scripts', 'admin_enqueue');
add_action( 'wp_enqueue_scripts', 'admin_enqueue' );


function admin_enqueue( $hook ) {

    wp_enqueue_script( 'admijs', plugin_dir_url( __FILE__ ) . '/js/admin.js' , array( 'jquery' ), '3.1.0', true);
    wp_register_script( 'admijs', plugin_dir_url( __FILE__ ) . '/js/admin.js' , array( 'jquery' ), '3.1.0', true);

    wp_localize_script( 'admijs', 'object', array(
    'filter' => get_option( 'filterby' ),
    'dict' => get_option( 'dicts-tipi' ),
     ));

    //wp_enqueue_script( 'raphael', "//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js");
    //wp_enqueue_script( 'morris', "//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js");
    //wp_enqueue_style( 'style', "//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" );





}


 
function admin_manage(){
        add_menu_page( 'Wp-tipi', 'Tipi-Client', 'manage_options', 'tipi-client', 'manage' );
}
 
function manage(){
		
		settings_page();

		echo "<div id=container-tipi>";
		echo "</div>";


}



function display_dicts_element()
{

	$api = CallAPI("http://www.tipiciudadano.es/api/v1/dicts");
	createSelect($api);

}

function display_filter_element()
{
	?>
    	<select name="filterby" id="filterby">
    		<option value="bydeputies" <?php if ( get_option('filterby') == "bydeputies" ) echo 'selected="selected"'; ?>>Por diputados</option>
    		<option value="bygroups"<?php if ( get_option('filterby') == "bygroups" ) echo 'selected="selected"'; ?>>Por Grupos</option>
    		<option value="overall"<?php if ( get_option('filterby') == "overall" ) echo 'selected="selected"'; ?>>General</option>

    	</select>
    <?php
}


function display_tipi_panel_fields()
{
	add_settings_section("tipi-section", "All Settings", null, "tipi-options");
	
	add_settings_field("dicts-tipi", "Dicts", "display_dicts_element", "tipi-options", "tipi-section");
    add_settings_field("filterby", "Filter By", "display_filter_element", "tipi-options", "tipi-section");

    register_setting("tipi-section", "dicts-tipi");
    register_setting("tipi-section", "filterby");
}


function settings_page()
{
    ?>
	    <div class="wrap">
	    <h1>Tipi Panel</h1>
	    <form method="post" action="options.php">
	        <?php
	            settings_fields("tipi-section");
	            do_settings_sections("tipi-options");
	            submit_button(); 
	        ?>          
	    </form>
		</div>
	<?php
}

function CallAPI($url)
{

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_URL,$url);

    $result = curl_exec($curl);

    curl_close($curl);
   
    return json_decode($result, true);
}







function createSelect($array){

echo '<select name="dicts-tipi" id="dicts-id">';
  foreach ($array as $key => $value) {
  	$name=$value['name'];
  	$slug=$value['slug'];

  	if (get_option('dicts-tipi') == $slug){
  		echo '<option value="'.$slug.'" selected="selected">'.$name.'</option>';

  	}else{
  		echo '<option value="'.$slug.'">'.$name.'</option>';
  	}
  	

  	  }


echo "</select>";

}




?>