<?php
require_once __DIR__ .'/vendor/autoload.php';
// Copy your client id and secret from Google developer console

define('APPLICATION_NAME', 'Drive API PHP Quickstart');
define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret_928537847124-qm8cq61u3j4r3rs88osph5itc6bvn5mm.apps.googleusercontent.com.json');
define('SCOPES', implode(' ', array(
        Google_Service_Drive::DRIVE,
        Google_Service_Drive::DRIVE_APPDATA,
        Google_Service_Drive::DRIVE_APPS_READONLY,
        Google_Service_Drive::DRIVE_FILE,
        Google_Service_Plus::USERINFO_EMAIL,
        'https://spreadsheets.google.com/feeds'
    )
));
define('REDIRECT_URL', 'http://localhost/php-google-oauth');

// -----------------------------------------------------------------------------
// DO NOT EDIT BELOW THIS LINE
// -----------------------------------------------------------------------------



session_start();

$client = new Google_Client();
$client->setAuthConfigFile(CLIENT_SECRET_PATH);
$client->addScope(SCOPES);
$client->setAccessType('offline');
$client->setRedirectUri(REDIRECT_URL);


if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['token'] = $client->getAccessToken();
    exit;
}

if (isset ($_SESSION['token'])){
    $client->setAccessToken($_SESSION['token']);

    if ($client->isAccessTokenExpired()) {
        $client->refreshToken($client->getRefreshToken());
        $_SESSION['token'] = $client->getAccessToken();
    }
}
else{
    print '<a href="' . $client->createAuthUrl() . '">Authenticate</a>';
}

print_r(json_decode($client->getAccessToken(), true));