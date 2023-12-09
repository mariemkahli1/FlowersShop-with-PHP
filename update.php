<?php
// Inclure la connexion à la base de données et d'autres configurations nécessaires
require_once('connexion.php');
// Utiliser la mémoire tampon pour éviter les problèmes d'en-tête
ob_start();

// Vérifier si le code du produit est passé en paramètre dans l'URL
if (isset($_GET['code']) && is_numeric($_GET['code'])) {
    $codeProduit = $_GET['code'];

    // Récupérer les informations du produit à partir de la base de données
    $sqlSelect = "SELECT * FROM produit WHERE code = :codeProduit";
    $stmtSelect = $pdo->prepare($sqlSelect);
    $stmtSelect->bindParam(':codeProduit', $codeProduit, PDO::PARAM_INT);
    $stmtSelect->execute();
    $produit = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    // Récupérer les catégories depuis la base de données
    $sqlCategories = "SELECT code, nom FROM categorie";
    $stmtCategories = $pdo->prepare($sqlCategories);
    $stmtCategories->execute();
    $categories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);

    // Vérifier si le produit existe
    if (!$produit) {
        echo "Produit non trouvé.";
        exit();
    }
} else {
    // Rediriger ou afficher un message d'erreur, selon vos besoins
    header('Location: crudProduit.php');
    exit();
}

// Inclure le code de l'en-tête, le cas échéant
require_once('header.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Produit</title>
    <!-- Liens Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <h2>Modifier Produit</h2>

        <!-- Formulaire de modification de produit -->
        <form method="post" action="update.php?code=<?= $codeProduit ?>" enctype="multipart/form-data">
            <!-- Champ de formulaire pour chaque attribut du produit -->
            <div class="form-group">
                <label for="designation">Designation:</label>
                <input type="text" class="form-control" name="designation" value="<?= $produit['designation'] ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="code_categorie">Catégorie:</label>
                <select class="form-control" name="code_categorie" id="code_categorie">
                    <?php
                    foreach ($categories as $categorie) {
                        // Vérifiez si la catégorie actuelle correspond à celle du produit
                        $selected = ($produit['code_categorie'] == $categorie['code']) ? 'selected' : '';
                        echo '<option value="' . $categorie['code'] . '" ' . $selected . '>' . $categorie['nom'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="prix">Prix:</label>
                <input type="number" class="form-control" name="prix" value="<?= $produit['prix'] ?>" required>
            </div>

            <div class="form-group">
                <label for="Qte">Quantité en stock:</label>
                <input type="number" class="form-control" name="Qte" value="<?= $produit['Qte'] ?>" required>
            </div>

            <div class="form-group">
                <label for="image" class="form-label">Image actuelle</label>
                <img src="<?= $produit['image'] ?>" alt="Product Image" style="max-width: 200px;" class="img-fluid">
            </div>

            <div class="form-group">
                <label for="new_image" class="form-label">Nouvelle image</label>
                <input type="file" class="form-control" id="new_image" name="new_image" accept="*/*">
            </div>

            <!-- Ajoutez un champ caché pour stocker le code du produit -->
            <input type="hidden" name="codeProduit" value="<?= $produit['code'] ?>">

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>

    <?php
    // Inclure le code du pied de page, le cas échéant
    require_once('footer.php');
    ?>
</body>

</html>

<?php
// Traitez le formulaire de mise à jour ici 
// Vérifiez si le formulaire de mise à jour a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérez les données du formulaire de mise à jour
    $codeProduit = $_POST['codeProduit'];
    $designation = $_POST['designation'];
    $prix = $_POST['prix'];
    $Qte = $_POST['Qte'];
    $codeCategorie = $_POST['code_categorie'];

    // Gérez le téléchargement de la nouvelle image (si une nouvelle image est fournie)
    if ($_FILES['new_image']['error'] == UPLOAD_ERR_OK) {
        $tempName = $_FILES['new_image']['tmp_name'];
        $originalName = $_FILES['new_image']['name'];
        $newImageFileName = 'images/' . uniqid() . '_' . $originalName;
        move_uploaded_file($tempName, $newImageFileName);
        $imagePath = $newImageFileName;
    } else {
        // Pas de nouvelle image, conservez l'image existante
        $imagePath = $produit['image'];
    }

    // Mettez à jour les données du produit dans la base de données
    $sqlUpdate = "UPDATE produit SET designation = :designation, prix = :prix, Qte = :Qte, image = :image, code_categorie = :codeCategorie WHERE code = :codeProduit";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->bindParam(':codeProduit', $codeProduit, PDO::PARAM_INT);
    $stmtUpdate->bindParam(':designation', $designation, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':prix', $prix, PDO::PARAM_INT);
    $stmtUpdate->bindParam(':Qte', $Qte, PDO::PARAM_INT);
    $stmtUpdate->bindParam(':image', $imagePath, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':codeCategorie', $codeCategorie, PDO::PARAM_INT);

    if ($stmtUpdate->execute()) {
        // Redirigez vers la page crudProduit.php après la mise à jour réussie
        header('Location: crudProduit.php');
        exit();
    } else {
        // Gérez les erreurs de mise à jour dans la base de données
        echo "Erreur de mise à jour dans la base de données.";
    }
}
?>