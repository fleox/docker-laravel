<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon thème Bootstrap</title>
    <!-- Liens vers les fichiers CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lien vers votre fichier CSS personnalisé -->
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    
    <!-- Contenu principal -->
    <div class="container">
        @yield('body')
    </div>

    <!-- Liens vers les fichiers JavaScript de Bootstrap (Bootstrap bundle inclut Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Lien vers jQuery (requis pour certaines fonctionnalités de Bootstrap) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Vos scripts JavaScript personnalisés -->
    <script src="js/scripts.js"></script>
</body>
</html>
