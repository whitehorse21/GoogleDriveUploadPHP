<?php

class Config
{

    const GOOGLE_WEB_CLIENT_ID = '320393148274-gp82vd168vk8t4f3f5irq24g56ap4elf.apps.googleusercontent.com';

    const GOOGLE_WEB_CLIENT_SECRET = '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';

    const GOOGLE_ACCESS_SCOPE = 'https://www.googleapis.com/auth/drive';

    const AUTHORIZED_REDIRECT_URI = 'http://localhost/googledrive/callback.php';

    const GOOGLE_OAUTH2_TOKEN_URI = 'https://oauth2.googleapis.com/token';

    const GOOGLE_DRIVE_FILE_UPLOAD_URI = 'https://www.googleapis.com/upload/drive/v3/files';

    const GOOGLE_DRIVE_FILE_META_URI = 'https://www.googleapis.com/drive/v3/files/';
}

?>