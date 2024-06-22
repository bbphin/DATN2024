<?php

function ApiResponse($success = true, $statusCode = null, $message = null, $data = null,$type=NULL)
{
    if ($success || $type=="custom") {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'statusCode' => $statusCode,
            'data' => $data
        ], $statusCode);
    }
    return response()->json([
        'success' => $success,
        'message' => $message,
        'statusCode' => $statusCode,
    ], $statusCode);
}

// sử dụng khi hiển thị danh sách
function messageResponseData()
{
    return 'Hiển thị thông tin thành công';
}

// sử dụng với CRUD với trạng thái thành công
function messageResponseActionSuccess()
{
    return 'Thao tác thành công';
}

// sử dụng với CRUD với trạng thái thất bại
function messageResponseActionFailed()
{
    return 'Thao tác không thành công, vui lòng thử lại';
}

// sử dụng khi tìm sản phẩm
function messageResponseNotFound()
{
    return 'Không tìm thấy thông tin, vui lòng thử lại';
}
