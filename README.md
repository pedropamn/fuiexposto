# Fui exposto? (Telegram Bot)
Bot para Telegram que checa se determinado e-mail está contido em algum vazamento de dados, via https://haveibeenpwned.com

## Uso

*  Altere as variáveis no arquivo ***func.php*** (Banco de dados, chave de API, sua própria ID do Telegram (para receber alertas de uso, feedback, etc) e usuário e senha para o arquivo ***broadcast.php***

*  O arquivo ***broadcast.php*** envia mensagens para todos os usuários do banco de dados. Você pode utilizar {nome} e {sobrenome} para mensagens mais personalizadas

* O arquivo ***check.php*** faz a checagem dos domínios cadastrados com as breaches do Have I Been Pwned. De preferência, configure um cron job para ele
