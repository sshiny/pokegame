<div class="content">
    <section class="hero is-bold is-light">
        <div class="hero-body">
            <div class="container">
            <h1 class="title">Détail</h1>
            <h2 class="subtitle">Apprenez en plus sur le pokemon sélectionné</h2>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="card">
        <div class="card-content">
            <?php
                if (!empty($_GET['trainer'])) {
                    echo '<p>Nom : ' . $pokemon['pokemon']->getName() . '</p>';
                    echo '<p>Sexe : <span class="icon is-small is-left">';
                    if ($pokemon['sexe'] == "M") {
                        echo '<i class="fas fa-mars"></i>';
                    } else { // $pokemon['sexe'] == "F"
                        echo '<i class="fas fa-venus"></i>';
                    }
                    echo '</span></p>';
                    echo '<p>Type : ' . displayTypes($pokemon['pokemon']->getTypes()) . '</p>';
                    echo '<p>Dresseur origine : ' . $pokemon['do'] . '</p>';
                    echo '<p>Niveau : ' . $pokemon['niveau'] . '</p>';
                    echo '<p>Points exp : ' . $pokemon['xp'] . '</p>';
                    if ($pokemon['training'] != 0) {
                        echo '<p>Dernier entraînement : ' . date("d/m/Y", $pokemon['training']) . '</p>';
                    }
                } else {
                    echo '<p>ID : ' . $pokemon->getID() . '</p>';
                    echo '<p>Nom : ' . $pokemon->getName() . '</p>';
                    echo '<p>Type : ' . displayTypes($pokemon->getTypes()) . '</p>';
                    if ($pokemon->getIsEvolution()) {
                        echo '<p>est une évolution</p>';
                    } else {
                        echo '<p>est un Pokemon de base</p>';
                    }
                }
            ?>
        </div>
        </div>
    </div>
</div>