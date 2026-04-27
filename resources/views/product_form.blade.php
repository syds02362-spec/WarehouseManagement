<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product ? 'تعديل الصنف' : 'إضافة صنف جديد' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>{{ $product ? 'تعديل الصنف' : 'إضافة صنف جديد' }}</h1>
        <form action="{{ $product ? route('products.update', $product) : route('products.store') }}" method="POST">
            @csrf
            @if($product)
                @method('PUT')
            @endif
            <div class="mb-3">
                <label for="name" class="form-label">اسم الصنف</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $product->name ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">السعر</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $product->price ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">الوصف</label>
                <textarea class="form-control" id="description" name="description" required>{{ $product->description ?? '' }}</textarea>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">الكمية</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $product->quantity ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="min_quantity" class="form-label">الحد الأدنى</label>
                <input type="number" class="form-control" id="min_quantity" name="min_quantity" value="{{ $product->min_quantity ?? '' }}" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ $product ? 'تحديث' : 'إضافة' }}</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>