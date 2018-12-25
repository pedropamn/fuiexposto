<?php

//Funções
header("Content-type: text/html; charset=utf-8");
define("API", "");

//Via Curl
function Curl($target_url,$post,$headers){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$target_url);
		curl_setopt($ch, CURLOPT_HEADER,true); //Retorna o Header na saída
		if($headers){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}		
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST ,0);


		$response = curl_exec($ch);
		/*var_dump($response);
		if (!$response){
			echo curl_error($ch);
		}*/
		
		$arr = array(
			'code' => $info["http_code"],
			'json' => $response
		);
		
		//$info = curl_getinfo($ch);
}

//Via file_get_contents
function Conn($target_url,$post){						
	$post = http_build_query($post);
	$context_options = array(
		'http' => array(
			'method' => 'POST',
			'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
				. "Content-Length: " . strlen($post) . "\r\n",
			'content' => $post,
			'timeout' => 60
			)
		);

	$context = stream_context_create($context_options);				
	$con = file_get_contents($target_url,NULL,$context);
	return $con;
}

/* Telegram Functions */

function sendMessage($chat_id,$text,$id_reply){
		$target_url = "https://api.telegram.org/bot" . API ."/sendMessage";
		$post = array(
			'chat_id'=> $chat_id,
			'text'   => $text,
			'parse_mode' => 'markdown',
			'disable_web_page_preview' => true,
			'reply_to_message_id' => $id_reply
		);
		
		return Conn($target_url,$post);		
 }
 
 function sendPhoto($chat_id,$photo,$caption,$id_reply){
		$target_url = "https://api.telegram.org/bot" . API ."/sendPhoto";
		$post = array(
			'chat_id'=> $chat_id,
			'photo'   => $photo,
			'caption'   => $caption,
			'reply_to_message_id' => $id_reply
		);
		
		Conn($target_url,$post);		
 }
 
 function sendAudio($chat_id,$audio,$id_reply){
		$target_url = "https://api.telegram.org/bot" . API ."/sendAudio";
		$post = array(
			'chat_id'=> $chat_id,
			'audio'   => $audio,
			'reply_to_message_id' => $id_reply
		);
		
		Conn($target_url,$post);		
 }
 
 function sendDocument($chat_id,$document,$id_reply){
		$target_url = "https://api.telegram.org/bot" . API ."/sendDocument";
		$post = array(
			'chat_id'=> $chat_id,
			'document'   => $document,
			'reply_to_message_id' => $id_reply
		);
		
		Conn($target_url,$post);		
 }
 
 function sendSticker($chat_id,$sticker,$id_reply){
		$target_url = "https://api.telegram.org/bot" . API ."/sendSticker";
		$post = array(
			'chat_id'=> $chat_id,
			'sticker'   => $sticker,
			'reply_to_message_id' => $id_reply
		);
		
		Conn($target_url,$post);		
 }
 
 function sendVideo($chat_id,$video,$id_reply){
		$target_url = "https://api.telegram.org/bot" . API ."/sendVideo";
		$post = array(
			'chat_id'=> $chat_id,
			'video'   => $video,
			'reply_to_message_id' => $id_reply
		);
		
		Conn($target_url,$post);		
 }
 
 function sendVoice($chat_id,$voice,$id_reply){
		$target_url = "https://api.telegram.org/bot" . API ."/sendVoice";
		$post = array(
			'chat_id'=> $chat_id,
			'voice'   => $voice,
			'reply_to_message_id' => $id_reply
		);
		
		Conn($target_url,$post);		
 }
 
  function sendChatAction($chat_id,$action){
		$target_url = "https://api.telegram.org/bot" . API ."/sendChatAction";
		$post = array(
			'chat_id'=> $chat_id,
			'action'   => $action
		);
		
		Conn($target_url,$post);		
 }
 
