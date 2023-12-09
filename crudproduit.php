<?php
session_start();

// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit();
}
?>
<?php
require_once('header.php');
require_once('connexion.php');
// Check if the "delete" parameter exists in the URL
if (isset($_GET['delete'])) {
    $codeToDelete = $_GET['delete'];

    // Use a prepared statement to delete the product
    $sql = "DELETE FROM produit WHERE code = :codeToDelete";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codeToDelete', $codeToDelete, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Product has been deleted, you can add a success message or redirect
    } else {
        echo "Error deleting the product.";
    }
}
?>
<main>



    <div class="container-xl">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h2>Gestion <b>Produits</b></h2>
                            </br>

                            <button class="btn btn-success" onclick="window.location.href='add.php';">Ajouter
                                Produit</button>
                        </div>
                        <div class="col-sm-4">
                            <div class="search-box">
                                <i class="material-icons">&#xE8B6;</i>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search&hellip;">
                            </div>
                        </div>

                    </div>
                </div>
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Designation <i class="fa fa-sort"></i></th>
                            <th>Categorie</th>
                            <th>Prix Unitaire</th>
                            <th>Quantite de stock <i class="fa fa-sort"></i></th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch data from the database table "produit"
                        $sql = $sql = "SELECT produit.code, produit.designation, categorie.nom as categorie, produit.Qte, produit.prix, produit.image
        FROM produit
        JOIN categorie ON produit.code_categorie = categorie.code";

                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($products as $row) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $row['code']; ?>
                                </td>
                                <td>
                                    <?php echo $row['designation']; ?>
                                </td>
                                <td>
                                    <?php echo $row['categorie']; ?>
                                </td>
                                <td>
                                    <?php echo $row['prix']; ?>
                                </td>
                                <td>
                                    <?php echo $row['Qte']; ?>
                                </td>
                                <!-- <td><img width="80px"
                                        src="data:image/jpg;base64,<?php echo base64_encode($row['image']); ?>"
                                        alt="Product Image"></td>-->
                                <td>
                                    <img src="<?php echo $row['image']; ?>" alt="Product Image"
                                        style="max-width: 100px; max-height: 100px;">
                                </td>

                                <td>
                                    <a href="#" class="view" title="View" data-toggle="tooltip"><i
                                            class="material-icons">&#xE417;</i></a>
                                    <a href="update.php?code=<?= $row['code'] ?>" class="edit" title="Edit"
                                        data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
                                    <a href="crudProduit.php?delete=<?= $row['code'] ?>" class="delete" title="Delete"
                                        data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                                </td>
                            </tr>

                            <?php
                        }
                        ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const searchInput = document.getElementById('searchInput');

                                searchInput.addEventListener('input', function () {
                                    const searchText = searchInput.value.toLowerCase();

                                    $.ajax({
                                        url: 'filter_products.php',
                                        method: 'GET',
                                        data: { designation: searchText },
                                        dataType: 'json',
                                        success: function (response) {
                                            updateTable(response);
                                        },
                                        error: function () {
                                            console.error('Error fetching filtered products.');
                                        }
                                    });
                                });

                                function updateTable(products) {
                                    const tableRows = document.querySelectorAll('.table-striped tbody tr');

                                    tableRows.forEach(row => {
                                        row.style.display = 'none';
                                    });

                                    products.forEach(product => {
                                        const matchingRow = document.querySelector(`.table-striped tbody tr[data-code="${product.code}"]`);

                                        if (matchingRow) {
                                            matchingRow.style.display = '';
                                        }
                                    });
                                }
                            });
                        </script>


                    </tbody>
                </table>
                <div class="clearfix">
                    <div class="hint-text">Showing <b>
                            <?= count($products) ?>
                        </b> out of <b>
                            <?= count($products) ?>
                        </b> entries</div>
                    <ul class="pagination">
                        <li class="page-item disabled"><a href="#"><i class="fa fa-angle-double-left"></i></a></li>
                        <li class="page-item"><a href="#" class="page-link">1</a></li>
                        <li class="page-item"><a href="#" class="page-link">2</a></li>
                        <li class="page-item active"><a href="#" class="page-link">3</a></li>
                        <li class="page-item"><a href="#" class="page-link">4</a></li>
                        <li class="page-item"><a href="#" class="page-link">5</a></li>
                        <li class="page-item"><a href="#" class="page-link"><i class="fa fa-angle-double-right"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once('footer.php');
