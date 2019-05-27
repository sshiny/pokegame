<div class="content">
    <section class="hero is-bold is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">Pokedex</h1>
                <h2 class="subtitle">Consultez le nombre de Pokemons par type</h2>
            </div>
        </div>
    </section>
    <div class="container">
        <?php if (!empty($types)) { ?>
            <table class="table is-hoverable">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($types as $type) {
                            echo '<tr>';
                            echo '<td>' . ucfirst($type['libelle']) . '</td>';
                            echo '<td>' . $type['nb'] . '</td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        <?php
            } else {
                echo "Pas de types à afficher";
            }
        ?>
    </div>
</div>