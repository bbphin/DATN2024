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

function getStatusOrderShip($text, $type = "default")
{
    $status = '';

    // Kiểm tra nếu $type không phải là "default", thêm chữ "Đơn hàng"
    $title = ($type == "default") ? "Đơn hàng " : "";

    switch ($text) {
        case 'ORDERPLACE':
            $status = "<span class='badge rounded-pill bg-primary-light text-primary fw-medium p-0' style='text-align:left;font-size:16px'>
                            $title Đã được tạo
                        </span>";
            break;
        case 'PACKED':
            $status = "<span class='badge rounded-pill bg-primary-light text-primary fw-medium p-0' style='text-align:left;font-size:16px'>
                            $title Đã nhận và đang đóng gói
                        </span>";
            break;
        case 'SHIPPED':
            $status = "<span class='badge rounded-pill bg-primary-light text-primary fw-medium p-0' style='text-align:left;font-size:16px'>
                            $title Đã được chuyển giao
                        </span>";
            break;
        case 'INTRANSIT':
            $status = "<span class='badge rounded-pill bg-primary-light text-primary fw-medium p-0' style='text-align:left;font-size:16px'>
                            $title Đang trên đường đến điểm đến
                        </span>";
            break;
        case 'OUTFORDELIVERY':
            $status = "<span class='badge rounded-pill bg-primary-light text-primary fw-medium p-0' style='text-align:left;font-size:16px'>
                            $title Đang được giao cho người nhận
                        </span>";
            break;
        case 'DELIVERED':
            $status = "<span class='badge rounded-pill bg-primary-light text-primary fw-medium p-0' style='text-align:left;font-size:16px'>
                            $title Đã được giao thành công
                        </span>";
            break;
        case 'DELAYED':
            $status = "<span class='badge rounded-pill bg-primary-light text-primary fw-medium p-0' style='text-align:left;font-size:16px'>
                            $title Gặp trễ hẹn trong quá trình vận chuyển
                        </span>";
            break;
        case 'EXCEPTION':
            $status = "<span class='badge rounded-pill bg-primary-light text-primary fw-medium p-0' style='text-align:left;font-size:16px'>
                            $title Gặp vấn đề hoặc ngoại lệ trong quá trình vận chuyển
                        </span>";
            break;
        case 'RETURNED':
            $status = "<span class='badge rounded-pill bg-primary-light text-primary fw-medium p-0' style='text-align:left;font-size:16px'>
                            $title Đã được trả lại cho người gửi
                        </span>";
            break;
        default:

            break;
    }

    return $status;
}
function formatPrice($price, $type = "default")
{
    if ($type == "default") {
        return str_replace(",", ".", number_format($price)) . ' đ';
    } else {
        return str_replace(",", ".", number_format($price));
    }
}

