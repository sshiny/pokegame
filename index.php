<?php

    if (!isset($_SESSION) && empty($_SESSION)) {
        session_start();
    }

    require_once "./functions.php";
    require_once "./models/Pokemon.php";
    require_once "./models/DB.php";

    include "./views/head.php";
    include "./views/header.php";

    if (empty($_GET)) {
        if (empty($_SESSION)) {
            header("Location: ./index.php?action=accueil");
            exit;
        } else {
            header("Location: ./index.php?action=pokemon");
            exit;
        }
    } else if (!empty($_GET['action'])) {
        switch($_GET['action']) {
            case "accueil":
                if (!empty($_SESSION)) {
                    header("Location: ./index.php?action=pokemon");
                    exit;
                } else {
                    include "./views/home.php";
                }
                break;
            case "inscription":
                if (!empty($_SESSION)) {
                    header("Location: ./index.php?action=pokemon");
                    exit;
                } else {
                    if (!empty($_POST)) {
                        if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email']) && !empty($_POST['starter'])) {
                            $result = DB::inscription($_POST);
                            if (!empty($result)) {
                                if (empty(($result['error']))) {
                                    $_SESSION['id'] = $result['id'];
                                    $_SESSION['username'] = $result['username'];
                                    $_SESSION['nb_pieces'] = $result['nb_pieces'];
                                    header("Location: ./index.php?action=pokemon");
                                    exit;
                                } else {
                                    echo notification($result['error'], "is-warning");
                                }
                            } else {
                                echo notification("Une erreur est survenue", "is-danger");
                            }
                        }
                    }
                    $starters = DB::getStarters();
                    include "./views/inscription.php";
                }
                break;
            case "login":
                if (!empty($_SESSION)) {
                    header("Location: ./index.php?action=pokemon");
                    exit;
                } else {
                    if (!empty($_POST)) {
                        if (!empty($_POST['username']) && !empty($_POST['password'])) {
                            $result = DB::login($_POST);
                            if (!empty($result)) {
                                $_SESSION['id'] = $result['id'];
                                $_SESSION['username'] = $result['username'];
                                $_SESSION['nb_pieces'] = $result['nb_pieces'];
                                header("Location: ./index.php?action=pokemon");
                                exit;
                            } else {
                                echo notification("Nom d'utilisateur et/ou mot de passe incorrect(s)", "is-warning");
                            }
                        }
                    }
                    include "./views/login.php";
                }
                break;
            case "pokemon":
                if (empty($_SESSION)) {
                    header("Location: ./index.php?action=accueil");
                    exit;
                } else {
                    $pokemons = DB::getTrainersPokemons($_SESSION['id']);
                    include "./views/pokemon.php";
                }
                break;
            case "detail":
                if (empty($_SESSION)) {
                    header("Location: ./index.php?action=accueil");
                    exit;
                } else {
                    if (!empty($_GET['pokemon'])) {
                        if (!empty($_GET['trainer'])) {
                            $pokemon = DB::getPokemonsDetails($_GET['pokemon'], $_GET['trainer']);
                        } else {
                            $pokemon = DB::getDetails($_GET['pokemon']);
                        }
                        if (!empty($pokemon)) {
                            include "./views/detail.php";
                        } else {
                            echo notification("Une erreur est survenue", "is-danger");
                        }
                    } else {
                        echo notification("Une erreur est survenue", "is-danger");
                    }
                }
                break;
            case "pokedex":
                if (empty($_SESSION)) {
                    header("Location: ./index.php?action=accueil");
                    exit;
                } else {
                    $pokemons = DB::getPokemons();
                    $nbPokemons = DB::countPokemon();
                    $nbEvolutions = DB::countPokemonEvolution();
                    $nbBases = DB::countPokemonEvolution(false);
                    include "./views/pokedex.php";
                }
                break;
            case "disconnect":
                if (session_destroy()) {
                    header("Location: ./index.php?action=accueil");
                    exit;
                } else {
                    echo notification("Une erreur est survenue", "is-danger");
                }
                break;
            case "delete":
                if (empty($_SESSION)) {
                    header("Location: ./index.php?action=accueil");
                    exit;
                } else {
                    if (!empty($_GET['pokemon'])) {
                        if (DB::deletePokemon($_GET['pokemon'])) {
                            header("Location: ./index.php?action=pokedex");
                            exit;
                        } else {
                            echo notification("Une erreur est survenue", "is-danger");
                        }
                    } else {
                        echo notification("Une erreur est survenue", "is-danger");
                    }
                }
                break;
            case "new":
                if (empty($_SESSION)) {
                    header("Location: ./index.php?action=accueil");
                    exit;
                } else {
                    if (!empty($_POST)) {
                        if (!empty($_POST['name']) && !empty($_POST['type1']) && !empty($_POST['courbe'])) {
                            $types = array(DB::getType($_POST['type1']));
                            if (!empty($_POST['type2'])) {
                                array_push($types, DB::getType($_POST['type2']));
                            }
                            $pkmn = new Pokemon(
                                DB::getLastIdPokemon() + 1,
                                ucfirst($_POST['name']),
                                $_POST['courbe'],
                                !empty($_POST['isEvo']),
                                $types
                            );
                            if (DB::addPokemon($pkmn)) {
                                echo notification("Le Pokemon a bien été ajouté", "is-success");
                            } else {
                                echo notification("Une erreur est survenue", "is-danger");
                            }
                        }
                    }
                    $types = DB::getTypes();
                    $typesCourbeNiv = DB::getTypesCourbeNiveau();
                    include "./views/new.php";
                }
                break;
            case "types":
                if (empty($_SESSION)) {
                    header("Location: ./index.php?action=accueil");
                    exit;
                } else {
                    $types = DB::countPokemonPerType();
                    include "./views/types.php";
                }
                break;
            default:
                echo 'Aucune action ne correspond à votre demande';
                break;
        }
    }

    include "./views/footer.php";