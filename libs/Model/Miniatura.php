<?php
function miniatura($prefixo, $img, $caminho, $onde_salvar, $modo_de_rdm, $largura, $altura, $qualidade=85, $marca = 'nao'){

 //adiciona a imagem ao caminho
 $caminho = $caminho.$img;

 if(substr($caminho,0,3) == '../'){
  $volta = '../';
 } else {
  $volta = '';
 }
 
 //checa se imagem existe, se não joga o erro de não encontrada
 if(!file_exists($caminho) or empty($img)) {
  $caminho = $volta.'imagens/imagem_nao_encontrada.gif';
  $modo_de_rdm = 'esticar';
 } 
 
 //criar diretorio das miniaturas se nao existir
 if (!file_exists($onde_salvar.$prefixo)) {
    mkdir($onde_salvar.$prefixo, 0777, true);
 }
 
 $foto_peq = $onde_salvar.$prefixo.$img;
 
 if(file_exists($foto_peq) && !isset($_GET['substitui'])){
  return $onde_salvar.$prefixo.$img;
  exit;
 } elseif(!file_exists($foto_peq) or isset($_GET['substitui'])) {
  //retorna informações da imagem
  list($largura_orig, $altura_orig) = getimagesize($caminho);
  
  if($largura == 'auto' && $altura == 'auto'){
   $largura = $largura_orig;
   $altura = $altura_orig;
  }

  //pra deixar uma altura proporcinal ou fixa na imagem
  if($altura == 'auto'){
   $proporcao = (float)$altura_orig/$largura_orig;	  
   $mini_altura = round($largura * $proporcao);   
  }	else {
   $mini_altura = $altura;	
  }
  
  $altura = $mini_altura;
  
  //pra deixar a largura proporcinal ou fixa na imagem
  if($largura == 'auto'){
   $proporcao = (float)$largura_orig/$altura_orig;	  
   $mini_largura = round($altura * $proporcao);   
  }	else {
   $mini_largura = $largura;	
  }  
  
  $largura = $mini_largura;
  
  if($largura_orig < $largura){
   $largura = $largura_orig;
   $altura = $altura_orig;
   $mini_largura = $largura;
   $mini_altura = $altura;
  }  
			
  //detecta o tipo de arquivo
  $fonte = imagecreatefromstring(file_get_contents($caminho));
  //cria canvas
  $mini = imagecreatetruecolor($largura, $altura);		
  //para deixar as png com transpararencia
  $branco = imagecolorallocate($mini, 255, 255, 255);
  imagefill($mini, 0, 0, $branco);
  
  //calcula a proporção da altura da miniatura de acordo com a largura, para fazer as verificações
  $proporcao = (float)$altura_orig/$largura_orig;	  
  $verifica_altura = round($largura * $proporcao);
  
    
  if($modo_de_rdm == 'cortar'){

  if($verifica_altura == $altura){
   imagecopyresampled($mini, $fonte, 0, 0, 0, 0, $mini_largura, $mini_altura, $largura_orig, $altura_orig);
  } else {        
  	if($verifica_altura > $altura){   	
	 $corte = round(($verifica_altura - $altura)/2);		 
     imagecopyresampled($mini, $fonte, 0, -$corte, 0, 0, $mini_largura, $verifica_altura, $largura_orig, $altura_orig);
	} else {
	  $proporcao = (float)$largura_orig/$altura_orig;	  
      $mini_largura = round($altura * $proporcao);  
	  $aumenta = round(($mini_largura - $largura)/2);
	 $mostra = $largura;
     imagecopyresampled($mini, $fonte, -$aumenta, 0, 0, 0, $mini_largura, $mini_altura, $largura_orig, $altura_orig);	 
	}
  }
  
  //calcula a proporção da altura da miniatura de acordo com a largura, para fazer as verificações
  $proporcao = (float)$altura_orig/$largura_orig;	  
  $verifica_largura = round($altura * $proporcao);
  
  }
  
  if($mini_largura == 'auto' or $mini_altura == 'auto' or $modo_de_rdm == 'esticar'){ 
   imagecopyresampled($mini, $fonte, 0, 0, 0, 0, $mini_largura, $mini_altura, $largura_orig, $altura_orig);	 
  } 
  
  if($marca == 'marca'){
   $marca =  $volta."imagens/marca.png";
   $imagem_marca = imagecreatefrompng($marca);
$preto = imagecolorallocate($imagem_marca, 0, 0, 0);

imagecolortransparent ($imagem_marca, $preto);

   $marca_larg=imagesx($imagem_marca);
   $marca_alt= imagesy($imagem_marca);
  imagecopyresampled($mini, $imagem_marca, $mini_largura-($marca_larg+10), $mini_altura-($marca_alt+10), 0, 0, $marca_larg, $marca_alt, $marca_larg, $marca_alt);	
  }
  
  //destrói, para liberar memória
  imagedestroy($fonte);			
 
  //cria thumb baseada no tipo da imagem	
  if($caminho == $volta.'imagens/imagem_nao_encontrada.gif'){
   $miniatura = $onde_salvar.'imagem_nao_encontrada.gif';
  } else {   
   if(strpos($img,'/') !== false){
    $img = str_replace('/','_',$img);
   }
   
   $miniatura = $onde_salvar.$prefixo.$img;				
  } 
 
  imagejpeg($mini, $miniatura, $qualidade);
 
  return $miniatura; 
  exit;
}

}
?>