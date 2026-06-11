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
  var relayOrigin = '<?= $env_relayOrigin ?>';
  var done = false;

  function redirect() {
    if (done) return;
    done = true;
    window.location.href = '../index.php';
  }

  var frame = document.createElement('iframe');
  frame.src = relayOrigin + '/relay.html';
  frame.setAttribute('aria-hidden', 'true');
  frame.style.cssText = 'display:none;position:fixed;width:0;height:0;border:0';

  // Fallback : si le relay ne répond pas dans les 5s, on redirige quand même
  var fallback = setTimeout(redirect, 5000);

  window.addEventListener('message', function (e) {
    if (e.origin !== relayOrigin) return;
    var d = e.data;
    if (d && d.type === 'tyro-relay-init') {
      clearTimeout(fallback);
      var now = Date.now().toString();
      frame.contentWindow.postMessage({ type: 'tyro-relay-remove', key: 'tyrolium-token' }, relayOrigin);
      frame.contentWindow.postMessage({ type: 'tyro-relay-remove', key: 'tyrolium-user' }, relayOrigin);
      frame.contentWindow.postMessage({ type: 'tyro-relay-set', key: 'tyrolium-logout', value: now }, relayOrigin);
      setTimeout(redirect, 300);
    }
  });

  // Si l'iframe échoue à charger (CORS, serveur down), on redirige immédiatement
  frame.onerror = function () {
    clearTimeout(fallback);
    redirect();
  };

  document.body.appendChild(frame);
})();
</script>
</body>
</html>
