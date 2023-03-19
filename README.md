<p align="center">
  <img src="https://cdn-icons-png.flaticon.com/512/564/564619.png" width=150 />
</p>

# Fui exposto? (Telegram Bot)
Bot para Telegram que checa se determinado e-mail está contido em algum vazamento de dados, via https://haveibeenpwned.com


> Bot desenvolvido para a versão 2 da API (Não testado com a API v3). Agora, é necessário uma chave de API, obtida em https://haveibeenpwned.com/API/Key, além uma pequena adaptação na função ```Conn_mail_curl``` no arquivo ```func.php``` para que use a chave de API. Pull Requests são bem-vindos

## Uso

*  Altere as variáveis no arquivo ***func.php*** (Banco de dados, chave de API, sua própria ID do Telegram - para receber alertas de uso, feedback, etc) e usuário e senha para o arquivo ***broadcast.php***

*  O arquivo ***broadcast.php*** envia mensagens para todos os usuários do banco de dados. Você pode utilizar {nome} e {sobrenome} para mensagens mais personalizadas

* O arquivo ***check.php*** faz a checagem dos domínios cadastrados com as breaches do Have I Been Pwned. De preferência, configure um cron job para ele

## Licença

This project is licensed under the GNU General Public License
