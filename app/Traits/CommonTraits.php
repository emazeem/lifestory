<?php
namespace App\Traits;
trait CommonTraits {


    function sendSuccess($message, $data = '') {
        return response()->json(['message' => $message, 'data' => $data,],200);
    }
    function sendError($error_message, $code = 400, $data = NULL) {
        return response()->json(['message' => $error_message, 'data' => $data],$code);
    }

}
