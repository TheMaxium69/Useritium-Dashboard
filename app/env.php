<?php

//PROD or DEV
$APP_ENV = "DEV";


//Variable Share

$env_urlFile = "./extension/";
$env_gameniumLink = false;

if($APP_ENV == "PROD"){

    $env_urlLogo = "https://useritium.fr/assets/tyrolium-ui/projects/Useritium.png";

    $env_connectUrl = "https://dashboard.useritium.fr/";
    $env_uploadUrl = "https://dashboard.useritium.fr/uploads/";
    $env_urlGamenium = "https://vps216.tyrolium.fr/";

} else if ($APP_ENV == "DEV"){

    $env_urlLogo = "https://useritium.fr/assets/tyrolium-ui/projects/Useritium.png";

    $env_connectUrl = "http://192.168.1.81/Useritium-Dashboard/";
    $env_uploadUrl = "http://192.168.1.81/Useritium-Dashboard/uploads/";
    $env_urlGamenium = "https://localhost:8000/";

}


