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
</head>
<body>
<script>
(function () {
  var relayOrigin = '<?= $env_relayOrigin ?>';
  var frame = document.createElement('iframe');
  frame.src = relayOrigin + '/relay.html';
  frame.setAttribute('aria-hidden', 'true');
  frame.style.cssText = 'display:none;position:fixed;width:0;height:0;border:0';

  window.addEventListener('message', function (e) {
    if (e.origin !== relayOrigin) return;
    var d = e.data;
    if (d && d.type === 'tyro-relay-init') {
      var now = Date.now().toString();
      frame.contentWindow.postMessage({ type: 'tyro-relay-remove', key: 'tyrolium-token' }, relayOrigin);
      frame.contentWindow.postMessage({ type: 'tyro-relay-remove', key: 'tyrolium-user' }, relayOrigin);
      frame.contentWindow.postMessage({ type: 'tyro-relay-set', key: 'tyrolium-logout', value: now }, relayOrigin);
      setTimeout(function () { window.location.href = '../index.php'; }, 300);
    }
  });

  document.body.appendChild(frame);
})();
</script>
</body>
</html>
