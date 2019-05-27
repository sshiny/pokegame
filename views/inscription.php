<div class="content">
    <section class="hero is-info">
        <div class="hero-body">
            <div class="container">
            <h1 class="title">Inscription</h1>
            <h2 class="subtitle">Inscrivez-vous en remplissant ce formulaire</h2>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="card">
            <form action="./index.php?action=inscription" method="POST">
                <div class="card-content">
                    <div class="content">
                        <div class="field">
                            <p class="control has-icons-left has-icons-right">
                                <input class="input" type="text" name="username" placeholder="Nom d'utilisateur" required="required">
                                <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
                            </p>
                        </div>
                        <div class="field">
                            <p class="control has-icons-left has-icons-right">
                                <input class="input" type="email" name="email" placeholder="Email" required="required">
                                <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                            </p>
                        </div>
                        <div class="field">
                            <p class="control has-icons-left">
                                <input class="input" type="password" name="password" placeholder="Mot de passe" required="required">
                                <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                            </p>
                        </div>
                        <?php if (!empty($starters)) { ?>
                            <div class="field has-addons">
                                <p class="control">
                                    <a class="button is-static">Starter</a>
                                </p>
                                <p class="control is-expanded">
                                    <span class="select is-fullwidth">
                                        <select name="starter" required="required">
                                            <?php
                                                foreach ($starters as $starter) {
                                                    echo '<option value="' . $starter->getId() . '">' . $starter->getName() . '</option>';
                                                }
                                            ?>
                                        </select>
                                    </span>
                                </p>
                            </div>
                        <?php } ?>
                        <div class="field">
                            <p class="control"><button class="button is-primary">S'inscrire</button></p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>