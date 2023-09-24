<p align="center">
  <img src="https://cdn-icons-png.flaticon.com/512/564/564619.png" width=150 /><br>
  <img src="https://raw.githubusercontent.com/pedropamn/fuiexposto/master/fuiexposto.gif" width=600 />
</p>

# Fui exposto? (Telegram Bot)
Telegram bot that checks whether a given email is part of a data leak, via https://haveibeenpwned.com

> Bot developed for version 2 of the API. Not tested with API v3 (only one currently supported). Now, an API key is required, obtained from https://haveibeenpwned.com/API/Key, in addition to a small adaptation in the ```Conn_mail_curl``` and ```check_alertas``` functions in ```func. php``` so that both use the key in the request

## Usage

* Change the variables in the ***func.php*** file (Database, API key, your own Telegram ID - to receive usage alerts, feedback, etc) and username and password for the  file ***broadcast.php***

* The ***broadcast.php*** file sends messages to all database users. You can use {first name} and {last name} for more personalized messages

* The file ***check.php*** checks the domains registered with the Have I Been Pwned breaches. Preferably, set up a cron job for it

## License

This project is licensed under the GNU General Public License
