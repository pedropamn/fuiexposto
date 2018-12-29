<?php
//Funções
date_default_timezone_set('America/Sao_Paulo'); //Timezone
header("Content-type: text/html; charset=utf-8");
define("API", "SUA_CHAVE_API"); //API Telegram
define("MEU_ID", "SEU_TELEGRA_ID"); //ID para que o bot te envie em privado alguns eventos, como erro em banco de dados, etc
define("USER_BROADCAST", "user"); //Usuário para broadcast
define("PASS_BROADCAST", "senha"); //Senha para broadcast
 

//Conexão com o DB
function get_conn(){
		$host = "host";
		$user = "user";
		$pass = "senha";
		$db = "banco";	
		
		$conn = new mysqli($host, $user, $pass, $db);
		return $conn;
}




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
 

 
//Tradução com Google Tradutor, de inglês para português
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

//Gera string aleatória
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//Check email válido
function check_valid_email($string){
	if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
		return true;
	}
}



function insere_usuario($nome,$sobrenome,$chat_id,$username){
	$conn = get_conn();	
	
	//Checa se o usuário já existe
	$stmt = $conn->prepare("SELECT chat_id FROM usuarios WHERE chat_id = ?");
	$stmt->bind_param('i',$chat_id);
	$stmt->execute();
	$stmt->store_result();
	$num_rows = $stmt->num_rows;
	
	if($num_rows < 1){
		$stmt = $conn->prepare("INSERT INTO usuarios (nome,sobrenome,chat_id,username) VALUES (?,?,?,?)");
		$stmt->bind_param('ssis',$nome,$sobrenome,$chat_id,$username);
		$stmt->execute();
		if($stmt->affected_rows < 1){
			sendMessage(MEU_ID,"Erro ao cadastrar o usuário ".$username." - ID ".$chat_id."","");
		}
	}

	
}

function insere_alerta($chat_id,$dominio,$data_cadastro){
	$conn = get_conn();	
	
	//Verifica se já há alerta para este domínio para o usuário
	$stmt = $conn->prepare("SELECT COUNT(*) FROM alertas WHERE chat_id = ? AND dominio = ?");
	$stmt->bind_param('is',$chat_id,$dominio);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($num_results);
	$stmt->fetch();
	if($num_results > 0){
		sendMessage($chat_id,"Você já cadastrou esse domínio. Veja com #alerta","");
	}
	else{
		$stmt = $conn->prepare("INSERT INTO alertas (chat_id,dominio,data_cadastro) VALUES (?,?,?)");
		$stmt->bind_param('iss',$chat_id,$dominio,$data_cadastro);
		$stmt->execute();
		if($stmt->affected_rows < 1){
			sendMessage(MEU_ID,"Erro ao cadastrar alerta - ID ".$chat_id."","");
			sendMessage($chat_id,"Ops... Erro ao cadastrar alerta. Tente novamente","");
		}
		else{
			sendMessage($chat_id,"✅ Alerta cadastrado com sucesso!","");
			exibe_alertas($chat_id);
		}
	}
	

	
}

function exibe_alertas($chat_id){
	$conn = get_conn();	
	
	$stmt = $conn->prepare("SELECT id,chat_id,dominio,data_cadastro FROM alertas WHERE chat_id = ?");
	$stmt->bind_param('i',$chat_id);
	$stmt->execute();
	$stmt->store_result();
	//$result = $stmt->get_result();
	$stmt->bind_result($id,$chat_id,$dominio,$data_cadastro);
	$num_rows = $stmt->num_rows;
	
	if($num_rows < 1){
		sendMessage($chat_id,"🔇 Sem alertas. Envie #alerta + domínio para cadastrar \n(ex: #alerta linkedin.com)","");
		die();
	}
	
	$msg = "";
	while ($stmt->fetch()){
		//$id = $row['id'];
		//$dominio = $row['dominio'];
		$msg .= "🌎 Domínio: ".$dominio."\n❌/del".$id."\n\n➖➖➖➖➖\n\n";
	}
	
	sendMessage($chat_id,$msg,"");
	
}