//Pesquisa Email no haveibeenpwned. Header User-Agent é necessário
 function Conn_mail($email){			
					
	$context_options = array(
		'http' => array(
			'method' => 'GET',
			'header'=> "Content-type: application/x-www-form-urlencoded\r\n".
						"User-Agent: Pwnage_".generateRandomString(10)."-Checker-For-Telegram\r\n",
			'timeout' => 60
			)
		);
	
	$context = stream_context_create($context_options);			
	$con = file_get_contents("https://haveibeenpwned.com/api/v2/breachedaccount/{$email}",NULL,$context);
	
	$retorno = $http_response_header[0];
	if($retorno == 'HTTP/1.1 200 OK'){
		$ret = '200';
	}
	else if($retorno == 'HTTP/1.1 404 Not Found'){
		$ret = '404';
	}
	else{
		$ret = $retorno;
	}
	
	$arr = array(
		'code' => $ret,
		'json' => $con
	);
	
	return $arr;
 }
 
  function Conn_mail_curl($email){			
					
	 $headers = [            
            'User-Agent: Meu user agent'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://haveibeenpwned.com/api/v2/breachedaccount/{$email}");
        //curl_setopt($ch, CURLOPT_HEADER,true); //Retorna o Header na saída
        //if($headers){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //}     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST ,0);

        $response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		$arr = array(
			'code' => $httpcode,
			'json' => $response
		);
        return $arr;
 }
 
 /* Pesquisa Senha no haveibeenpwned. Header User-Agent é necessário
	Para pesquisar a senha, é necessário:
	1 - Gerar o hash SHA-1
	2 - Pesquisar pelos 5 primeiros caracteres do hash. Irá retornar todos os hashes que começam com estes 5 (mas vai retornar sem os 5 primeiros caracteres)
	3 - Em seguida, pesquisar neste retorno pelos caracteres restantes do hash gerado (do caracter 6 em diante)
 */
  function Conn_pass($pass){
			
			$senha_bruta = $pass;
			
			//Gera o SHA-1
			$sha1 = sha1($senha_bruta);
			
			//5 primeiros do SHA-1 da senha
			$sha1_five = substr($sha1,0,5); 
			
			//Restante, sem os 5 primeiros
			$sha1_restante = substr($sha1,6); 
			
					
			$context_options = array(
				'http' => array(
					'method' => 'GET',
					'header'=> "Content-type: application/x-www-form-urlencoded\r\n".
								"User-Agent: Pwnage-Checker-For-Telegram\r\n",
					'timeout' => 60
					)
				);
		
		$context = stream_context_create($context_options);			
		$con = file_get_contents("https://api.pwnedpasswords.com/range/{$sha1_five}",NULL,$context);
		
		//Procura o SHA-1 restante dentro do retornado
		if(stristr($con,$sha1_restante)){
			$split = explode(":",stristr($con,$sha1_restante));
			return $split[1];
		}
		else{
			return false;
		}
 }
 
//Tradução com Yandex. Direction: en-pt
 function translate_en_pt($txt){
	 $txt = urlencode($txt);
	 $json = file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=pt&dt=t&q={$txt}");
	 $dec = json_decode($json,true);
	 return $dec[0][0][0];
 }

 //Conversão de formato de data
function formata_data($data){
	return date('d/m/Y',strtotime($data));
}
 
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
 


	//Pega o JSON
	$json = file_get_contents('php://input');
	
	//Decodifica o JSON e converte em array
	$dados = json_decode($json, TRUE);			
	
	//Obtemos o nome do usuário
	$nome_do_user = $dados['message']['from']['first_name'];
	
	//Obtém o chat_id 		
	$chat_id = $dados['message']['from']['id']; //Usuário
	
	//Conteúdo
	$texto = $dados['message']['text']; //Usuário
	
	//Textos
	$txt_start = "👋 Olá, bem-vindo. Este bot mostra se seu email ou sua senha apareceram em grandes vazamentos de dados, como Facebook, Linkedin e outros sites. \n\n
	✍️ Modo de uso:\n 
	📧 /email meu@email.com\n
	Ou\n
	🔑 /senha minhasenhalegal123";
	
	$txt_privacidade = "
		*O que este bot faz com o email e a senha que digito aqui?\n*
		O que você informar aqui servirá unicamente para retornar se o dado está envolvido em vazamentos ou não. Os dados são consultados na base de dados do site https://haveibeenpwned.com e retornados a você. *Nada é repassado ou armazenado em lugar algum*. Você pode consultar o código do bot a qualquer momento em https://github.com/pedropamn/fuiexposto
	";
	$txt_faq = "
	*Onde posso confirmar os dados que este bot retorna?*
	R: De onde puxamos os dados, ou seja, no site https://haveibeenpwned.com
	
	*O bot diz que meu email e minha senha estavam em vazamentos. O que faço agora?*
	R: O melhor a fazer é trocar sua senha que utilizava nos referidos serviços. Lembre-se de fazer isso periodicamente e não utilizar a mesma em vários serviços
	
	*Digitei meu email e o bot retornou os vazamentos. O bot recebe a senha que eu usava na época?*
	R: Claro...QUE NÃO. Esta informação é confidencial e não cedida pelo haveibeenpwned a aplicativos de terceiros (como este bot)
	
	*O bot diz que minha senha consta em X vazamentos, mas não fala que senhas são essas...*
	R: O bot não recebe esta informação quanto a senhas. Apenas se constam em vazamentos e em quantos.
	
	*Huum, sei não...Quero ver o código deste bot. Posso?*
	R: Claro! Você é livre para ver, sugerir mudanças e contribuir. Veja em https://github.com/pedropamn/fuiexposto
	";
	
	
	switch($texto){
		case '/start':
			sendMessage($chat_id,$txt_start,"");
			break;
		case '/privacidade':
			sendChatAction($chat_id,'typing');
			sendMessage($chat_id,$txt_privacidade,"");
			break;
		case '/faq':
			sendChatAction($chat_id,'typing');
			sendMessage($chat_id,$txt_faq,"");
			break;
		case substr($texto,0,6) == "/email":			
			$email = substr($texto,7);
			$ret_conn_mail = Conn_mail_curl($email);			
			$msg_retorno = "";
			if($ret_conn_mail['code'] == '200'){
				$msg_retorno = "❗️ Ops... Este email consta em algum vazamento de dados...\n\n";
				$num_reg = count($ret_conn_mail['json']);
				$dec = json_decode($ret_conn_mail['json'],true);
				foreach($dec as $dec){
					$nome = $dec['Name'];
					$dominio = $dec['Domain'];
					$data = $dec['BreachDate'];
					$descricao = translate_en_pt(strip_tags($dec['Description']));
					$dados = $dec['DataClasses'];
					foreach($dados as $dados){
						$dadoss .= $dados.',';
					}
					$dadoss = substr($dadoss, 0, -1);
					
					$msg_retorno .= "🔤 *Nome*: {$nome} \n";
					$msg_retorno .= "🔗 *Domínio*: {$dominio}\n";
					$msg_retorno .= "📆 *Data*: ".formata_data($data)."\n";
					$msg_retorno .= "✍️ *Descrição*: _".$descricao."_\n";
					$msg_retorno .= "👀 *Dados vazados*: ".translate_en_pt($dadoss)."\n\n";
					$msg_retorno .= "➖➖➖➖➖➖➖➖➖➖➖\n\n";
				}
				$msg_retorno .= "💡 O site https://haveibeenpwned.com não fornece neste resultado dados que possam comprometer ou constranger o dono do email informado, como vazamentos de sites pornográficos ou de encontros extraconjungais, por exemplo. Para uma consulta completa, utilize https://haveibeenpwned.com";
				
				if(strlen($msg_retorno) > 4096){
					$parts = str_split($msg_retorno, 4096);
					foreach($parts as $part){
						sendChatAction($chat_id,'typing');
						sendMessage($chat_id,$part,"");
					}
					die();
				}
				else{
					sendChatAction($chat_id,'typing');
					sendMessage($chat_id,$msg_retorno,"");
				}
				
				
			}
			else if($ret_conn_mail['code'] == '404'){
				sendChatAction($chat_id,'typing');
				sendMessage($chat_id,"✅ Boas notícias! Este email não aparece em nenhum vazamento registrado 😁. Mas leve em conta que resultados que possam ser considerados prejudiciais ou comprometedores ao dono do email não são fornecidos a este bot pelo site https://haveibeenpwned.com, como vazamentos de sites pornográficos ou de encontros extraconjungais, por exemplo. Dê uma olhada no site oficial para uma consulta completa 😉","");
			}
			else{
				sendChatAction($chat_id,'typing');
				sendMessage($chat_id,"🤔 Ops... Houve um erro desconhecido. Tente novamente. Erro ".$ret_conn_mail['code'],"");
			}
			break;
		case substr($texto,0,6) == "/senha":
			$senha = substr($texto,7);
			$pass = Conn_pass($senha);
			if($pass != false){
				sendChatAction($chat_id,'typing');
				sendMessage($chat_id,"⚠️ Huuumm, isso não é bom... Esta senha consta em pelo menos ".substr($pass,0,1)." vazamentos... Ainda a utiliza? Troque-a imediatamente","");
			}
			else{
				sendChatAction($chat_id,'typing');
				sendMessage($chat_id,"✅ Boas notícias! Esta senha não consta em nenhum vazamento público! De qualquer forma, nunca utilize a mesma e troque-as periodicamente 😉","");
			}
			break;
	}

	
						
?>