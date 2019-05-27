<header>
    <nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a role="button" class="navbar-burger" data-target="navbar" aria-label="menu" aria-expanded="false">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbar" class="navbar-menu">
        <div class="navbar-start">
            <a class="navbar-item" href="./index.php?action=accueil">Accueil</a>
            <?php if (!empty($_SESSION)) { ?>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link" href="./index.php?action=pokedex">Pokedex</a>
                    <div class="navbar-dropdown">
                        <a class="navbar-item" href="./index.php?action=pokedex">Consulter le Pokedex</a>
                        <a class="navbar-item" href="./index.php?action=new">Ajouter un nouveau Pokemon</a>
                        <a class="navbar-item" href="./index.php?action=types">Nombre de Pokemons par type</a>
                    </div>
                </div>
                <a class="navbar-item" href="./index.php?action=pokemon">Pokemon</a>
            <?php } ?>
        </div>

        <div class="navbar-end">
            <div class="navbar-item">
                <?php if (empty($_SESSION)) { ?>
                    <div class="buttons">
                        <a class="button is-primary" href="./index.php?action=inscription"><strong>Inscription</strong></a>
                        <a class="button is-light" href="./index.php?action=login">Connexion</a>
                    </div>
                <?php } else { ?>
                    <div class="navbar-item"><b><?= $_SESSION['username'] ?></b></div>
                    <div class="navbar-item">
                        <div><?= $_SESSION['nb_pieces'] ?></div>
                        <span class="icon is-small is-left"><i class="fas fa-coins"></i></span>
                    </div>
                    <div><a class="button is-light" href="./index.php?action=disconnect">Déconnexion</a></div>
                <?php } ?>
            </div>
        </div>
    </div>
    </nav>
</header>