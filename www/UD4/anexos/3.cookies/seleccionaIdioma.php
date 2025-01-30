<?php
$cookieName = 'idioma';
$idioma = isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : "galego";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $idioma = isset($_POST["idioma"]) ? $_POST["idioma"] : 'galego';
    setcookie($cookieName, $idioma, time() + (86400 * 30), "/");
}
?>

<?php include_once('head.php'); ?>
<body>

    <?php include_once('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <main class="col">
                
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Cambio de idioma</h2>
                </div>

                <div class="container justify-content-between">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="mb-2 w-50">
                        <div class="mb-3">
                            <label for="idioma" class="form-label">Idioma</label>
                            <select class="form-select" id="idioma" name="idioma" required>
                                <option value="galego" <?php echo isset($idioma) && $idioma == "galego" ? 'selected' : '' ?>>
                                    Galego
                                </option>
                                <option value="castellano" <?php echo isset($idioma) && $idioma == "castellano" ? 'selected' : '' ?>>
                                    Castellano
                                </option>
                                <option value="english" <?php echo isset($idioma) && $idioma == "english" ? 'selected' : '' ?>>
                                    English
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm">Cambiar</button>
                    </form>
                </div>

                <?php include_once('back.php'); ?>

            </main>
        </div>
    </div>
    
    <?php include_once('footer.php'); ?>
    
</body>
</html>
