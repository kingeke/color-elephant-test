<?php

function messageResponse($status, $message = null, $code = 200)
{
    return response()->json([
        'status'  => $status,
        'message' => $message,
    ], $code);
}