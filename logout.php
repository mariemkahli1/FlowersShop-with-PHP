<?php
session_start(); // Démarrez la session

// Nettoyez la session en supprimant les variables de session
session_unset();

// Détruisez la session
session_destroy();

// Redirigez l'utilisateur vers la page de connexion avec un paramètre pour indiquer la déconnexion
header('Location: login.php?logout=1');
exit();
?>