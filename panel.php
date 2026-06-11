<?php include "app/app.php"; $page = 3; head($page); ?>
<body>
    
<?php

if($page == 3 && $isLogged == null){
    if (!$appdesktop){
        header("location: index.php");
    } else {
        header("location: index.php");
    }
}

if(empty($_GET['p'])){ 

    $sidepage = 1;

} else {

    $sidepage = $_GET['p'];

}

require "app/env.php";
$yourMail = getEmailUser();
foreach ($yourMail as $mail){
    if ($mail['email'] == $_SESSION['userEmailLog']) {
        if ($mail['isVerif'] == 0) {
            header("location: verif.php");
        }
    }
}

?>

<sidebar><?php sidebar($page); ?></sidebar>


            <main class="content">
            
            <?php 
            // var_dump($_SESSION);
            // var_dump($isLogged);
            content($sidepage) 
            
            ?>
                
            </main>

<script src='https://unpkg.com/@popperjs/core@2'></script><script  src="javascript/sidebar.js"></script>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
    <script src='https://unpkg.com/izitoast/dist/js/iziToast.min.js'></script>
    <script  src="https://tyrolium.fr/javascript/notif.js"></script>

    <?php if (!empty($_GET['true'])) {?>
        <script>
            if(Text != 1){
                iziToast.success({
                    title: 'Succès',
                    position: 'bottomRight',
                    message: '<?php echo $_GET['true']; ?>'
                });
            }
        </script>
    <?php } ?>

    <?php if (!empty($_GET['err'])) { ?>
        <script>
            if(Text != 1){
                iziToast.error({
                    title: 'Erreur',
                    position: 'bottomCenter',
                    message: ' <?php echo $_GET['err']; ?>'
                });
            }
        </script>
    <?php } ?>

    <script>
        function redirectPanel(i) {
            startUrl = "http://"
            startUrls = "https://"

            console.log(i)
            if (!i.startsWith(startUrl) || !i.startsWith(startUrls)) {
                window.location.href = i;
            } else {
                window.open(i, "_blank");
            }
        }
    </script>

<?php sidebarEnd(); ?>

<?php if (!empty($_SESSION['userWebToken'])):
  $relayOrigin = $APP_ENV === 'DEV' ? 'http://192.168.1.81:9001' : 'https://tyrolium.fr';
  $relayUser   = json_encode([
    'id'          => (int)($_SESSION['userIdLog']     ?? 0),
    'email'       => $_SESSION['userEmailLog']        ?? '',
    'username'    => $_SESSION['userNameLog']         ?? '',
    'displayname' => $_SESSION['userDisNameLog']      ?? null,
    'pp'          => $_SESSION['userPpLog']           ?? null,
    'webToken'    => $_SESSION['userWebToken']        ?? '',
  ]);
?>
<script>
(function () {
  var relayOrigin = '<?= $relayOrigin ?>';
  var webToken    = '<?= htmlspecialchars($_SESSION['userWebToken'], ENT_QUOTES) ?>';
  var loginAt     = String(Date.now());
  var loginAtMs   = parseInt(loginAt, 10);
  var userData    = JSON.stringify(<?= $relayUser ?>);
  var frame          = null;
  var tokenConfirmed = false; // devient true dès qu'on voit le token dans le relay

  function checkLogout(data) {
    var logoutAt = data && data['tyrolium-logout'] ? parseInt(data['tyrolium-logout'], 10) : 0;
    var hasToken = !!(data && data['tyrolium-token']);
    console.log('[relay-poll] loginAtMs=' + loginAtMs + ' | logoutAt=' + logoutAt + ' | hasToken=' + hasToken + ' | data=', data);
    if (hasToken) tokenConfirmed = true;
    if (logoutAt > loginAtMs || (tokenConfirmed && !hasToken)) window.location.href = 'session-destroy.php';
  }

  // Iframe principale : écriture des clés au login + détection rapide via storage event
  window.addEventListener('message', function (e) {
    if (e.origin !== relayOrigin || !frame || e.source !== frame.contentWindow) return;
    var d = e.data;
    if (d && d.type === 'tyro-relay-init') {
      frame.contentWindow.postMessage({ type: 'tyro-relay-set', key: 'tyrolium-login-at', value: loginAt }, relayOrigin);
      frame.contentWindow.postMessage({ type: 'tyro-relay-set', key: 'tyrolium-token',    value: webToken  }, relayOrigin);
      frame.contentWindow.postMessage({ type: 'tyro-relay-set', key: 'tyrolium-user',     value: userData  }, relayOrigin);
    }
    if (d && d.type === 'tyro-relay-changed' && d.key === 'tyrolium-logout') {
      window.location.href = 'session-destroy.php';
    }
  });

  // Polling toutes les 5s : iframe temporaire qui lit l'état localStorage courant
  // Ne dépend pas des storage events ni de relay.html
  function pollLogout() {
    var tmp = document.createElement('iframe');
    tmp.src = relayOrigin + '/relay.html';
    tmp.style.cssText = 'display:none;position:fixed;width:0;height:0;border:0';

    function onMsg(e) {
      if (e.origin !== relayOrigin || e.source !== tmp.contentWindow) return;
      if (!e.data || e.data.type !== 'tyro-relay-init') return;
      window.removeEventListener('message', onMsg);
      if (tmp.parentNode) tmp.parentNode.removeChild(tmp);
      checkLogout(e.data.data);
    }
    window.addEventListener('message', onMsg);
    document.body.appendChild(tmp);
  }

  document.addEventListener('DOMContentLoaded', function () {
    frame = document.createElement('iframe');
    frame.src = relayOrigin + '/relay.html';
    frame.setAttribute('aria-hidden', 'true');
    frame.style.cssText = 'display:none;position:fixed;width:0;height:0;border:0';
    document.body.appendChild(frame);
    setInterval(pollLogout, 5000);
  });
})();
</script>
<?php endif; ?>

</body> </html>