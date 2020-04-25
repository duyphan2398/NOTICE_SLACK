<!DOCTYPE html>
<html>
<head>
    <title>Bill</title>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        table.center {
            margin-left:auto;
            margin-right:auto;
        }
        .content{
            margin-bottom: 3px;
        }
    </style>
</head>
    <body class="text-center">
        <h1 class="content">My Cafe Bill</h1>
        <h4 class="content">Address: 2/12A Tan Thuan Tay District 7 HCM city</h4>
        <h4 class="content">Contact: 0936221326</h4>
        <h5 class="content">Date: {{$receipt['billing_at']}}</h5>
        <hr class="w-50">
        <h3>Thank you</h3>
        <table class="table w-75 center">
            <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit price</th>
                <th>Total price</th>
                <th>Sale price</th>
            </tr>
            </thead>
            <tbody>
            @foreach($receipt['products'] as $product)
                <tr>
                    <td>{{$product['product_name']}}</td>
                    <td>{{$product['quantity']}}</td>
                    <td>{{$product['product_price']}}</td>
                    <td>{{$product['product_price'] * $product['quantity']}}</td>
                    <td>{{($product['product_sale_price']) ? $product['product_sale_price'] * $product['quantity'] : '--'}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <hr>
        <h5>Total: {{($receipt['sale_excluded_price']}}</h5>
        @if(($receipt['sale_included_price']) && ($receipt['sale_included_price'] < $receipt['sale_excluded_price']))
            <br>
            <h5>{{$receipt['sale_included_price']}}</h5>
        @endif
        <hr>
        <h4>
            Customer Service : mycafe@cafemail.com
        </h4>
    </body>
</html>