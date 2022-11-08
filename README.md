# APPTEST
Dans le fichier .env pour la configuration DNS et connection à la base 
Dans config/routes.yaml pour gestion de route
Dans config/packages/security.yaml :
     - la gestion des Rôles et les hierarchies
     - le hashage de mot de pase 
     - le gestion du login_link (paramètre du liens de connexion temporaire )
Dans config/packages/twig.yaml le thème du bootsrap utilisé 
Dans assets les fichier .js, .json, .css et .scss de configuration
Dans SecurityController.php :
    - controller pour la page de login 
    - controller pout la page de connexion temporaire 
Dans src/Service/MailerService.php le service d'envoie d'Email , les configurations de connexion sont dans .env :
    - pour Maiitrap : MAILER_DSN=smtp://8dc76547d6a1fd:bff8ebe80e0230@smtp.mailtrap.io:2525?encryption=tls&auth_mode=login
    - pour Gmail : MAILER_DSN=gmail://testemailappsoi2@gmail.com:wwxneylcakvwxlez@default?verify_peer=0
    - dans config/packages/mailer.yaml l'addresse email d'envoi par defaut
Dans assets/notyf.js pour la configuration du bibliothèque JavaScript Notyf , pour plus d'information https://www-npmjs-com.translate.goog/package/notyf?_x_tr_sl=en&_x_tr_tl=fr&_x_tr_hl=fr&_x_tr_pto=sc 

SELECT count(*), SUBSTRING(date_quest, 1,10) as date_PJ FROM `question`GROUP BY date_PJ;

SELECT count(*) FROM `question`WHERE finished;

SELECT count(*) FROM `question`WHERE reponse = 0;