function remove_alerta($id,$chat_id){
	$conn = get_conn();	
	$stmt = $conn->prepare("DELETE FROM alertas WHERE id = ? AND chat_id = ?");
	$stmt->bind_param('ii',$id,$chat_id);
	$stmt->execute();
	if($stmt->affected_rows < 1){
		sendMessage(MEU_ID,"Erro ao deletar o alerta de ID ".$id." - Chat id ".$chat_id."","");
		sendMessage($chat_id,"❌ Erro ao deletar o alerta. Tente novamente","");
	}
	else{
		sendMessage($chat_id,"✅ Alerta removido com sucesso","");
		exibe_alertas($chat_id);
	}
}



function broadcast($txt){
	$conn = get_conn();	
	
	$stmt = $conn->prepare("SELECT nome,sobrenome,chat_id FROM usuarios");
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($nome,$sobrenome,$chat_id);
	$num_rows = $stmt->num_rows;
	while($stmt->fetch()){
		if(stristr($txt,'{nome}') || stristr($txt,'{sobrenome}')){
			$arr_procura = array('{nome}','{sobrenome}');
			$arr_replace = array($nome,$sobrenome);
			
			$texto = str_replace($arr_procura, $arr_replace, $txt);
		}
		else{
			$texto = $txt;
		}
		
		sendMessage($chat_id,$texto,"");
	}
}



function check_alertas(){	
	$json = file_get_contents('https://haveibeenpwned.com/api/v2/breaches');
	
	
	$conn = get_conn();	
	
	$stmt = $conn->prepare("SELECT chat_id,dominio,data_cadastro FROM alertas");
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($chat_id,$dominio,$data_cadastro);
	$num_rows = $stmt->num_rows;
	$decode = json_decode($json,true);
	while($stmt->fetch()){
		foreach($decode as $dec){
			$dominio_breach = $dec['Domain'];
			$data_breach = $dec['BreachDate'];
			$desc = strip_tags($dec['Description']);
			$dados = $dec['DataClasses'];
			$dados_vazados = "";
			foreach($dados as $dados){
				$dados_vazados .= $dados.',';
			}
			$dados_vazados = substr($dados_vazados, 0, -1);
			
			if(strtotime($data_breach) > strtotime($data_cadastro)){
				if($dominio == $dominio_breach){
					$msg = "⚠️ Opa! Parece que algo aconteceu com o domínio ".$dominio.", que você cadastrou! Veja abaixo detalhes do incidente:\n\n➖➖➖";
					
					
					//$traducao = translate_en_pt($desc);
					$msg .= "\n".translate_en_pt($desc)."Os dados vazados incluem ".translate_en_pt($dados_vazados)."\n\n➖➖➖\n\n👉 Seu e-mail estava envolvido? Envie-o para checar";
					sendMessage($chat_id,$msg,"");
					sendMessage(MEU_ID,"Alerta enviado sobre o domínio ".$dominio."","");
					
					//Altera a data do alerta para a data de hoje
					$hoje = date('Y-m-d');
					$stmt = $conn->prepare("UPDATE alertas SET data_cadastro = ? WHERE dominio = ? AND chat_id = ?");
					$stmt->bind_param('ssi',$hoje,$dominio,$chat_id);
					$stmt->execute();
					$stmt->store_result();
					
				}
			}
		}
		
	}

}

function feedback($txt,$chat_id,$nome,$sobrenome,$username){
	$msg = "
		📧 Novo Feedback
		🅰️ {$nome} {$sobrenome}
		🆔 {$chat_id}
		👤 @{$username}
		
		Mensagem: _{$txt}_
	";
	sendMessage(MEU_ID,$msg,"");
	sendMessage($chat_id,"Feedback enviado ✅","");
} 
?>