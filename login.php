<?php
session_start();

$host = "localhost";
$base = "boutique";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$base", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * FROM user WHERE username = :username AND password = :password";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            // L'utilisateur est authentifié avec succès
            $_SESSION['utilisateur'] = $username;

            // Nettoyer les cookies existants lors de la connexion
            setcookie('remember_user', '', time() - 3600, '/');
            setcookie('remember_password', '', time() - 3600, '/');


            if (isset($_POST['remember'])) {
                // Si l'utilisateur a coché "Se souvenir de moi", créez des cookies pour le nom d'utilisateur et le mot de passe
                $cookie_name_user = 'remember_user';
                $cookie_name_password = 'remember_password';
                $cookie_value = $username;
                setcookie($cookie_name_user, $cookie_value, time() + 3600 * 24, '/'); // Cookie valable pendant une semaine
                setcookie($cookie_name_password, $password, time() + 3600 * 24, '/'); // Cookie valable pendant une semaine
            }

            header('Location: crudProduit.php'); // Redirige l'utilisateur vers la page de gestion des produits
            exit();
        }
    }
    // Vérifiez si les cookies "remember_user" et "remember_password" existent
    if (isset($_COOKIE['remember_user']) && isset($_COOKIE['remember_password'])) {
        $cookie_username = $_COOKIE['remember_user'];
        $cookie_password = $_COOKIE['remember_password'];
    } else {
        $cookie_username = ''; // Valeur par défaut
        $cookie_password = ''; // Valeur par défaut
    }
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap Simple Login Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .login-form {
            width: 340px;
            margin: 50px auto;
        }

        .login-form form {
            margin-bottom: 15px;
            background: #f7f7f7;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }

        .login-form h2 {
            margin: 0 0 15px;
        }

        .form-control,
        .btn {
            min-height: 38px;
            border-radius: 2px;
        }

        .btn {
            font-size: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php require 'headerlogin.php'; ?>
    <div class="login-form">
        <form method="post">
            <h2 class="text-center">Se connecter</h2>
            <div class="form-group">
                <label>Pseudo ou e-mail</label>
                <input type="text" class="form-control" name="username" required="required"
                    value="<?php echo isset($cookie_username) ? htmlspecialchars($cookie_username) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Mot de passe<a href="#" class="pull-right"></a></label>
                <input type="password" class="form-control" name="password" required="required"
                    value="<?php echo isset($cookie_password) ? htmlspecialchars($cookie_password) : ''; ?>">
            </div>



            <!-- Case à cocher "Se souvenir de moi" -->
            <div class="clearfix">
                <label class="pull-left checkbox-inline">
                    <input type="checkbox" name="remember" value="1">Se souvenir de moi
                </label>
            </div>
            <br />
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block" name="login">Se connecter</button>
            </div>

            <!--<label>Vous n'avez pas de compte?<a href="#" class="pull-right">S'inscrire</a></label>-->
        </form>
    </div>
</body>

<?php require 'footer.php'; ?>

</html>