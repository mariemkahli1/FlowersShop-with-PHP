<?php
require_once('connexion.php');

if (isset($_GET['designation'])) {
    $designation = $_GET['designation'];

    // Construisez votre requête SQL pour récupérer les produits avec la désignation spécifiée
    $sql = "SELECT produit.code, produit.designation, categorie.nom as categorie, produit.Qte, produit.prix, produit.image
            FROM produit
            JOIN categorie ON produit.code_categorie = categorie.code
            WHERE produit.designation LIKE :designation";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':designation', '%' . $designation . '%', PDO::PARAM_STR);
    $stmt->execute();

    $filteredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retournez les résultats au format JSON
    echo json_encode($filteredProducts);
}
?>