<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    .navbar {
        width: 100%;
        background-color: #fff;
        overflow: auto;
    }

    .navbar a {
        float: left;
        padding: 12px;
        color: white;
        text-decoration: none;
        font-size: 17px;
    }

    .navbar a:hover {
        background-color: #000;
    }

    .active {
        background-color: #04AA6D;
    }

    @media screen and (max-width: 500px) {
        .navbar a {
            float: none;
            display: block;
        }
    }

    /* CSS pour rendre le logo circulaire */
    .logo {
        width: 100px;
        height: 100px;
        /* Assurez-vous que la hauteur soit la même que la largeur pour obtenir un cercle */
        margin-right: 10px;
        border-radius: 50%;
        /* Utilisez border-radius pour créer une forme circulaire */
        object-fit: cover;
        /* Pour s'assurer que l'image remplit le cercle sans déformation */
    }

    .logo-text {
        color: firebrick;
    }
</style>

<body>

    <div class="navbar">
        <header class="d-flex align-items-center pb-3 mb-5 border-bottom">
            <a href="/" class="d-flex align-items-center text-body-emphasis text-decoration-none">
                <img class="logo" src="images\logo.jpg" alt="Nom du logo" width="100" height="112">
                <span class="logo-text">Flowers Shop</span>
            </a>
        </header>
        <!-- <a class="active" href="#"><i class="fa fa-fw fa-home"></i> Home</a>
        <a href="#"><i class="fa fa-fw fa-search"></i> Search</a>
        <a href="#"><i class="fa fa-fw fa-envelope"></i> Contact</a>-->
    </div>
</body>

</html>