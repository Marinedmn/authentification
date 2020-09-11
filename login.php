<?php

// Afficher le formulaire (email, password)
// Traiter le formulaire
// - Vérifier que l'email existe en BDD
// - S'il existe :
//    - On peut comparer le mdp saisi avec le hash avec password_verify
//    - Si c'est true, on connecte le user
//    - On démarre la session, on ajouter le user dans la session
//    - Si c'est false, on affiche un message d'erreur
// - Si l'email n'existe pas on affiche un msg d'erreur

// Dans la navbar, on affiche le pseudo de l'utilisateur dès qu'il est connecté

require 'config/config.php';
require 'views/partials/header.php'; 

$errors = $email = $pseudo = null;

if (!empty($_POST)) { // Traitement du login
    $email = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);

    // Vérifier que l'email est présent en BDD
    $query = $db->prepare('SELECT * FROM user WHERE email = :email OR pseudo = :email');
        // execute(compact('email'))
    $query->execute(['email' => $email]);
    $user = $query->fetch();

    if ($user) { // On va vérifier le mdp
        $isValid = password_verify($password, $user['password']); // retourne true si le hash correspond au mdp
        
        if ($isValid) {
            // Je dois me connecter avec la session
            $_SESSION['user'] = [
                'pseudo' => $user['pseudo'],
                'email' => $user['email'],
            ];

            redirect();
        }
        else {
            // On a une erreur
            $error = 'Mot de passe invalide';
        }
    }
    else { // On a une erreur
        $error = 'Mot de passe invalide';
    }
}


?>

<div class="container">

    <form action="" method="post">
        <p>
        <label for="email">Email : </label>
        <input type="email" name="email" id="email" class="form-control" value="<?= $email; ?>">
        </p>
        <p>
        <label for="password">Password : </label>
        <input type="password" name="password" id="password" class="form-control" value="<?= $password; ?>">
        </p>
        <p>
        <button>Connexion</button>
        </p>
    </form>
</div>


<?php
require 'views/partials/footer.php'; 

?>

