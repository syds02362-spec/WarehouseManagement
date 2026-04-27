<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير المخزون</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>تقرير المخزون</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>السعر</th>
                    <th>الوصف</th>
                    <th>الكمية</th>
                    <th>الحد الأدنى</th>
                    <th>القيمة الإجمالية</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->min_quantity }}</td>
                    <td>{{ $product->price * $product->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">العودة</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>