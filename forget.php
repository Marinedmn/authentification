<?php 

// Formulaire où on saisit son email
// On vérifie que l'email existe, et s'il existe il va falloir
// générer un token dans la BDD et le lier à cet user

// INSERT INTO reset_token (token, expired_at, user_id)
// VALUES (abc456, '2020-09-11 16:30:00')

// Le token doit être généré aléatoirement avec 64 caractères
// Le expired_at doit être l'heure actuelle +1 
// (il faut modifier la structure de la bdd en datetime)

require 'config/config.php';
require 'views/partials/header.php'; 

$error = $email = null;

if (!empty($_POST)) {
    $email = sanitize($_POST['email']);

    // Vérifier que l'email est présent en BDD
    $query = $db->prepare('SELECT * FROM user WHERE email = :email OR pseudo = :email');
    $query->execute(['email' => $email]);
    $user = $query->fetch();

    if ($user) { // on génère le token
        $token = bin2hex(random_bytes(32));
        $expiredAt = (new DateTime())->add(new DateInterval('PT1H'));
        // var_dump($token);
        // var_dump($expiredAt);
        $query = $db->prepare('INSERT INTO reset_token (token, expired_at, user_id)
        VALUES(:token, :expired_at, :user_id)');
        $query->execute([
            'token' => $token,
            'expired_at' => $expiredAt->format('Y-m-d H:i:s'),
            'user_id' => $user['id'],
        ]);
        echo $baseUrl.'/reset.php?token='.$token;
    }
    else {
        $error = 'Le token a été envoyé.'; // pour induire en erreur
    }
}

?>    
<div class="container">
    <?= $error; ?>
    <form action="" method="post">
        <label for="email">Email : </label>
        <input type="email" name="email" id="email" class="form-control" value="<?= $email; ?>">
        <button class="btn btn-primary">Envoyer</button>
    </form>
</div>

<?php 
require 'views/partials/footer.php'; ?>