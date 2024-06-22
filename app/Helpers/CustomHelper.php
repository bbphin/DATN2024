<?php

use App\Models\Option;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

function getOption($name)
{
    $option = Option::where('name', $name)->first();
    return $option ? $option->value : null;
}
function getRandomPassword($length = 8)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    $charactersLength = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, $charactersLength - 1)];
    }
    return $password;
}
function validationErrors($errors): \Illuminate\Http\JsonResponse
{
    return ApiResponse(false,Response::HTTP_UNPROCESSABLE_ENTITY, $errors);
}
function errors($errors): \Illuminate\Http\JsonResponse
{
    return ApiResponse(false,Response::HTTP_BAD_REQUEST, $errors);
}
function success($message,$data=NULL): \Illuminate\Http\JsonResponse
{
    return ApiResponse(true,Response::HTTP_OK, $message,$data);
}

