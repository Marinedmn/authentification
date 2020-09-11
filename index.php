<?php

/* Système d'authentification en PHP
Il nous faudra 4 pages :
- inscription (/register)
- connexion (/login)
- mot de passe oublié (/forget)
- formulaire de changement de mot de passe (/reset/123)

On va devoir stocker les utilisateurs donc il nous faut une table user :
- id
- email
- password
- pseudo

On va stocker les tokens de réinitialisation du mot de passe dans une table reset_token :
- id
- token
- expired_at
- user_id

*/