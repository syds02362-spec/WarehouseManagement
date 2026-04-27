<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم الرئيسية - مخزون البضاعة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
  <div class="d-flex">
        <!-- المحتوى الرئيسي -->
        <div class="main-content flex-grow-1">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- هيدر للشاشات الصغيرة -->
            <div class="d-md-none mb-3 text-center">
                <h3>لوحة التحكم الرئيسية</h3>
                <div class="btn-group-vertical w-100">
                    <a href="{{ route('products.create') }}" class="btn btn-primary mb-2"><i class="fas fa-plus-circle"></i> إضافة صنف جديد</a>
                    <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#stockInModal"><i class="fas fa-arrow-up"></i> إدخال مخزون</button>
                    <button class="btn btn-warning mb-2" data-bs-toggle="modal" data-bs-target="#stockOutModal"><i class="fas fa-arrow-down"></i> إخراج مخزون</button>
                    <a href="{{ route('products.report') }}" class="btn btn-info mb-2"><i class="fas fa-chart-bar"></i> تقرير المخزون</a>
                </div>
            </div>
            <!-- بطاقات الإحصائيات -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="stat-icon text-danger"><i class="fas fa-exclamation-triangle"></i></div>
                        <h4>{{ $expired }}</h4>
                        <p>أصناف نافذة</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="stat-icon text-warning"><i class="fas fa-chart-line"></i></div>
                        <h4>{{ $low_stock }}</h4>
                        <p>منخفض المخزون</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="stat-icon text-primary"><i class="fas fa-boxes"></i></div>
                        <h4>{{ $total_items }}</h4>
                        <p>إجمالي الأصناف</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="stat-icon text-success"><i class="fas fa-cubes"></i></div>
                        <h4>{{ $total_quantity }}</h4>
                        <p>إجمالي الكمية</p>
                    </div>
                </div>
            </div>

            <!-- جدول قائمة الأصناف -->
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>قائمة الأصناف</h5>
                    <div>
                        <button id="filterLowStock" class="btn btn-warning me-2">عرض المنخفضة فقط</button>
                        <button id="showAll" class="btn btn-secondary me-2">عرض الكل</button>
                        <a href="{{ route('products.create') }}" class="btn btn-success me-2"><i class="fas fa-plus"></i> إضافة صنف جديد</a>
                        <a href="{{ route('products.export') }}" class="btn btn-info"><i class="fas fa-download"></i> تصدير</a>
                    </div>
                </div>
                <div class="card-body">
                    <input type="text" class="form-control mb-3" placeholder="البحث عن صنف..." id="searchInput">
                    <div class="table-responsive">
                        <table class="table table-striped" id="productsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم الصنف</th>
                                <th>الباركود</th>
                                <th>الكمية</th>
                                <th>الحد الأدنى</th>
                                <th>السعر</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $index => $product)
                            <tr data-min-quantity="{{ $product->min_quantity }}" data-quantity="{{ $product->quantity }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->id }}</td> <!-- افتراضيًا كباركود -->
                                <td>{{ $product->quantity }}</td>
                                <td>{{ $product->min_quantity }}</td>
                                <td>{{ $product->price }}</td>
                                <td>
                                    @if($product->quantity > $product->min_quantity)
                                        <span class="status-available">متوفر</span>
                                    @elseif($product->quantity > 0)
                                        <span class="status-low">منخفض</span>
                                    @else
                                        <span class="status-expired">نافذ</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد؟')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <!-- Pagination -->
                    <nav>
                        <ul class="pagination justify-content-center">
                            <li class="page-item"><a class="page-link" href="#">السابق</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">التالي</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- آخر الحركات -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5><i class="fas fa-history"></i> آخر الحركات</h5>
                </div>
                <div class="card-body">
                    @forelse($recent_activities as $activity)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>{{ $activity['name'] }}</strong> - {{ $activity['quantity'] }} وحدة ({{ $activity['type'] }})
                        </div>
                        <small>{{ $activity['time'] }}</small>
                    </div>
                    @empty
                    <p>لا توجد حركات حديثة.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- الإجراءات السريعة (الشريط الجانبي) -->
        <div class="sidebar">
            <h5>الإجراءات السريعة</h5>
            <a href="{{ route('products.create') }}" class="btn btn-primary w-100 mb-2"><i class="fas fa-plus-circle"></i> إضافة صنف جديد</a>
            <button class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#stockInModal"><i class="fas fa-arrow-up"></i> إدخال مخزون</button>
            <button class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#stockOutModal"><i class="fas fa-arrow-down"></i> إخراج مخزون</button>
            <a href="{{ route('products.report') }}" class="btn btn-info w-100"><i class="fas fa-chart-bar"></i> تقرير المخزون</a>
        </div>
    </div>

    <!-- Modal for Stock In -->
    <div class="modal fade" id="stockInModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إدخال مخزون</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="#" method="POST" id="stockInForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="productSelectIn" class="form-label">اختر الصنف</label>
                            <select class="form-select" id="productSelectIn" name="product_id" required>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantityIn" class="form-label">الكمية</label>
                            <input type="number" class="form-control" id="quantityIn" name="quantity" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-success">إدخال</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Stock Out -->
    <div class="modal fade" id="stockOutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إخراج مخزون</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="#" method="POST" id="stockOutForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="productSelectOut" class="form-label">اختر الصنف</label>
                            <select class="form-select" id="productSelectOut" name="product_id" required>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantityOut" class="form-label">الكمية</label>
                            <input type="number" class="form-control" id="quantityOut" name="quantity" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-warning">إخراج</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>