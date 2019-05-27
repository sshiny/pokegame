<div class="content">
    <section class="hero is-bold is-success">
        <div class="hero-body">
            <div class="container">
            <h1 class="title">Pokemon</h1>
            <h2 class="subtitle">Consultez la liste de vos pokemons</h2>
            </div>
        </div>
    </section>
    <div class="container">
        <?php
            if (!empty($pokemons)) {
                echo '<ul>';
                foreach ($pokemons as $pokemon) {
                    echo '<li>';
                    echo '<a href="./index.php?action=detail&pokemon=' . $pokemon['id'] . '&trainer=' . $_SESSION['id'] . '">' . $pokemon['name'] . '</a>';
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>Vous ne possédez aucun Pokemon</p>';
            }
        ?>
    </div>
</div>