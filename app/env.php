<?php

//PROD or DEV
$APP_ENV = "DEV";


//Variable Share

$env_urlFile = "./extension/";
$env_urlLogo = "./assets/logo.png";
$env_urlLogoOnglet = "./assets/onglet.png";

if($APP_ENV == "PROD"){
    
    $env_connectUrl = "https://dashboard.useritium.fr/";
    $env_uploadUrl = "https://dashboard.useritium.fr/uploads/";
    $env_urlGamenium = "https://vps209.tyrolium.fr/";

} else if ($APP_ENV == "DEV"){

    $env_connectUrl = "http://localhost/Useritium-Dashboard/";
    $env_uploadUrl = "http://localhost/Useritium-Dashboard/uploads/";
    $env_urlGamenium = "https://localhost:8000/";

}


