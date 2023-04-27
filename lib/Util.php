<?php

class Util
{
    function redirect($type, $message)
    {
        $_SESSION['responseMessage'] = array(
            'messageType' => $type,
            'message' => $message
        );
        header("Location: index.php");
        exit();
    }
}
?>