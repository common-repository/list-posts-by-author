<?php
/*
Plugin Name: List posts by author
Author: Rodolfo Martínez
Author URI: http://www.escritoenelagua.com/
Version: 1.0
Description: Lists all the posts or the pages (or both of them) group by their authors 
Plugin URI: 

*/

/*
You can add a call to the plugin in a post or a page just typing (in HMTL mode) [autores], or you can type in your sidebar (o wherever you desire: <?php list_posts_by_author('all', 'page', 'date', 'asc', 'ul', 'h3', '#000000', 'italic', '_self'); ?>, changing the parameters as you wish
*/


//Varias funciones de configuración

//Parametrizar según idioma
$currentLocale = get_locale();
if(!empty($currentLocale)) {
$moFile = dirname(__FILE__) . "/languages/list-posts-by-author-" . $currentLocale . ".mo";
if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('listauthor', $moFile);
}

//Insertar código en los post para ver la lista de autores
add_shortcode('autores', 'autor_shortcode');
function autor_shortcode($atts) {
      return list_posts_by_author('', '', '', '', '', '', '', '', '', '');
}

/* Añade link a las opciones en la página de plugins
 * Thanks Dion Hulse -- http://dd32.id.au/wordpress-plugins/?configure-link
 */
function list_posts_by_author_filter_plugin_actions($links, $file){
	static $this_plugin;

	if( !$this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if( $file == $this_plugin ){
		$settings_link = '<a href="options-general.php?page=list-posts-by-author/list-posts-by-author.php">' . __('Opciones') . '</a>';
		$links = array_merge( array($settings_link), $links); // before other links
	}
	return $links;
}

/* Añade el plugin a las Opciones de WordPress */
function list_posts_by_author_add_to_menu() {
    add_submenu_page('options-general.php', 'List Posts by Authors', 'List Posts by Authors', 10, __FILE__, 'list_posts_by_author_settings');
add_filter('plugin_action_links', 'list_posts_by_author_filter_plugin_actions', 10, 2);
}
add_action('admin_menu', 'list_posts_by_author_add_to_menu');


//Configura el plugin a través del menú "Opciones" de WordPress
function list_posts_by_author_settings() {
        //para posibles usos futuros: meter todos los parámetros en un array
        $parametros = array ('queautor', 'noqueautor', 'quever', 'ordenar', 'asc', 'tipolista', 'cabest', 'cabcolor');
	if ($_POST) {
                if($_POST["queautor"] == "")
			$_POST["queautor"] = "all";
                if($_POST["noqueautor"] == "")
			$_POST["noqueautor"] = "all";
                if($_POST["quever"] == "")
			$_POST["quever"] = "post";
                if($_POST["ordenar"] == "")
			$_POST["ordenar"] = "date";
                if($_POST["asc"] == "")
			$_POST["as"] = "ASC";
                if($_POST["tipolista"] == "")
			$_POST["tipolista"] = "ul";
                if($_POST["cabest"] == "")
			$_POST["cabest"] = "H2";
                if($_POST["cabcolor"] == '')
                        $_POST["cabcolor"] = "#000000";
                if($_POST["detest"] == '')
                        $_POST["detest"] = "";
                if($_POST["dettipo"] == '')
                        $_POST["dettipo"] = "tit";
		update_option('queautor', $_POST['queautor']);
		update_option('noqueautor', $_POST['noqueautor']);
		update_option('quever', $_POST['quever']);
		update_option('ordenar', $_POST['ordenar']);
		update_option('asc', $_POST['asc']);
		update_option('tipolista', $_POST['tipolista']);
		update_option('cabest', $_POST['cabest']);
                update_option('cabcolor', $_POST['cabcolor']);
                update_option('detest', $_POST['detest']);
                update_option('dettipo', $_POST['dettipo']);
          

	}
	// Get options
	$queautor = get_option('queautor');
	$noqueautor = get_option('noqueautor');
	$quever = get_option('quever');
	$ordenar = get_option('ordenar');
	$asc = get_option('asc');
	$tipolista = get_option('tipolista');
        $cabest = get_option('cabest');
        $cabcolor = get_option('cabcolor');
        $detest = get_option('detest');
        $dettipo = get_option('dettipo');



?>

<div class="wrap">
<h2><?php  _e('Listado de entradas por autor', 'listauthor'); ?></h2>

<?php
//Mensaje de opciones actualizadas
	if ($_POST) {
echo '<div id="message" class="updated fade"><p>';
_e("Opciones actualizadas", 'listauthor');
echo '.</p></div>';
};

?>

<form target="_self" method="post">
<table width=70%>

<!-- Cabecera "Opciones" -->
<tr>
<td width=25%></td>
<td width=45$><h3><?php _e('Opciones', 'listauthor'); ?></h3></td>
<td width=3%></td>
<td><h3><?php _e("Valores actuales", 'listauthor'); ?></h3></td>
</tr>

<!-- Detalle "Opciones -->
<tr>
<td><strong><?php _e("Escritos por", 'listauthor'); ?>: </strong></td>
<td>
<?php
//Obtener usuarios con posts
global $wpdb;
$queautor1 = array ();
$queautor2 = array ();
$query1_select = "SELECT usuarios.ID, usuarios.display_name";
$query1_from = " FROM $wpdb->users as usuarios";
$query1_where = " WHERE usuarios.ID IN (SELECT post_author FROM $wpdb->posts WHERE post_status = 'publish') ";
$query1_orderby = " ORDER BY usuarios.display_name ASC";
$query1 = $query1_select . $query1_from . $query1_where . $query1_orderby;
//echo $query1;

$us_posts = $wpdb->get_results($query1);
if ($us_posts) {
  $i = 0;
  foreach ($us_posts as $uspost) {
   $queautor1[$i] = $uspost->display_name;
   $queautor2[$i] = $uspost->display_name;
   $i++;
  }
};
$j = $i;
?>
<input name="queautor" type="hidden" style="width:100%;" value="<?php echo $queautor; ?>" />
<select name="queautor">
<?php
if ($i == 0) {
    $queautor1 = array ("all");
    $queautor2 = array (__("Todos", 'listauthor'));
    $j = 3;
}
else {
    $queautor1[$j] = "all";
    $queautor2[$j] = __("Todos", 'listauthor'); 
    $j++;
};
$i = 0;
while ($i < $j)
{
?>
         <option <?php if ($queautor==$queautor1[$i]) echo 'selected'; ?> value="<?php echo $queautor1[$i] ?>"><?php echo $queautor2[$i] ?></option>
<?php
$i++;
}
?>
           </select>
</td>
<td></td> 
<td><?php
$i = 0;
while ($i < $j)
{
 if ($queautor==$queautor1[$i]) echo $queautor2[$i];
 $i++;
}
 ?></td>
</tr>

<tr>
<td><strong><?php _e("No escritos por", 'listauthor'); ?>: </strong></td>
<td>
<?php
//Obtener usuarios con posts
if ($us_posts) {
  $i = 0;
  foreach ($us_posts as $uspost) {
   $noqueautor1[$i] = $queautor1[$i];
   $noqueautor2[$i] = $queautor2[$i];
   $i++;
  }
};
$j = $i;
?>
<input name="noqueautor" type="hidden" style="width:100%;" value="<?php echo $noqueautor; ?>" />
<select name="noqueautor">
<?php
if ($i == 0) {
    $noqueautor1 = array ("none", "all");
    $noqueautor2 = array (__("Ninguno", 'listauthor'), __("No filtrar". 'listauthor'));
    $j = 3;
}
else {
    $noqueautor1[$j] = "none";
    $noqueautor2[$j] = __("Ninguno", 'listauthor'); 
    $j++;
    $noqueautor1[$j] = "all";
    $noqueautor2[$j] = __("No filtrar", 'listauthor'); 
    $j++;
};
$i = 0;
while ($i < $j)
{
?>
         <option <?php if ($noqueautor==$noqueautor1[$i]) echo 'selected'; ?> value="<?php echo $noqueautor1[$i] ?>"><?php echo $noqueautor2[$i] ?></option>
<?php
$i++;
}
?>
           </select>
</td>
<td></td> 
<td><?php
$i = 0;
while ($i < $j)
{
 if ($noqueautor==$noqueautor1[$i]) echo $noqueautor2[$i];
 $i++;
}
 ?></td>
</tr>

<tr>
<td><strong><?php _e("Visualizar", 'listauthor'); ?>: </strong></td>
<td>
<input name="quever" type="hidden" style="width:100%;" value="<?php echo $quever; ?>" />
<select name="quever">
<?php
$quever1 = array ("post", "page", "postpage");
$quever2 = array (__("Entradas", 'listauthor'), __("Páginas", 'listauthor'), __("Entradas y páginas", 'listauthor'));
$i = 0;
while ($i < 3)
{
?>
         <option <?php if ($quever==$quever1[$i]) echo 'selected'; ?> value="<?php echo $quever1[$i] ?>"><?php echo $quever2[$i] ?></option>
<?php
$i++;
}
?>
           </select>
</td>
<td></td> 
<td><?php
$i = 0;
while ($i < 3)
{
 if ($quever==$quever1[$i]) echo $quever2[$i];
 $i++;
}
 ?></td>
</tr>
<tr>
<td><strong><?php _e("Ordenar por", 'listauthor'); ?>: </strong></td>
<td>
<input name="ordenar" type="hidden" style="width:100%;" value="<?php echo $ordenar; ?>" />
<select name="ordenar">
<?php
$ordenar1 = array ("date", "name");
$ordenar2 = array (__("Fecha", 'listauthor'), __("Título", 'listauthor'));
$i = 0;
while ($i < 2)
{
?>
         <option <?php if ($ordenar==$ordenar1[$i]) echo 'selected'; ?> value="<?php echo $ordenar1[$i] ?>"><?php echo $ordenar2[$i] ?></option>
<?php
$i++;
}
?>
           </select>
</td>
<td></td> 
<td><?php
$i = 0;
while ($i < 2)
{
 if ($ordenar==$ordenar1[$i]) echo $ordenar2[$i];
 $i++;
}
 ?></td>
</tr>
<tr>
<td><strong><?php _e("Ordenación", 'listauthor'); ?>: </strong></td>
<td>
<input name="asc" type="hidden" style="width:100%;" value="<?php echo $asc; ?>" />
<select name="asc">
<?php
$asc1 = array ("ASC", "DESC");
$asc2 = array (__("Ascendente", 'listauthor'), __("Descendente", 'listauthor'));
$i = 0;
while ($i < 2)
{
?>
         <option <?php if ($asc==$asc1[$i]) echo 'selected'; ?> value="<?php echo $asc1[$i] ?>"><?php echo $asc2[$i] ?></option>
<?php
$i++;
}
?>
           </select>
</td>
<td></td> 
<td><?php
$i = 0;
while ($i < 2)
{
 if ($asc==$asc1[$i]) echo $asc2[$i];
 $i++;
}
 ?></td>
</tr>

<!-- Cabecera "Formato" -->
<tr>
<td></td>
<td><h3><?php _e('Formato', 'listauthor'); ?></h3></td>
<td></td>
<td></td>
</tr>

<!-- Detalle "Formato" -->
<tr>
<td><strong><?php _e("Visualizar como", 'listauthor'); ?>: </strong></td>
<td>
<input name="tipolista" type="hidden" style="width:100%;" value="<?php echo $tipolista; ?>" />
<select name="tipolista">
<?php
$tipolista1 = array ("ul", "ol", "tabla");
$tipolista2 = array (__("Lista", 'listauthor'), __("Lista numerada", 'listauthor'), __("Tabla", 'listauthor'));
$i = 0;
while ($i < 3)
{
?>
         <option <?php if ($tipolista==$tipolista1[$i]) echo 'selected'; ?> value="<?php echo $tipolista1[$i] ?>"><?php echo $tipolista2[$i] ?></option>
<?php
$i++;
}
?>
           </select>
</td>
<td></td> 
<td><?php
$i = 0;
while ($i < 3)
{
 if ($tipolista==$tipolista1[$i]) echo $tipolista2[$i];
 $i++;
}
 ?></td>
</tr>



<!-- Cabecera "Autor" -->
<tr>
<td></td>
<td><h3><?php _e('Autores', 'listauthor'); ?></h3></td>
<td></td>
<td></td>
</tr>

<!-- Detalle "Autor" -->
<tr>
<td><strong><?php _e("Estilo para el autor", 'listauthor'); ?>: </strong></td>
<td>
<input name="cabest" type="hidden" style="width:100%;" value="<?php echo $cabest; ?>" />
<select name="cabest">
<?php
$cabest1 = array ("H1", "H2", "H3");
$cabest2 = array (__("Cabecera 1", 'listauthor'), __("Cabecera 2", 'listauthor'), __("Cabecera 3", 'listauthor'));
$i = 0;
while ($i < 3)
{
?>
         <option <?php if ($cabest==$cabest1[$i]) echo 'selected'; ?> value="<?php echo $cabest1[$i] ?>"><?php echo $cabest2[$i] ?></option>
<?php
$i++;
}
?>
           </select>
</td>
<td></td> 
<td><?php
$i = 0;
while ($i < 3)
{
 $j = $i + 1;
 if ($cabest==$cabest1[$i]) echo '<div id="content"><' . $cabest1[$i] . '>Cabecera ' . $j . '</' . $cabest1[$i] . '></div>';
 $i++;
}
 ?></td>
</tr>
<tr>
<td><strong><?php _e("Color para el autor", 'listauthor'); ?>: </strong></td>
<td>
<input name="cabcolor" type="hidden" style="width:100%;" value="<?php echo $cabcolor; ?>" />
<select name="cabcolor">
<?php
$cabcolor1 = array ("#000000", "#696969", "#8B0000", "#FF4500", "#006400", "#FFFF00");
$cabcolor2 = array (__("Negro", 'listauthor'), __("Gris", 'listauthor'), __("Rojo oscuro", 'listauthor'), __("Naranja", 'listauthor'), __("Verde", 'listauthor'), __("Amarillo", 'listauthor'));
$i = 0;
while ($i < 6)
{
?>
                    	<option <?php if ($cabcolor==$cabcolor1[$i]) echo 'selected'; ?> value="<?php echo $cabcolor1[$i]; ?>"><?php echo $cabcolor2[$i]; ?></option>
<?php
$i++;
}
?>
                    </select>
</td>
<td></td>
<td>
<span style="background-color: <?php echo $cabcolor; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
</td>
</tr>



<!-- Cabecera "Entradas" -->
<tr>
<td></td>
<td><h3><?php _e('Entradas', 'listauthor'); ?></h3></td>
<td></td>
<td></td>
</tr>

<!-- Detalle "Entradas" -->
<tr>
<td><strong><?php _e("Estilo de entrada", 'listauthor'); ?>: </strong></td>
<td><input name="detest" type="hidden" style="width:100%;" value="<?php echo $detest; ?>" />
<select name="detest">
<?php
$detest1 = array ("", "em", "strong");
$detest2 = array (__("Normal", 'listauthor'), __("Cursiva", 'listauthor'), __("Negrita", 'listauthor'));
$i = 0;
while ($i < 3)
{
?>
                    	<option <?php if ($detest==$detest1[$i]) echo 'selected'; ?> value="<?php echo $detest1[$i]; ?>"><?php echo $detest2[$i]; ?></option>
<?php
$i++;
}
?>
                    </select> 
</td>
<td></td>
<td><?php
$i = 0;
while ($i < 3)
{
 if ($detest==$detest1[$i]) echo $detest2[$i];
 $i++;
}
 ?></td>
</tr>
<tr>
<td><strong><?php _e("Visualizar como", 'listauthor'); ?>: </strong></td>
<td>
<input name="dettipo" type="hidden" style="width:100%;" value="<?php echo $dettipo; ?>" />
<select name="dettipo">
<?php
$dettipo1 = array ("tit", "_self", "_blank");
$dettipo2 = array (__("Texto", 'listauthor'), __("Enlace", 'listauthor'), __("Enlace a ventana/pestaña nueva", 'listauthor'));
$i = 0;
while ($i < 3)
{
?>
                    	<option <?php if ($dettipo==$dettipo1[$i]) echo 'selected'; ?> value="<?php echo $dettipo1[$i]; ?>"><?php echo $dettipo2[$i]; ?></option>
<?php
$i++;
}
?>
                    </select>
</td>
<td></td>
<td><?php
$i = 0;
while ($i < 3)
{
 if ($dettipo==$dettipo1[$i]) {
    if ($dettipo == 'tit') { echo $dettipo2[$i]; 
    }
    else {
       echo '<a href="">' .  $dettipo2[$i] . '</a>';
    }
 }
 $i++;
}
 ?>
</td>
</tr>

</table>
<!-- Fin de tabla de opciones -->

<p class="submit">
		<input name="submitted" type="hidden" value="yes" />
		<input type="submit" name="Submit" value="<?php _e("Actualizar opciones", 'listauthor'); ?>" />
</p>

</form>
<?php 

}


$queautor = get_option('queautor');
if ($queautor == '') {$queautor = 'all'; };
$noqueautor = get_option('noqueautor');
if ($noqueautor == '') {$noqueautor = 'all'; };
$quever = get_option('quever');
if ($quever == '') { $quever = 'post'; };
$ordenar = get_option('ordenar');
if ($ordenar == '') { $ordenar = 'date'; };
$asc = get_option('asc');
if ($asc == '') { $asc = 'ASC'; };
$tipolista = get_option('tipolista');
if ($tipolista == '') { $tipolista = 'ul'; };
$cabest = get_option('cabest');
if ($cabest == '') { $cabest = 'H2'; };
$cabcolor = get_option('cabcolor');
if ($cabcolor == '') {$cabcolor = '#000000'; };
$detest = get_option('detest');
if ($detest == '') {$detest = ''; };
$dettipo = get_option('dettipo');
if ($dettipo == '') {$dettipo = 'tit'; };



//Función principal
function list_posts_by_author($elautor, $elnoautor, $elquever, $elordenar, $elasc, $eltipolista, $elcabest, $elcabcolor, $eldetest, $eldettipo) {
     global $wpdb;
     global $queautor;
     if ($elautor != '') {$queautor = $elautor; };
     global $noqueautor;
     if ($elnoautor != '') {$noqueautor = $elnoautor; };
     global $quever;
     if ($elquever != '') {$quever = $elquever; };
     global $ordenar;
     if ($elordenar != '') {$ordenar = $elordenar; };
     global $asc;
     if ($elasc != '') {$asc = $elasc; };
     global $tipolista;
     if ($eltipolista != '') {$tipolista = $eltipolista; };
     global $cabest;
     if ($elcabest != '') {$cabest = $elcabest; };
     global $cabcolor;
     if ($elcabecolor != '') {$cabcolor = $elcabcolor; };
     global $detest;
     if ($eldetest != '') {$detest = $eldetest; };
     global $dettipo;
     if ($eldettipo != '') {$dettipo = $eldettipo; };

     //Obtener nombre, apellido e ID del autor del post
     $query_select = "SELECT usuarios.ID, usuarios.display_name, usmeta.user_id, usmeta.meta_key, usmeta.meta_value, usmetan.meta_value as nombre, usmetaa.meta_value as apellido";
     $query_from = " FROM $wpdb->users as usuarios, $wpdb->usermeta as usmeta, $wpdb->usermeta as usmetan, $wpdb->usermeta as usmetaa";
     $query_where = " WHERE usuarios.ID = usmeta.user_id AND usuarios.ID = usmetan.user_id AND usuarios.ID = usmetaa.user_id AND usmeta.meta_key LIKE '%_user_level' AND usmetan.meta_key = 'first_name' AND usmetaa.meta_key ='last_name'";
     if ($queautor == 'sauthor') {
        $query_where = $query_where . " AND (usmeta.meta_value = '2')";
     };
     if ($queautor == 'sadmin') {
        $query_where = $query_where . " AND (usmeta.meta_value = '10')";
     };
     if ($queautor == 'all') {
        $query_where = $query_where . " AND (usmeta.meta_value = '2' OR usmeta.meta_value = 10)";
     };
     if ($queautor != 'sauthor' && $queautor != 'sadmin' && $queautor != 'all') {
        $query_where = $query_where . " AND usuarios.display_name = '" . $queautor . "'";
     };

     if ($noqueautor != 'all') {
        if ($noqueautor == 'sauthor') {
           $query_where = $query_where . " AND (usmeta.meta_value <> '2')";
        };

        if ($noqueautor == 'sadmin') {
           $query_where = $query_where . " AND (usmeta.meta_value <> '10')";
        };

        if ($noqueautor == 'none') {
           $query_where = $query_where . " AND (usmeta.meta_value <> '2' AND usmeta.meta_value <> 10)";
        };

        if ($noqueautor != 'sauthor' && $noqueautor != 'sadmin' && $noqueautor != 'none') {
           $query_where = $query_where . " AND usuarios.display_name <> '" . $noqueautor . "'";
        };
     };

     $query_orderby = " ORDER BY apellido ASC, nombre ASC";
     $query = $query_select . $query_from . $query_where . $query_orderby;
     $autores = $wpdb->get_results($query);

     //Bucle para visualizar cada autor
     if ($autores) {
       foreach ($autores as $autor) {
          //$autor_nombre = $autor->nombre;
          //$autor_apellido = $autor->apellido;
          $autor_nombre_ver = $autor->display_name;
          $autor_ID = $autor->ID;

          //Obtener posts que ha escrito
          $query2_select = "SELECT post_author, post_title, post_status, post_type, post_date, post_name, post_date_gmt";
          $query2_from = " FROM $wpdb->posts";
          $query2_where = " WHERE post_author = " . $autor_ID . " AND post_status = 'publish'";  
          if ($quever == 'post' || $quever == 'page') {
             $query2_where = $query2_where  . "AND post_type = '" . $quever . "'";
          }
          if ($ordenar == 'name') {
             $query2_orderby = " ORDER BY post_title";
          }
          else {         
             $query2_orderby = " ORDER BY post_date";
          }
          if ($asc == 'DESC') {
             $query2_orderby = $query2_orderby . ' DESC';
          }

          $query2 = $query2_select . $query2_from . $query2_where . $query2_orderby;
          $entradas = $wpdb->get_results($query2);
          
          //Bucle para visualizar cada entrada
          if ($entradas) {
              if ($tipolista == 'tabla') {
                  echo '<table width=100%><tr><td colspan=2><' . $cabest . '><font color=' . $cabcolor . '>' . $autor_nombre_ver . '</font></' . $cabest . '></td></tr>';
               }
              else {
                   echo '<' . $cabest .'><font color=' . $cabcolor . '>' . $autor_nombre_ver . '</font></' . $cabest . '><' . $tipolista . '>';
              }
              foreach ($entradas as $entrada) {
                     $entrada_titulo = $entrada->post_title;
                     $entrada_fecha = $entrada->post_date;
                     $entrada_anio = substr($entrada_fecha, 0 , 4);
                     $entrada_mes = substr($entrada_fecha, 5, 2);
                     $entrada_dia = substr($entrada_fecha, 8, 2);
                     $entrada_nombre = $entrada->post_name;
                     if ($detest == 'em' || $detest == 'strong') {
                        $entrada_compuesta = '<' . $detest . '>' . $entrada_titulo . '</' . $detest . '>';
                     }
                     else {
                        $entrada_compuesta = $entrada_titulo;
                     }
                     if ($dettipo != 'tit') { 
                         $entrada_compuesta = '<a href="/' . $entrada_anio . '/' . $entrada_mes . '/' . $entrada_dia . '/' . $entrada_nombre . '/" target="' . $dettipo . '">' . $entrada_compuesta . '</a>';
                      }
                     if ($tipolista == 'ul' || $tipolista == 'ol') {                          
                         echo '<li>' . $entrada_compuesta . '</li>';  
                     }
                     else {
                        echo '<tr><td width=10%></td><td>';
                        echo $entrada_compuesta;
                        echo '</td></tr>';
                     }
              }
              if ($tipolista == 'tabla') { echo '</table>'; }
              else { echo '</' . $tipolista . '>'; };
          }

       }
    }
}

?>