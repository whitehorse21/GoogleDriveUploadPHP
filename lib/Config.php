<?php

class Config
{

    const GOOGLE_WEB_CLIENT_ID = //Your client id;

    const GOOGLE_WEB_CLIENT_SECRET = //Your Client Secret;

    const GOOGLE_ACCESS_SCOPE = 'https://www.googleapis.com/auth/drive';

    const AUTHORIZED_REDIRECT_URI = 'http://localhost/googledrive/callback.php';

    const GOOGLE_OAUTH2_TOKEN_URI = 'https://oauth2.googleapis.com/token';

    const GOOGLE_DRIVE_FILE_UPLOAD_URI = 'https://www.googleapis.com/upload/drive/v3/files';

    const GOOGLE_DRIVE_FILE_META_URI = 'https://www.googleapis.com/drive/v3/files/';
}

?>
