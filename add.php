<?php
// Database connection inclure
require_once('connexion.php');


// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $designation = $_POST['designation'];
    $code_categorie = $_POST['code_categorie'];
    $prix = $_POST['prix'];
    $Qte = $_POST['Qte'];

    // Gérer le téléchargement de l'image
    $imageFileName = ''; // Le nom du fichier image dans le dossier images

    if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $tempName = $_FILES['image']['tmp_name'];
        $originalName = $_FILES['image']['name'];
        $imageFileName = 'images/' . uniqid() . '_' . $originalName;

        // Déplacer le fichier image téléchargé vers le dossier images
        move_uploaded_file($tempName, $imageFileName);
    }

    // Insérer les données dans la base de données
    $sql = "INSERT INTO produit (designation, code_categorie, prix, Qte, image) VALUES (:designation, :code_categorie, :prix, :Qte, :image)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':designation', $designation, PDO::PARAM_STR);
    $stmt->bindParam(':code_categorie', $code_categorie, PDO::PARAM_INT);
    $stmt->bindParam(':prix', $prix, PDO::PARAM_INT);
    $stmt->bindParam(':Qte', $Qte, PDO::PARAM_INT);
    $stmt->bindParam(':image', $imageFileName, PDO::PARAM_STR);



    if ($stmt->execute()) {
        // Rediriger vers la page crudProduit.php après l'ajout réussi
        header('Location: crudProduit.php');
        exit();
    } else {
        // Gérer les erreurs d'insertion dans la base de données
        echo "Erreur d'insertion dans la base de données.";
    }
}

// Récupérer les catégories depuis la base de données
$sqlCategories = "SELECT code, nom FROM categorie";
$stmtCategories = $pdo->prepare($sqlCategories);
$stmtCategories->execute();
$categories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Liens Bootstrap -->
    <!-- Liens Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <title>Ajouter Produit</title>
</head>

<body>
    <?php require('header.php'); ?>
    <div class="container mt-5">
        <h2 class="mb-4">Ajouter Produit</h2>

        <!-- Formulaire d'ajout de produit -->
        <form method="post" enctype="multipart/form-data">

            <!-- Champ de formulaire pour chaque attribut du produit -->
            <div class="form-group">
                <label for="designation">Designation:</label>
                <input type="text" class="form-control" name="designation" required>
            </div>

            <div class="form-group">
                <label for="code_categorie">Catégorie:</label>
                <select class="form-control" name="code_categorie" id="code_categorie">
                    <?php
                    foreach ($categories as $categorie) {
                        echo '<option value="' . $categorie['code'] . '">' . $categorie['nom'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="prix">Prix:</label>
                <input type="number" class="form-control" name="prix" required>
            </div>

            <div class="form-group">
                <label for="Qte">Quantité en stock:</label>
                <input type="number" class="form-control" name="Qte" required>
            </div>

            <div class="form-group">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required />
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>

    <?php require('footer.php'); ?>
</body>

</html>