?>


<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

<style>
    body {
        color: #566787;
        background: #f5f5f5;
        font-family: 'Roboto', sans-serif;
    }

    .table-responsive {
        margin: 30px 0;
    }

    .table-wrapper {
        min-width: 1000px;
        background: #fff;
        padding: 20px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }

    .table-title {
        padding-bottom: 10px;
        margin: 0 0 10px;
        min-width: 100%;
    }

    .table-title h2 {
        margin: 8px 0 0;
        font-size: 22px;
    }

    .search-box {
        position: relative;
        float: right;
    }

    .search-box input {
        height: 34px;
        border-radius: 20px;
        padding-left: 35px;
        border-color: #ddd;
        box-shadow: none;
    }

    .search-box input:focus {
        border-color: #3FBAE4;
    }

    .search-box i {
        color: #a0a5b1;
        position: absolute;
        font-size: 19px;
        top: 8px;
        left: 10px;
    }

    table.table tr th,
    table.table tr td {
        border-color: #e9e9e9;
    }

    table.table-striped tbody tr:nth-of-type(odd) {
        background-color: #fcfcfc;
    }

    table.table-striped.table-hover tbody tr:hover {
        background: #f5f5f5;
    }

    table.table th i {
        font-size: 13px;
        margin: 0 5px;
        cursor: pointer;
    }

    table.table td:last-child {
        width: 130px;
    }

    table.table td a {
        color: #a0a5b1;
        display: inline-block;
        margin: 0 5px;
    }

    table.table td a.view {
        color: #03A9F4;
    }

    table.table td a.edit {
        color: #FFC107;
    }

    table.table td a.delete {
        color: #E34724;
    }

    table.table td i {
        font-size: 19px;
    }

    .pagination {
        float: left;
        /* Changez de "right" à "left" pour déplacer la pagination à gauche */
        margin: 0 0 5px;
    }

    .pagination li a {
        border: none;
        font-size: 95%;
        width: 30px;
        height: 30px;
        color: #999;
        margin: 0 2px;
        line-height: 30px;
        border-radius: 30px !important;
        text-align: center;
        padding: 0;
    }

    .pagination li a:hover {
        color: #666;
    }

    .pagination li.active a {
        background: #03A9F4;
    }

    .pagination li.active a:hover {
        background: #0397d6;
    }

    .pagination li.disabled i {
        color: #ccc;
    }

    .pagination li i {
        font-size: 16px;
        padding-top: 6px;
    }

    .hint-text {
        float: left;
        margin-top: 6px;
        font-size: 95%;
    }
</style>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<script>
    // Cet événement s'active une fois que le DOM est entièrement chargé
    document.addEventListener('DOMContentLoaded', function () {
        // Obtient l'élément HTML pour le champ de recherche
        const searchInput = document.getElementById('searchInput');
        // Récupère toutes les lignes du tableau (toutes les lignes du corps du tableau)
        const tableRows = document.querySelectorAll('.table-striped tbody tr');

        // Ajoute un écouteur d'événements qui surveille les changements dans le champ de recherche
        searchInput.addEventListener('input', function () {
            // Obtient le texte entré dans le champ de recherche, en minuscules pour une comparaison non sensible à la casse
            const searchText = searchInput.value.toLowerCase();

            // Itère sur chaque ligne du tableau
            tableRows.forEach(row => {
                // Obtient le texte dans la colonne 2 (indice 1) de chaque ligne, en minuscules
                const designation = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

                // Vérifie si le texte de la colonne 2 contient le texte de recherche
                if (designation.includes(searchText)) {
                    // Affiche la ligne si elle correspond à la recherche
                    row.style.display = '';
                } else {
                    // Cache la ligne si elle ne correspond pas à la recherche
                    row.style.display = 'none';
                }
            });
        });
    });
</script>