<?php
/*
Plugin Name: Submarino Produtos
Plugin URI: http://www.battisti.etc.br/wp-plugins/
Description: Mostre produtos do Submarino em seu blog. 
Version: 0.1
Author: Anselmo Battisti
Author URI: http://www.battisti.etc.br/

	Copyright 2010  Anselmo Battisti  (email : anselmobattisti@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

global $wpdb;
global $subprod_options;
global $subprod_version;

$subprod_version = "0.1";
$subprod_options = get_option('subprod_options');

register_activation_hook(__FILE__, 'subprod_activate');
register_deactivation_hook(__FILE__, 'subprod_deactivate');

// Run widget code and init
add_action('widgets_init', 'subprod_widget_init');

# quando ativa o plugin cria um arquivo com o cache dos produtos
function subprod_activate(){
	add_option('subprod_afiliado', ""); // codigo de afiliado 
	add_option('subprod_produtos', ""); // lista com os produtos separados por ; que serão exibidos pelo widget
	add_option('subprod_titulowidget','Produtos Submarino'); // título do widget
	add_option('subprod_cache',''); // cache pre-processado com os produtos do submarino
	add_option('subprod_exibirnumprodutos',3); // quantidade de produtos que serão exibidos, é aleatório a exibição
}

# quando desativa o plugin apaga o arquivo de cache dos produtos
 function subprod_deactivate(){
	delete_option('subprod_afiliado');
	delete_option('subprod_produtos');
	delete_option('subprod_titulowidget');
	delete_option('subprod_cache');
	delete_option('subprod_exibirnumprodutos');
}

# inicia o widget
function subprod_widget_init(){

	if ( !function_exists('register_sidebar_widget') ) return;

	function widget_Submarinoprodutos($args) {
	
		// "$args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys." - These are set up by the theme
		extract($args);

		// These are our own options
		global $subprod_options;

		// were there any posts found?
		$produtos = subprod_core() ;
		if (!empty($produtos)){
			echo $produtos;
		};
	}

	$widget_ops = array('classname' => 'widget_Submarinoprodutos', 'description' => __( 'Vitrine de Produtos do Submarino.com' ) );
	wp_register_sidebar_widget('submarino-produtos', 'Submarino Produtos', 'widget_Submarinoprodutos', $widget_ops);	
}

function subprod_core (){

	$afiliado = get_option("subprod_afiliado");
	$produtos = unserialize(get_option("subprod_cache"));
	$subprod_exibirnumprodutos = get_option("subprod_exibirnumprodutos");
	$num_produtos_cache = sizeof($produtos)-1;

	if(is_array($produtos)){

		// se for para exibir menos do que todos entao sorteia
		if (($subprod_exibirnumprodutos > 0) && ( sizeof($produtos) > $subprod_exibirnumprodutos)){
			$exibir_produtos = array();
			while(sizeof($exibir_produtos) < $subprod_exibirnumprodutos){
				$i = rand(0,$num_produtos_cache);
				$exibir_produtos[$i] = $produtos[$i];
			}
		} else {
				$exibir_produtos[] = $produtos;
		}

		$html = "<ul id='subprod_widget'>";
		foreach($exibir_produtos as $produto){
			$html .= "
				<li>
					<a title='".$produto['titulo']."' href='http://www.submarino.com.br/produto/1/".$produto['id']."?franq=".$afiliado."&ST=SR'>
						<img width='80px' height='80px' src='".$produto['img']."'>
						".$produto['titulo']."
					</a>
				</li>";
		}
		
		$html .= "
			  <li>
				<form method='get' action='http://www.submarino.com.br/busca'>
					<input type='text'  id='buscasubmarino' name='q' value='Buscar Livro' onclick=\"this.value=''\" onblur=\"if(this.value=='') this.value='Busca Livro'\">
					<input type='hidden' name='franq' value='".$afiliado."'>
					<input type='submit' value='Busca'>
				</form>
			  </li>
		</ul>";
		
	}
	return "<div class='sidebox'><h3 class='sidetitl'>".get_option("subprod_titulowidget")."</h3>".$html."</div>";
}

//************ TELA DE OPÇÕES/CONFIGURÇÕES GERAIS ****************************
function subprod_options()
{
    add_options_page('Submarino Produtos', 'Submarino Produtos', 9, 'httpbattistietcbrplugins/subprod_options.php');
}

add_action('admin_menu', 'subprod_options');
?>
