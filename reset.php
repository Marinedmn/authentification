<?php 

require 'config/config.php';
require 'views/partials/header.php';  

// On vérifie si le token existe
$query = $sb->prepare('SELECT * FROM reset_token WHERE token = :token');
$query->execute(['token' => $_GET['token']]);
$token = $query->fetch();

// S'il n'existe pas
if (!$token) {
    http_response_code(404);
    die('404'); // On arrête le script
}

// S'il est expiré
$now = new DateTime();
$expiredAt = new DateTime($token['expired_at']); // $token['expired_at'] = 2020-09-11 17:49:36

if ($now > $expiredAt) {
    // @todo: idéalement, on supprime le token de la bdd
    http_response_code(404);
    die('404'); // On arrête le script
}

// S'il existe
if (!empty($_POST)) {
    $password = sanitize($_POST['password']);
    $cfPassword = sanitize($_POST['cfPassword']);

    // @todo: Faire les vérifications

    $password = password_hash($password, PASSWORD_DEFAULT);

    // On mets à jour le mot de passe du user
    $db->prepare('UPDATE user SET password = :password');
    $query->execute(['password' => $password]);

    // On supprime le token
    $d->query('DELETE FROM reset_token WHERE id = '.$token['id']);

    redirect('/login.php');
}

?>

<div class="container">
    <form action="" method="post">
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" class="form-control">
        
        <label for="cfPassword">Confirmer le mot de passe</label>
        <input type="password" name="cfPassword" id="cfPassword" class="form-control">
        
        <button class="btn btn-primary">Envoyer</button>
    </form>
</div>


<?php
require 'views/partials/footer.php';