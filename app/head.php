<?php

function head($page)
{


    if(empty($_COOKIE['DesktopApp'])){

        //Page
        if ($page == 2) {
            $title = "Useritium - Connexion/Inscription";
        } else if ($page == 3) {
            $title = "Useritium - Dashboard";
        } else if ($page == 4) {
            $title = "Useritium - Verification";
        } else if ($page == 5) {
            $title = "Useritium - Mots de passe";
        } else

        {
            $title = "Useritium - 404";
        }

    } else {

        $title = "Useritium App";

    }

    include "env.php";

    echo '<!doctype html> <html lang="fr"> <head>';
    require_once "composant/meta.phtml";
    echo '<title>' . $title . '</title>';
    require_once "extension.php";
    echo '</head>';
}
