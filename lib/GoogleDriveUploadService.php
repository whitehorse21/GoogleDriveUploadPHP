<?php
require_once __DIR__ . '/Config.php';

class GoogleDriveUploadService
{

    public function getAccessToken($clientId, $authorizedRedirectURI, $clientSecret, $code)
    {
        $curlPost = 'client_id=' . $clientId . '&redirect_uri=' . $authorizedRedirectURI . '&client_secret=' . $clientSecret . '&code=' . $code . '&grant_type=authorization_code';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, Config::GOOGLE_OAUTH2_TOKEN_URI);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $curlResponse = json_decode(curl_exec($curl), true);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($responseCode != 200) {
            $errorMessage = 'Problem in getting access token';
            if (curl_errno($curl)) {
                $errorMessage = curl_error($curl);
            }
            throw new Exception('Error: ' . $responseCode . ': ' . $errorMessage);
        }

        return $curlResponse;
    }

    public function findFiles($accessToken, $fileName)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, Config::GOOGLE_DRIVE_FILE_META_URI . "?q=name%20contains%20'".$fileName."'");
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ));

        $curlResponse = json_decode(curl_exec($curl), true);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($responseCode != 200) {
            $errorMessage = 'Failed to upload file to drive';
            if (curl_errno($curl)) {
                $errorMessage = curl_error($curl);
            }
            throw new Exception('Error ' . $responseCode . ': ' . $errorMessage);
        }
        curl_close($curl);

        $foundFileID = "new";
        $files = $curlResponse['files'];
        
        if (count($files) > 0) {
            foreach ($files as $eachFile) {
                if ($eachFile['name'] == $fileName) {
                    $foundFileID = $eachFile['id'];
                    break;
                }
            }
        }

        return $foundFileID;
    }

    public function uploadFileToGoogleDrive($accessToken, $fileContent, $filetype, $fileSize)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, Config::GOOGLE_DRIVE_FILE_UPLOAD_URI . '?uploadType=media');
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fileContent);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: ' . $filetype,
            'Content-Length: ' . $fileSize,
            'Authorization: Bearer ' . $accessToken
        ));

        $curlResponse = json_decode(curl_exec($curl), true);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($responseCode != 200) {
            $errorMessage = 'Failed to upload file to drive';
            if (curl_errno($curl)) {
                $errorMessage = curl_error($curl);
            }
            throw new Exception('Error ' . $responseCode . ': ' . $errorMessage);
        }
        curl_close($curl);
        return $curlResponse['id'];
    }

    public function addFileMeta($accessToken, $googleDriveFileId, $fileMeta)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, Config::GOOGLE_DRIVE_FILE_META_URI . $googleDriveFileId);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fileMeta));
        $curlResponse = json_decode(curl_exec($curl), true);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($responseCode != 200) {
            $errorMessage = 'Failed to add file metadata';
            if (curl_errno($curl)) {
                $errorMessage = curl_error($curl);
            }
            throw new Exception('Error ' . $responseCode . ': ' . $errorMessage);
        }
        curl_close($curl);

        return $curlResponse;
    }

    public function deleteFile($accessToken, $googleDriveFileId)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, Config::GOOGLE_DRIVE_FILE_META_URI . $googleDriveFileId);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $curlResponse = json_decode(curl_exec($curl), true);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
    }    
}
?>