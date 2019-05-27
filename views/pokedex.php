<div class="content">
    <section class="hero is-bold is-danger">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">Pokedex</h1>
                <h2 class="subtitle">Consultez la liste des pokemons</h2>
                <div class="field is-grouped is-grouped-multiline">
                    <div class="control">
                        <div class="tags has-addons">
                        <span class="tag is-dark"><?= $nbPokemons ?></span>
                        <span class="tag is-light">Pokemon(s)</span>
                        </div>
                    </div>
                    <div class="control">
                        <div class="tags has-addons">
                        <span class="tag is-dark"><?= $nbBases ?></span>
                        <span class="tag is-light">base(s)</span>
                        </div>
                    </div>
                    <div class="control">
                        <div class="tags has-addons">
                        <span class="tag is-dark"><?= $nbEvolutions ?></span>
                        <span class="tag is-light">évolution(s)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
        <?php if (!empty($pokemons)) { ?>
            <table class="table is-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($pokemons as $pokemon) {
                            echo '<tr>';
                            echo '<td>' . $pokemon->getId() . '</td>';
                            echo '<td><a href="./index.php?action=detail&pokemon=' . $pokemon->getId() . '">' . $pokemon->getName() . '</a></td>';
                            echo '<td>' . displayTypes($pokemon->getTypes()) . '</td>';
                            echo '<td><a href="./index.php?action=delete&pokemon=' . $pokemon->getId() . '" class="button is-warning">Supprimer</a></td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>Votre Pokedex ne contient aucun Pokemon</p>
        <?php } ?>
    </div>
</div>