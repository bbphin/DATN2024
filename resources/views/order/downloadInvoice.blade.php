<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hoá đơn</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <style type="text/css">
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ asset('fonts/' . $font_family) }}") format('truetype');
        }

        body {
            font-family: 'THSarabunNew';
        }


        * {
            box-sizing: border-box;

        }

        pre,
        p {
            padding: 0;
            margin: 0;

        }

        table {
            width: 100%;
            border-collapse: collapse;
            padding: 1px;

        }

        td,
        th {
            text-align: left;

        }

        .visibleMobile {
            display: none;

        }

        .hiddenMobile {
            display: block;

        }
    </style>
</head>

<body>
    {{-- header start --}}
    <table style="width: 100%; table-layout: fixed">
        <tr>
            <td colspan="4"
                style="border-right: 1px solid #e4e4e4; width: 300px; color: #323232; line-height: 1.5; vertical-align: top;">
                <p style="font-size: 15px; color: #5b5b5b; font-weight: bold; line-height: 1; vertical-align: top; ">
                    Hoá đơn</p>
                <br>
                <p style="font-size: 12px; color: #5b5b5b; line-height: 24px; vertical-align: top;">
                    Hoá đơn : INV
                    {{ $order->id }}<br>
                    Thời gian tạo : {{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y H:i:s') }}
                </p>
            </td>
            <td colspan="4" align="right"
                style="width: 300px; text-align: right; padding-left: 50px; line-height: 1.5; color: #323232;">
                <h3>{{ env('APP_NAME') }}</h3>
                <p style="font-size: 12px;font-weight: bold; color: #5b5b5b; line-height: 1; vertical-align: top; ">
                    Thanh Xuân</p>
                <p style="font-size: 12px; color: #5b5b5b; line-height: 24px; vertical-align: top;">
                    Hà Nội<br>
                    Số điện thoại: 0888888888
                </p>
            </td>
        </tr>
        <tr class="visibleMobile">
            <td height="10"></td>
        </tr>
        <tr>
            <td colspan="10" style="border-bottom:1px solid #e4e4e4"></td>
        </tr>
    </table>
    {{-- header end --}}

    {{-- billing and shipping start --}}
    <table class="table" style="width: 100%;">
        <tbody style="display: table-header-group">
            <tr class="visibleMobile">
                <td height="20"></td>
            </tr>
            <tr style=" margin: 0;">
                <td colspan="4" style="width: 300px;">
                    <p
                        style="font-size: 12px; font-weight: bold; color: #5b5b5b; line-height: 1; vertical-align: top; ">
                        Địa chỉ khách hàng</p>
                    <p style="font-size: 12px; color: #5b5b5b; line-height: 24px; vertical-align: top;">
                        {{ $order->address }},
                    <p class="mb-0">Trạng thái chuyển:
                        {!! getStatusOrderShip($order->shipment_status) !!}
                    </p>
                    </p>
                </td>
            </tr>

        </tbody>
    </table>
    {{-- billing and shipping end --}}

    {{-- item details start --}}
    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable"
        bgcolor="#ffffff">
        <tbody>
            <tr>
                <td>
                    <table width="600" border="0" cellpadding="0" cellspacing="0" align="center"
                        class="fullTable" bgcolor="#ffffff">
                        <tbody>
                            <tr class="visibleMobile">
                                <td height="40"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"
                                        class="fullPadding">
                                        <tbody>
                                            <tr>
                                                <th style="font-size: 12px; color: #000000; font-weight: normal;
                  line-height: 1; vertical-align: top; padding: 0 10px 7px 0;"
                                                    width="52%" align="left">
                                                    Sản phẩm
                                                </th>
                                                <th style="font-size: 12px; color: #000000; font-weight: normal;
                  line-height: 1; vertical-align: top; padding: 0 0 7px;"
                                                    align="left">
                                                    Giá
                                                </th>
                                                <th style="font-size: 12px; color: #000000; font-weight: normal;
                  line-height: 1; vertical-align: top; padding: 0 0 7px; text-align: center; "
                                                    align="center">
                                                    Số lượng
                                                </th>
                                                <th style="font-size: 12px; color: #000000; font-weight:
                  normal; line-height: 1; vertical-align: top; padding: 0 0 7px; text-align: right; "
                                                    align="right">
                                                    Thành tiền
                                                </th>
                                            </tr>
                                            <tr>
                                                <td height="1" style="background: #e4e4e4;" colspan="4"></td>
                                            </tr>
                                            @php  $totalOrderPrice = 0; @endphp
                                            @foreach ($order->orderItems as $key => $item)
                                                @php
                                                    $product = $item->product;
                                                    $totalPrice = $product->price * $item->quantity;
                                                    $totalOrderPrice += $totalPrice;
                                                @endphp
                                                <tr>
                                                    <td style="font-size: 12px; color: #5b5b5b;  line-height: 18px;  vertical-align: top; padding:10px 0;"
                                                        class="article">
                                                        <div>{{ $product->name }}</div>
                                                        <div class="text-muted">
                                                            <span class="fs-xs">
                                                                {{ $product->Color->name }} x {{ $product->Size->name }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td
                                                        style="font-size: 12px; color: #646a6e;  line-height:
              18px;  vertical-align: top; padding:10px 0;">
                                                        {{ formatPrice($product->price) }}
                                                    </td>
                                                    <td style="font-size: 12px; color: #646a6e;  line-height:
              18px;  vertical-align: top; padding:10px 0; text-align: center;"
                                                        align="center">{{ $item->quantity }}</td>
                                                    <td style="font-size: 12px; color: #1e2b33;  line-height:
              18px;  vertical-align: top; padding:10px 0; text-transform: capitalize !important;"
                                                        align="right">
                                                        <strong>{{ formatPrice($totalPrice) }}
                                                        </strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="1" style="background: #e4e4e4;" colspan="4"></td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="20"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    {{-- item details end --}}

    {{-- item total start --}}
    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable"
        bgcolor="#ffffff">
        <tbody>
            <tr>
                <td>
                    <table width="600" border="0" cellpadding="0" cellspacing="0" align="center"
                        class="fullTable" bgcolor="#ffffff">
                        <tbody>
                            <tr>
                                <td>
                                    <!-- Table Total -->
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"
                                        class="fullPadding">
                                        <tbody>

                                            <tr>
                                                <td
                                                    style="font-size: 12px; color: #000; line-height: 22px; vertical-align: top; text-align:right; ">
                                                    <strong>Tổng tiền</strong>
                                                </td>
                                                <td
                                                    style="font-size: 12px; color: #000; line-height: 22px; vertical-align: top; text-align:right; ">
                                                    <strong>{{ formatPrice($totalOrderPrice) }}</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!-- /Table Total -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    {{-- item total end --}}

    {{-- Note --}}
    @if ($order->note)
        <p style="text-align:center;margin:10px 0"><b>Ghi chú:</b> {{ $order->note }}</p>
    @endif


</body>

</html>
