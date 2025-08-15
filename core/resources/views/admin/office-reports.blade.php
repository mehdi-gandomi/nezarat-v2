@extends(backpack_view('blank'))

@section('content')
<style>
.hover-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.hover-card:hover .card-body {
    background-color: #f8f9fa;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-4">گزارشات دفاتر</h1>
        </div>
    </div>

    <div class="row">
        <!-- دفاتر متخلف -->
        <div class="col-md-4 col-lg-3 mb-4">
            <a href="{{ route('office-file.index', ['violation_status' => 'with_violations']) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm hover-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="la la-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title text-dark">دفاتر متخلف</h5>
                        <h2 class="text-danger">{{ $stats['offices_with_violations'] }}</h2>
                        <p class="text-muted">دفتر</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- دفاتر فاقد تخلف -->
        <div class="col-md-4 col-lg-3 mb-4">
            <a href="{{ route('office-file.index', ['violation_status' => 'without_violations']) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm hover-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="la la-check-circle text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title text-dark">دفاتر فاقد تخلف</h5>
                        <h2 class="text-success">{{ $stats['offices_without_violations'] }}</h2>
                        <p class="text-muted">دفتر</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- دفاتر دارای تخلف ملزم به رفع نقص -->
        <div class="col-md-4 col-lg-3 mb-4">
            <a href="{{ route('office-file.index', ['violation_status' => 'requiring_defect_removal']) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm hover-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="la la-tools text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title text-dark">دفاتر دارای تخلف ملزم به رفع نقص</h5>
                        <h2 class="text-warning">{{ $stats['offices_requiring_defect_removal'] }}</h2>
                        <p class="text-muted">دفتر</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- دفاتری که به هیئت بدوی ارسال شده است -->
        <div class="col-md-4 col-lg-3 mb-4">
            <a href="{{ route('office-file.index', ['violation_status' => 'sent_to_board']) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm hover-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="la la-gavel text-info" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title text-dark">دفاتری که به هیئت بدوی ارسال شده است</h5>
                        <h2 class="text-info">{{ $stats['offices_sent_to_board'] }}</h2>
                        <p class="text-muted">دفتر</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row mt-4">
        <!-- مقایسه نموداری میزان تخلف -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="la la-bar-chart text-primary"></i>
                        مقایسه نموداری میزان تخلف
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="violationChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- مشاهده تخلفات به تفکیک دفتر -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="la la-list text-primary"></i>
                        مشاهده تخلفات به تفکیک دفتر
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>کد دفتر</th>
                                    <th>تعداد تخلفات</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $officeViolations = \App\Models\InspectionLog::where('adapt', 0)
                                        ->selectRaw('office_code, COUNT(*) as violation_count')
                                        ->groupBy('office_code')
                                        ->orderBy('violation_count', 'desc')
                                        ->limit(10)
                                        ->get();
                                @endphp

                                @foreach($officeViolations as $office)
                                <tr>
                                    <td>{{ $office->office_code }}</td>
                                    <td>
                                        <span class="badge bg-danger">{{ $office->violation_count }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('office-document.index', ['office_code' => $office->office_code]) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="la la-eye"></i> مشاهده
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('violationChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['دفاتر متخلف', 'دفاتر فاقد تخلف', 'دفاتر ملزم به رفع نقص', 'دفاتر ارسال شده به هیئت'],
            datasets: [{
                data: [
                    {{ $stats['offices_with_violations'] }},
                    {{ $stats['offices_without_violations'] }},
                    {{ $stats['offices_requiring_defect_removal'] }},
                    {{ $stats['offices_sent_to_board'] }}
                ],
                backgroundColor: [
                    '#dc3545',
                    '#28a745',
                    '#ffc107',
                    '#17a2b8'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
