<?php
    session_start();
?>

<html>
    <body>
    <?php if(!empty($_SESSION['responseMessage'])){ ?>
        <div>
            <?php echo $_SESSION['responseMessage']['message']; ?>
        </div>
    <?php
        $_SESSION['responseMessage'] = "";
    } else {
        require_once __DIR__ . '/lib/Util.php';
        require_once __DIR__ . '/lib/Config.php';
        require_once __DIR__ . '/lib/FileModel.php';

        $util = new Util();
        $fileModel = new FileModel();

        $path = "data";
        $files = array_diff(scandir($path), array('.', '..'));
        if (count($files) >= 1) {
            $fileName = $files[2];
            $fileInsertId = $fileModel->insertFile($fileName);
            if ($fileInsertId) {
                $_SESSION['fileInsertId'] = $fileInsertId;

                $googleOAuthURI = 'https://accounts.google.com/o/oauth2/auth?scope=' .
                urlencode(Config::GOOGLE_ACCESS_SCOPE) . '&redirect_uri=' .
                Config::AUTHORIZED_REDIRECT_URI . '&response_type=code&client_id=' .
                Config::GOOGLE_WEB_CLIENT_ID . '&access_type=online';

                header("Location: $googleOAuthURI");
                exit();
            } else {
                $util->redirect("error", 'Failed to insert into the database.');
            }
        } else {
            echo "Please copy the file you are going to upload into the data folder";
        }
    }
    ?>
    </body>
</html>
