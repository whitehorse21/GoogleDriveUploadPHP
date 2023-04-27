<?php
session_start();
ini_set("display_errors",1);
require_once __DIR__ . '/lib/Util.php';
$util = new Util();
if (isset($_GET['code'])) {

    require_once __DIR__ . '/lib/Config.php';

    require_once __DIR__ . '/lib/GoogleDriveUploadService.php';
    $googleDriveUploadService = new GoogleDriveUploadService();

    $googleResponse = $googleDriveUploadService->getAccessToken(Config::GOOGLE_WEB_CLIENT_ID, Config::AUTHORIZED_REDIRECT_URI, Config::GOOGLE_WEB_CLIENT_SECRET, $_GET['code']);
    $accessToken = $googleResponse['access_token'];

    if (! empty($accessToken)) {

        require_once __DIR__ . '/lib/FileModel.php';
        $fileModel = new FileModel();

        $fileId = $_SESSION['fileInsertId'];

        if (! empty($fileId)) {

            $fileResult = $fileModel->getFileRecordById($fileId);
            if (! empty($fileResult)) {
                $fileName = $fileResult[0]['file_base_name'];
                $filePath = 'data/' . $fileName;
                $fileContent = file_get_contents($filePath);
                $fileSize = filesize($filePath);
                $filetype = mime_content_type($filePath);
                $checkFileExits = $googleDriveUploadService->findFiles($accessToken, basename($fileName));
                if ($checkFileExits != 'new') {
                    $bDeleteSuccess = $googleDriveUploadService->deleteFile($accessToken, $checkFileExits);
                }

                try {
                    // Move file to Google Drive via cURL
                    $googleDriveFileId = $googleDriveUploadService->uploadFileToGoogleDrive($accessToken, $fileContent, $filetype, $fileSize);
                    if ($googleDriveFileId) {
                        $fileMeta = array(
                            'name' => basename($fileName)
                        );
        
                        // Add file metadata via API
                        $googleDriveMeta = $googleDriveUploadService->addFileMeta($accessToken, $googleDriveFileId, $fileMeta);
                        if ($googleDriveMeta) {
                            $fileModel->updateFile($googleDriveFileId, $fileId);

                            $_SESSION['fileInsertId'] = '';
                            $util->redirect("success", "https://drive.google.com/open?id=" . $googleDriveMeta['id']);
                        }
                    }
                } catch (Exception $e) {
                    $util->redirect("error", $e->getMessage());
                }
            } else {
                $util->redirect("error", 'Failed to get the file content.');
            }
        } else {
            $util->redirect("error", 'File id not found.');
        }
    } else {
        $util->redirect("error", 'Something went wrong. Access forbidden.');
    }
}
?>