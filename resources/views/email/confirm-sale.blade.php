<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css">
        table tr td {
            padding-right: 10px;
        }
    </style>
</head>
<body>
    <p>
        Confirm if you want to sell the product "{{ $data['name_product'] }}"
    </p>
    <p>
        <button>
            <a href="{{ $data['url'] }}/confirm-order?order_id={{ $data['order_id'] }}">Click here</a>
        </button>
    </p>
</body>
</html>