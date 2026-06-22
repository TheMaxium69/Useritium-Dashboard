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
    <script  src="javascript/notif.js"></script>

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

<?php if (!empty($_SESSION['userWebToken'])): ?>
<script>
(function () {
  var SSO_URL  = 'https://sso.tyrolium.fr';
  var UUID_KEY = '_tyro_uuid';
  var webToken = '<?= htmlspecialchars($_SESSION['userWebToken'], ENT_QUOTES) ?>';
  var uuid = localStorage.getItem(UUID_KEY);
  if (!uuid) return;

  function ssoPost(key, value) {
    fetch(SSO_URL + '/state', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ uuid: uuid, key: key, value: value || '' }).toString(),
    }).catch(function () {});
  }

  ssoPost('token', webToken);

  setInterval(function () {
    fetch(SSO_URL + '/state?uuid=' + encodeURIComponent(uuid))
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.status === 'ok' && !data.data.token) {
          window.location.href = 'sso/destroy.php';
        }
      })
      .catch(function () {});
  }, 15000);
})();
</script>
<?php endif; ?>

</body> </html>