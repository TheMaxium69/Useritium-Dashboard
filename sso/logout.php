<?php
session_start();
require_once '../app/env.php';
session_unset();
session_destroy();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Déconnexion…</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { background: #fff; display: flex; align-items: center; justify-content: center; height: 100vh; }
    .spinner {
      width: 72px; height: 72px;
      border-radius: 50%;
      background: conic-gradient(from 0deg, #e63946, #9b5de5, #4361ee, #e63946);
      animation: spin .8s linear infinite;
      -webkit-mask: radial-gradient(circle, transparent 54%, black 55%);
              mask: radial-gradient(circle, transparent 54%, black 55%);
    }
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>
</head>
<body>
  <div class="spinner"></div>
<script>
(function () {
  var SSO_URL  = 'https://sso.tyrolium.fr';
  var UUID_KEY = '_tyro_uuid';
  var uuid = localStorage.getItem(UUID_KEY);
  var done = false;

  function redirect() {
    if (done) return;
    done = true;
    window.location.href = '../index.php';
  }

  if (uuid) {
    fetch(SSO_URL + '/state', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ uuid: uuid, key: 'token', value: '' }).toString(),
    })
    .then(redirect)
    .catch(redirect);
    setTimeout(redirect, 3000);
  } else {
    redirect();
  }
})();
</script>
</body>
</html>
