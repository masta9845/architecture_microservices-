<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Gestion de post-its">
    <title><?= $title ?> | Accueil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="RUELE Amaury">
    <meta name="author" content="CASSACA Kilian">
    <meta name="author" content="RABHI Sofiene">
    <meta name="author" content="Ridouane OUSMANE DOUDOU">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="public/style.css">
</head>

<body>
    <header>
        <nav id="navigation">
            <ul>
                <?php if (isset($_GET['action'])) : ?>
                    <li><a href="index.php"><i class="fa-solid fa-home"></i> Accueil</a></li>
                <?php endif; ?>
                <li>
                    <a href="index.php?action=deconnect" class="logout-button">
                        D&eacute;connexion
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
    <div id="contents">

    </div>
    <?php if (isset($_SESSION['error-msg'])) : ?>
        <div id="error"><span id="error-msg"><?= $_SESSION['error-msg'] ?> !</span></div>
    <?php unset($_SESSION['error-msg']); ?>
    <?php endif; ?>
    <?= $content ?>
    <footer>
        <!-- BEGIN: Footer -->
        <!-- END: Footer -->
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>
</body>

</html>
