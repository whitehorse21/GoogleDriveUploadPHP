<?php
require_once __DIR__ . '/DataSource.php';

class FileModel extends DataSource
{

    function insertFile($fileBaseName)
    {
        $query = "INSERT INTO google_drive_upload_response_log (file_base_name, create_at) VALUES (?, NOW())";
        $paramType = 's';
        $paramValue = array(
            $fileBaseName
        );
        $insertId = $this->insert($query, $paramType, $paramValue);
        return $insertId;
    }

    function getFileRecordById($fileId)
    {
        $query = "SELECT * FROM google_drive_upload_response_log WHERE id = ?";
        $paramType = 'i';
        $paramValue = array(
            $fileId
        );
        $result = $this->select($query, $paramType, $paramValue);
        return $result;
    }

    function updateFile($googleFileId, $fileId)
    {
        $query = "UPDATE google_drive_upload_response_log SET google_file_id=? WHERE id=?";
        $paramType = 'si';
        $paramValue = array(
            $googleFileId,
            $fileId
        );
        $this->update($query, $paramType, $paramValue);
    }
}
?>