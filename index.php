<?php
	require('func.php');
	//Pega o JSON
	$json = file_get_contents('php://input');
	if(!$json){
		die("die");
	}
	
	//Decodifica o JSON e converte em array
	$dados = json_decode($json, TRUE);			
	
	//Nome
	$nome = $dados['message']['from']['first_name'];
	
	//Sobrenome
	$sobrenome = $dados['message']['from']['last_name'];
	
	//Obtém o chat_id 		
	$chat_id = $dados['message']['from']['id']; 
	
	//Username
	$username = $dados['message']['from']['username'];
	
	//Conteúdo
	$texto = $dados['message']['text']; //Usuário
	
	//Textos
	$txt_start = "👋 Olá, bem-vindo. Este bot mostra se seu email apareceu em recentes (ou não) vazamentos de dados, como Facebook, Linkedin e outros sites. \n\n
	✍️ Modo de uso:\n 
	📧 Envie um email válido, ex: meu@email.com\n
		O bot irá consultar a base de dados do site https://haveibeenpwned.com, que agrega este dados e alerta usuários sobre vazamentos. Se tiver alguma dúvida, use os comandos /privacidade e /faq a qualquer momento. Você pode também cadastrar /alertas e, se quiser, deixe-nos também um /feedback 🙂";
	
	$txt_alerta = "
	🔈 Deseja ser alertado se os serviços que você usa sofrerem um vazamento de dados? Envie #alerta, seguido do domínio (1 por vez)
	
	Por exemplo: 
	
	#alerta linkedin.com
	
	Para visualizar seus alertas, envie apenas /alerta ou #alerta, para exibir apenas os alertas, sem esta mensagem
	
	Se algum dos serviços que você inserir for afetado, você será notificado
	
	";
	
	$txt_privacidade = "
		*O que este bot faz com o email que digito aqui?\n*
		O email que você informar aqui servirá unicamente para retornar se ele está envolvido em vazamentos ou não. Os dados são consultados na base de dados do site https://haveibeenpwned.com e o bot retornados a você. *O email não é repassado ou armazenado em lugar algum*. Entretanto, os domínios que você inserir em /alertas terão que ser armazenados para que possamos enviá-los.
		
		O código do bot é aberto e pode ser consultado a qualquer momento em https://github.com/pedropamn/fuiexposto
	";
	$txt_faq = "
	*◾️ Onde posso confirmar os dados que este bot retorna?*
	R: De onde puxamos os dados, ou seja, no site https://haveibeenpwned.com
	
	*◾️ O bot diz que meu email estava em vazamentos. O que faço agora?*
	R: O melhor a fazer é trocar sua senha que utilizava nos referidos serviços. Lembre-se de fazer isso periodicamente e não utilizar a mesma em vários serviços
	
	*◾️ A mídia noticiou um vazamento de um site que cadastrei, mas não recebi o alerta*
	R: Se o vazamento foi recente (hoje), o bot pode estar processando o seu alerta. Mas caso passe um longo tempo, talvez ele ainda não esteja na base de dados do haveibeenpwned.com. Se isso acontecer, não hesite em nos mandar um /feedback
	
	*◾️ Recebi um alerta dizendo que um domínio que cadastrei foi comprometido. Meus dados foram expostos? 😱*
	R: Talvez. Os alertas mostram que determinado domínio foi comprometido, mas não necessariamente o vazamento contém o seu email. Para checar, apenas envie o seu email para o bot
	
	*◾️ Porque o bot pede os domínios ao invés do e-mail para mandar alertas?*
	R: Para enviar alertas pelo seu email, precisaríamos armazená-lo e optamos por não fazê-lo. Veja mais em /privacidade 
	
	*◾️ Este bot possui código aberto?*
	R: Claro! Você é livre para ver, sugerir mudanças e contribuir. Veja em https://github.com/pedropamn/fuiexposto
	";
	
	$txt_sobre = "
		*Fui Exposto?*\n
		_Bot para consulta de email em vazamentos de dados públicos via_ https://haveibeenpwned.com\n
		Siga o Canal @pamnnetwork para novidades 😁\n
	";
	
	$txt_feedback = "
		📧 Gosta do bot? Tem alguma dúvida, crítica ou sugestão? Use a hashtag #feedback e deixe sua mensagem aqui
	
	Exemplo: #feedback Minha mensagem
	";
	
	if(check_valid_email($texto) == true){
		$email = $texto;
		$texto = 'valid_mail';
	}
	switch($texto){
		case '/start':
			sendMessage($chat_id,$txt_start,"");
			insere_usuario($nome,$sobrenome,$chat_id,$username);
			break;
		case '/alertas':
			sendMessage($chat_id,$txt_alerta,"");
			exibe_alertas($chat_id);
			break;
		case '/feedback':
			sendMessage($chat_id,$txt_feedback,"");
			break;
		case '#alerta':
			exibe_alertas($chat_id);
			break;
		case '#feedback':
			sendMessage($chat_id,"Use #feedback + Mensagem (sem o sinal de +)","");
			break;
		case substr($texto,0,7) == "#alerta" && substr($texto,8,1) != "":
			$arr = explode(" ",$texto);
			$dominio = $arr[1];
			$data_cadastro = date('Y-m-d');
			insere_alerta($chat_id,$dominio,$data_cadastro);			
			break;
		case substr($texto,0,9) == "#feedback" && substr($texto,10,1) != "":
				$txt = substr($texto,10);
				feedback($txt,$chat_id,$nome,$sobrenome,$username);		
			break;
		case substr($texto,0,4) == '/del':
			$id = substr($texto,4);
			remove_alerta($id,$chat_id);
			break;
		case '/privacidade':
			sendChatAction($chat_id,'typing');
			sendMessage($chat_id,$txt_privacidade,"");
			break;
		case '/faq':
			sendChatAction($chat_id,'typing');
			sendMessage($chat_id,$txt_faq,"");
			break;
		case '/sobre':
			sendChatAction($chat_id,'typing');
			sendMessage($chat_id,$txt_sobre,"");
			break;
		case 'valid_mail':			
			//$email = substr($texto,7);
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
				$msg_retorno .= "💡 Se você ainda usa a senha dos serviços acima, é recomendável que troque. Evite repetí-las em vários serviços.
				
				👉 O site https://haveibeenpwned.com não fornece neste resultado dados que possam comprometer ou constranger o dono do email informado, como vazamentos de sites pornográficos ou de encontros extraconjungais, por exemplo. Para uma consulta completa, utilize https://haveibeenpwned.com";
				
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
		default:
			sendChatAction($chat_id,'typing');
			sendMessage($chat_id,"🤔 Ops... Envie uma opção válida ou e-mail válido","");
	}

	
						
?>