<?php

class DB {
    private static $pdo = null;

    public static function getPDO() {
        try {
            if (!isset($pdo)) {
                $conn = "mysql:host=localhost;dbname=pokegame";
                $user = "pokegame";
                $pass = "pokegame";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ];
                $pdo = new PDO($conn, $user, $pass, $options);
            }
            return $pdo;
        } catch (\PDOException $e) {
            return null;
        }
    }

    public static function login($formData) {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            $query = "SELECT id, username, password, nb_pieces FROM trainer WHERE username = ?";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(1, $formData['username'], PDO::PARAM_STR);
            if ($stmt->execute()) {
                $result = $stmt->fetch();
                return $result;
                if (!empty($result)) {
                    if (password_verify($formData['password'], $result['password'])) {
                        return $result;
                    }
                }
            }
        }
        return null;
    }

    public static function inscription($formData) {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            try {
                $pdo->beginTransaction();
                /* Check account does not already exist */
                $query = "SELECT * FROM trainer WHERE username = :username OR email = :email;";
                $stmt = $pdo->prepare($query);
                $stmt->bindValue(":username", $formData['username'], PDO::PARAM_STR);
                $stmt->bindValue(":email", $formData['email'], PDO::PARAM_STR);
                $stmt->execute();
                if ($stmt->rowCount() == 0) {
                    /* Add trainer */
                    $query = "INSERT INTO trainer(username, password, email, is_active, nb_pieces, starter_id)
                        VALUES (:username, :password, :email, :isActive, :nbPieces, :starter);";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindValue(":username", $formData['username'], PDO::PARAM_STR);
                    $stmt->bindValue(":password", password_hash($formData['password'], PASSWORD_DEFAULT), PDO::PARAM_STR);
                    $stmt->bindValue(":email", $formData['email'], PDO::PARAM_STR);
                    $stmt->bindValue(":isActive", true, PDO::PARAM_BOOL);
                    $stmt->bindValue(":nbPieces", 5000, PDO::PARAM_INT);
                    $stmt->bindValue(":starter", $formData['starter'], PDO::PARAM_INT);
                    $stmt->execute();
                    /* Get inserted trainer's id */
                    $id = $pdo->query("SELECT id FROM trainer ORDER BY id DESC LIMIT 1")->fetchColumn();
                    /* Add pokemon */
                    $query = "INSERT INTO pokemon(ref_pokemon_id, sexe, xp, niveau, a_vendre, prix, date_dernier_entrainement, dresseur_id)
                        VALUES (:pokemon, :sexe, :xp, :niveau, :aVendre, :prix, :dateDernierTraining, :dresseur);";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindValue(":pokemon", $formData['starter'], PDO::PARAM_INT);
                    $stmt->bindValue(":sexe", (rand(0, 1) == 0) ? "M" : "F", PDO::PARAM_STR);
                    $stmt->bindValue(":xp", 0, PDO::PARAM_INT);
                    $stmt->bindValue(":niveau", 5, PDO::PARAM_INT);
                    $stmt->bindValue(":aVendre", false, PDO::PARAM_BOOL);
                    $stmt->bindValue(":prix", 0, PDO::PARAM_INT);
                    $stmt->bindValue(":dateDernierTraining", 0, PDO::PARAM_INT);
                    $stmt->bindValue(":dresseur", $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $pdo->commit();
                    return array(
                        "id" => $id,
                        "username" => $formData['username'],
                        "nb_pieces" => 5000
                    );
                }
                return array(
                    "error" => "Un compte est déjà associé à un autre dresseur"
                );
            } catch (\Exception $e) {
                $pdo->rollback();
            }
        }
        return null;
    }

    public static function getStarters() {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            $stmt = $pdo->prepare("SELECT * FROM (
                    SELECT ref_pokemon.id, ref_pokemon.nom, ref_pokemon.type_courbe_niveau, ref_pokemon.evolution, type1.libelle AS type_1, type2.libelle AS type_2
                    FROM ref_pokemon
                    INNER JOIN ref_elementary_type AS type1 ON type1.id = ref_pokemon.type_1
                    INNER JOIN ref_elementary_type AS type2 ON type2.id = ref_pokemon.type_2
                    WHERE ref_pokemon.starter = :starter
                    UNION ALL
                    SELECT ref_pokemon.id, ref_pokemon.nom, ref_pokemon.type_courbe_niveau, ref_pokemon.evolution, type1.libelle AS type_1, NULL AS type_2
                    FROM ref_pokemon
                    INNER JOIN ref_elementary_type AS type1 ON type1.id = ref_pokemon.type_1
                    WHERE ref_pokemon.type_2 = :type2
                    AND ref_pokemon.starter = :starter
                ) AS a
                ORDER BY id;");
            $stmt->bindValue(":starter", 1, PDO::PARAM_INT);
            $stmt->bindValue(":type2", 0, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll();
            $arr = array();
            foreach ($result as $row) {
                array_push($arr, new Pokemon(
                    $row['id'],
                    $row['nom'],
                    $row['type_courbe_niveau'],
                    boolval($row['evolution']),
                    getTypesAsArray($row['type_1'], $row['type_2'])
                ));
            }
            return $arr;
        }
        return null;
    }

    public static function getTrainersPokemons($trainer) {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            $stmt = $pdo->prepare("SELECT pokemon.id, ref_pokemon.nom
                FROM ref_pokemon
                INNER JOIN pokemon ON pokemon.ref_pokemon_id = ref_pokemon.id
                WHERE pokemon.dresseur_id = ?
                ORDER BY pokemon.id;");
            $stmt->bindValue(1, $trainer, PDO::PARAM_INT);
            $stmt->execute();
            $arr = array();
            $result = $stmt->fetchAll();
            if (!empty($result)) {
                foreach ($result as $row) {
                    array_push($arr, array(
                        "id" => $row['id'],
                        "name" => $row['nom']
                    ));
                }
                return $arr;
            }
        }
        return null;
    }

    public static function getDetails($pokemon) {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            $stmt = $pdo->prepare("SELECT *
                FROM (
                    SELECT ref_pokemon.id, ref_pokemon.nom, ref_pokemon.type_courbe_niveau, ref_pokemon.evolution, type1.libelle AS type_1, type2.libelle AS type_2
                    FROM ref_pokemon
                    INNER JOIN ref_elementary_type AS type1 ON type1.id = ref_pokemon.type_1
                    INNER JOIN ref_elementary_type AS type2 ON type2.id = ref_pokemon.type_2
                    UNION ALL
                    SELECT ref_pokemon.id, ref_pokemon.nom, ref_pokemon.type_courbe_niveau, ref_pokemon.evolution, type1.libelle AS type_1, NULL AS type_2
                    FROM ref_pokemon
                    INNER JOIN ref_elementary_type AS type1 ON type1.id = ref_pokemon.type_1
                    WHERE ref_pokemon.type_2 = :type2
                ) AS a
                WHERE a.id = :idPokemon;");
            $stmt->bindValue(":type2", 0, PDO::PARAM_INT);
            $stmt->bindValue(":idPokemon", $pokemon, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();
            if (!empty($result)) {
                return new Pokemon(
                    $result['id'],
                    $result['nom'],
                    $result['type_courbe_niveau'],
                    boolval($result['evolution']),
                    getTypesAsArray($result['type_1'], $result['type_2'])
                );
            }
        }
        return null;
    }

    public static function getPokemonsDetails($pokemon, $trainer) {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            $stmt = $pdo->prepare("SELECT a.id, a.nom, a.type_courbe_niveau, a.evolution, a.type_1, a.type_2, pokemon.id AS id_pokemon, pokemon.sexe, pokemon.xp, pokemon.niveau, pokemon.date_dernier_entrainement, trainer.username AS do
                FROM (
                    SELECT ref_pokemon.id, ref_pokemon.nom, ref_pokemon.type_courbe_niveau, ref_pokemon.evolution, type1.libelle AS type_1, type2.libelle AS type_2
                    FROM ref_pokemon
                    INNER JOIN ref_elementary_type AS type1 ON type1.id = ref_pokemon.type_1
                    INNER JOIN ref_elementary_type AS type2 ON type2.id = ref_pokemon.type_2
                    UNION ALL
                    SELECT ref_pokemon.id, ref_pokemon.nom, ref_pokemon.type_courbe_niveau, ref_pokemon.evolution, type1.libelle AS type_1, NULL AS type_2
                    FROM ref_pokemon
                    INNER JOIN ref_elementary_type AS type1 ON type1.id = ref_pokemon.type_1
                    WHERE ref_pokemon.type_2 = :type2
                ) AS a
                INNER JOIN pokemon ON pokemon.ref_pokemon_id = a.id
                INNER JOIN trainer ON trainer.id = pokemon.dresseur_id
                WHERE pokemon.id = :idPokemon
                AND trainer.id = :trainer
                ORDER BY a.id;");
            $stmt->bindValue(":type2", 0, PDO::PARAM_INT);
            $stmt->bindValue(":idPokemon", $pokemon, PDO::PARAM_INT);
            $stmt->bindValue(":trainer", $trainer, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();
            if (!empty($result)) {
                return array(
                    "pokemon" => new Pokemon(
                        $result['id'],
                        $result['nom'],
                        $result['type_courbe_niveau'],
                        boolval($result['evolution']),
                        getTypesAsArray($result['type_1'], $result['type_2'])
                    ),
                    "id" => $result['id_pokemon'],
                    "sexe" => $result['sexe'],
                    "do" => $result['do'],
                    "xp" => $result['xp'],
                    "niveau" => $result['niveau'],
                    "training" => $result['date_dernier_entrainement']
                );
            }
        }
        return null;
    }

    public static function getPokemons() {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            $stmt = $pdo->prepare("SELECT *
                FROM (
                    SELECT ref_pokemon.id, ref_pokemon.nom, ref_pokemon.type_courbe_niveau, ref_pokemon.evolution, type1.libelle AS type_1, type2.libelle AS type_2
                    FROM ref_pokemon
                    INNER JOIN ref_elementary_type AS type1 ON type1.id = ref_pokemon.type_1
                    INNER JOIN ref_elementary_type AS type2 ON type2.id = ref_pokemon.type_2
                    UNION ALL
                    SELECT ref_pokemon.id, ref_pokemon.nom, ref_pokemon.type_courbe_niveau, ref_pokemon.evolution, type1.libelle AS type_1, NULL AS type_2
                    FROM ref_pokemon
                    INNER JOIN ref_elementary_type AS type1 ON type1.id = ref_pokemon.type_1
                    WHERE ref_pokemon.type_2 = ?
                ) AS a
                ORDER BY a.id;");
            $stmt->bindValue(1, 0, PDO::PARAM_INT);
            $stmt->execute();
            $arr = array();
            while ($row = $stmt->fetch()) {
                array_push($arr, new Pokemon(
                    $row['id'],
                    $row['nom'],
                    $row['type_courbe_niveau'],
                    boolval($row['evolution']),
                    getTypesAsArray($row['type_1'], $row['type_2'])
                ));
            }
            return $arr;
        }
        return null;
    }

    public static function deletePokemon($id) {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            try {
                $pdo->beginTransaction();
                $query = "DELETE FROM pokemon WHERE ref_pokemon_id = ?;";
                $stmt = $pdo->prepare($query);
                $stmt->bindValue(1, $id, PDO::PARAM_INT);
                $stmt->execute();
                $query = "DELETE FROM ref_pokemon WHERE id = ?;";
                $stmt = $pdo->prepare($query);
                $stmt->bindValue(1, $id, PDO::PARAM_INT);
                $stmt->execute();
                $pdo->commit();
                return true;
            } catch (\Exception $e) {
                $pdo->rollback();
                return false;
            }
        }
        return false;
    }

    public static function countPokemon() {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            $count = $pdo->query("SELECT COUNT(*) FROM ref_pokemon;")->fetchColumn();
            return $count;
        }
        return -1;
    }

    public static function countPokemonEvolution($fetchEvolution=true) {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            $query = "SELECT COUNT(*)
                FROM ref_pokemon
                WHERE evolution = ?;";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(1, $fetchEvolution, PDO::PARAM_BOOL);
            if ($stmt->execute()) {
                return $stmt->fetchColumn();
            }
        }
        return -1;
    }

    public static function getType($type) {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            $query = "SELECT libelle FROM ref_elementary_type WHERE id = ?;";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(1, $type, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch()['libelle'];
        }
        return null;
    }

    public static function getTypes() {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            $query = "SELECT * FROM ref_elementary_type ORDER BY libelle;";
            return $pdo->query($query)->fetchAll();
        }
        return null;
    }

    public static function getTypesCourbeNiveau() {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            $query = "SELECT DISTINCT type_courbe_niveau FROM ref_pokemon ORDER BY type_courbe_niveau;";
            return $pdo->query($query)->fetchAll();
        }
        return null;
    }

    public static function getLastIdPokemon() {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            $query = "SELECT id FROM ref_pokemon ORDER BY id DESC LIMIT 1;";
            return (int)$pdo->query($query)->fetch()['id'];
        }
        return -1;
    }

    public static function addPokemon($pokemon) {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            $query = "INSERT INTO ref_pokemon (nom, evolution, starter, type_courbe_niveau, type_1, type_2)
                VALUES (
                    :nom,
                    :evolution,
                    :starter,
                    :typeCourbeNiveau,
                    (SELECT id FROM ref_elementary_type WHERE libelle = :type1),";
            if (!empty($pokemon->getTypes()[1])) {
                $query .= "(SELECT id FROM ref_elementary_type WHERE libelle = :type2)";
            } else {
                $query .= ":type2";
            }
            $query .= ");";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(":nom", $pokemon->getName(), PDO::PARAM_STR);
            $stmt->bindValue(":evolution", $pokemon->getIsEvolution(), PDO::PARAM_BOOL);
            $stmt->bindValue(":starter", false, PDO::PARAM_BOOL);
            $stmt->bindValue(":typeCourbeNiveau", $pokemon->getXp(), PDO::PARAM_STR);
            $stmt->bindValue(":type1", $pokemon->getTypes()[0], PDO::PARAM_STR);
            if (!empty($pokemon->getTypes()[1])) {
                $stmt->bindValue(":type2", $pokemon->getTypes()[1], PDO::PARAM_STR);
            } else {
                $stmt->bindValue(":type2", 0, PDO::PARAM_INT);
            }
            return ($stmt->execute() && $stmt->rowCount() == 1);
        }
        return false;
    }

    public static function countPokemonPerType() {
        $pdo = DB::getPDO();
        if (!empty($pdo)) {
            $query = "SELECT a.libelle, count(*) AS nb
                FROM (
                    SELECT type.id, type.libelle
                    FROM ref_elementary_type AS type
                    INNER JOIN ref_pokemon ON ref_pokemon.type_1 = type.id
                    UNION ALL
                    SELECT type.id, type.libelle
                    FROM ref_elementary_type AS type
                    INNER JOIN ref_pokemon ON ref_pokemon.type_2 = type.id
                ) AS a
                GROUP BY a.libelle
                ORDER BY a.libelle;";
            return $pdo->query($query)->fetchAll();
        }
        return null;
    }
}