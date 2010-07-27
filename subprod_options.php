<? 
$lib   = '../wp-content/plugins/httpbattistietcbrplugins/simplehtmldom/simple_html_dom.php';
require_once($lib);

if(isset($_POST['subprod_afiliado']))
{
	$subprod_afiliado          = $_POST['subprod_afiliado'];	
	$subprod_produtos          = $_POST['subprod_produtos'];
	$subprod_titulowidget      = $_POST['subprod_titulowidget'];
	$subprod_exibirnumprodutos = $_POST['subprod_exibirnumprodutos'];

	update_option("subprod_afiliado",$subprod_afiliado);
	update_option("subprod_produtos",$subprod_produtos);
	update_option("subprod_titulowidget",$subprod_titulowidget);
	update_option("subprod_exibirnumprodutos",$subprod_exibirnumprodutos);

	subprod_cache($subprod_afiliado, $subprod_produtos);

	// criar o cache dos produtos salvos
	echo '<div id="message" class="updated">Salvo com sucesso.</div>';
}

function subprod_cache($subprod_afiliado, $subprod_produtos)
{
	$produtos = explode(";",$subprod_produtos);

	if(is_array($produtos)){
	
		$produtos_cache = null;
		
		foreach($produtos as $id){
		
			$url = 'http://www.submarino.com.br/produto/1/'.$id;

			$produto['id'] = $id;

			// get DOM from URL or file
			$html = file_get_html($url);

			// encontra a imagem
			foreach($html->find('#baseImg') as $e)
				$produto['img'] = $e->src;

			// encontra o tituulo
			foreach($html->find('.boxProductName h1') as $e)
				$produto['titulo'] = substr($e,4,(strpos($e,"span")-5));

			$produtos_cache[] = $produto;
		}
	}

	// escreve no arquivo de cache os produtos
	update_option("subprod_cache",serialize($produtos_cache));
}
?>
<style type='text/css'> 
#creditssubprod { list-style-type: circle; margin: 10px; padding: 10px;}
.stuffbox{padding:10px}
</style> 
<div class="wrap" id='poststuff'> 

	<h2>Produtos Submarino - Configurações</h2>

	<div class="stuffbox"><h3>Créditos</h3>
		<div class="inside">
			<h4>Produtos Submarino - <a target="_blank" href="http://battisti.etc.br/plugins/">http://battisti.etc.br/plugins/</a></h4>

<p>Não cobramos nenhuma porcentagem sobre seus ganhos e nem porcentagens em exibição (em nenhum dos programas de afiliados). Pode conferir no código fonte.</p>
<p>A minha gratificação virá a partir do seu reconhecimento, links, reviews, e... também uma pequena comissão que tento negociar diretamente com os sistemas de afiliados (que NÃO interfere em seus ganhos)</p>

<ul id="creditssubprod">
			<li><a target="_blank" href="http://twitter.com/battisti">@battisti</a> - Poste aqui dúvidas e sugesões.</li>
			<li>Como diz o micox "Fazer esta ferramenta e este plugin deu trabalho pra caramba. Perdi muitas horas de sono. Não roube meu trabalho. Em vez disso, faça uma doação :P </li>
</ul>

<em style='color:green; font-size:14px'>Caso o plugin te ajudou a ganhar uma grana, ajude quem fez o plugin a ganhar uma grana também, doar faz bem :)</em> <br/><br>
			
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypal.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - A maneira mais fácil e segura de efetuar pagamentos on-line!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>


		</div>
	</div>

	<div class="stuffbox"><h3>Configurações</h3>

	<form method="post" action="#" class='meta-box-sortabless'>

		<?php wp_nonce_field('update-options'); ?>
	
		<p><label>Código de Afiliado / Franqueado:<input type='text' name='subprod_afiliado' id='afiliado' value='<? echo get_option('subprod_afiliado') ?>'/></label></p>
	
		<p><label>Código dos Produtos (separados por ; 'ponto e vírgula'):<input type='text' size='200' name='subprod_produtos' id='produtos' value='<? echo get_option('subprod_produtos') ?>'/></label></p>
	
		<p><label>Título Widget:<input type='text' name='subprod_titulowidget' id='titulo' value='<? echo get_option('subprod_titulowidget') ?>'/></label></p>
		<p><label>Número de produtos a exibir:<input type='text' name='subprod_exibirnumprodutos' id='exibirnumprodutos' value='<? echo get_option('subprod_exibirnumprodutos') ?>'/></label></p>

		<input type="submit" class="button-primary" value="Salvar & Atualizar Cache" />
	</form>
	</div>
</div>
