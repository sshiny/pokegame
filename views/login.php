<div class="content">
    <section class="hero is-bold is-warning">
        <div class="hero-body">
            <div class="container">
            <h1 class="title">Connexion</h1>
            <h2 class="subtitle">Connectez-vous en remplissant ce formulaire</h2>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="card">
            <form action="./index.php?action=login" method="POST">
                <div class="card-content">
                    <div class="content">
                        <div class="field">
                            <p class="control has-icons-left has-icons-right">
                                <input class="input" type="text" name="username" placeholder="Nom d'utilisateur" required="required">
                                <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
                            </p>
                        </div>
                        <div class="field">
                            <p class="control has-icons-left">
                                <input class="input" type="password" name="password" placeholder="Mot de passe" required="required">
                                <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                            </p>
                        </div>
                        <div class="field">
                            <p class="control"><button class="button is-primary">Connexion</button></p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>