<div class="content">
    <section class="hero is-bold is-info">
        <div class="hero-body">
            <div class="container">
            <h1 class="title">Ajout</h1>
            <h2 class="subtitle">Ajoutez un nouveau Pokemon en remplissant ce formulaire</h2>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="card">
            <form action="./index.php?action=new" method="POST">
                <div class="card-content">
                    <div class="content">
                        <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label">Nom</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input class="input" type="text" name="name" placeholder="Nom" required="required">
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($types)) { ?>
                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Type 1</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <p class="control is-expanded">
                                            <span class="select is-fullwidth">
                                                <select name="type1" required="required">
                                                    <?php
                                                        foreach ($types as $type) {
                                                            echo '<option value="' . $type['id'] . '">' . ucfirst($type['libelle']) . '</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Type 2</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <p class="control is-expanded">
                                            <span class="select is-fullwidth">
                                                <select name="type2">
                                                    <option value="" disabled="disabled" selected="selected">Aucun</option>
                                                    <?php
                                                        foreach ($types as $type) {
                                                            echo '<option value="' . $type['id'] . '">' . ucfirst($type['libelle']) . '</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php } if (!empty($typesCourbeNiv)) { ?>
                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Type courbe de niveau</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <p class="control is-expanded">
                                            <span class="select is-fullwidth">
                                                <select name="courbe" required="required">
                                                    <?php
                                                        foreach ($typesCourbeNiv as $courbe) {
                                                            echo '<option value="' . $courbe['type_courbe_niveau'] . '">' . $courbe['type_courbe_niveau'] . '</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label">Evolution ?</label>
                            </div>
                            <div class="field-body">
                                <div class="field is-narrow">
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="isEvo" value="1">
                                            Oui
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="isEvo" value="0" checked="checked">
                                            Non
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field is-horizontal">
                            <div class="field-label"></div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <button class="button is-primary">Ajouter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>