<?php
session_start();
header('Content-Type: application/json');

if (!empty($_SESSION['userNameLog'])) {
    echo json_encode(['ok' => false, 'reason' => 'already_logged']);
    exit;
}

if (empty($_POST['token'])) {
    echo json_encode(['ok' => false, 'reason' => 'no_token']);
    exit;
}

require_once '../api/private/db.php';
$conn = mysqli_connect($host, $userDB, $passDB, $Database);

if (!$conn) {
    echo json_encode(['ok' => false, 'reason' => 'db_error']);
    exit;
}

$token = $_POST['token'];
$stmt = mysqli_prepare($conn, "SELECT id, username, displayname, email, role, pp FROM users WHERE webtoken = ?");
mysqli_stmt_bind_param($stmt, 's', $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
    $_SESSION['userIdLog']      = $user['id'];
    $_SESSION['userNameLog']    = $user['username'];
    $_SESSION['userDisNameLog'] = $user['displayname'];
    $_SESSION['userEmailLog']   = $user['email'];
    $_SESSION['userRoleLog']    = $user['role'];
    $_SESSION['userPpLog']      = $user['pp'];
    $_SESSION['userWebToken']   = $token;
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'reason' => 'invalid_token']);
}

mysqli_close($conn);
