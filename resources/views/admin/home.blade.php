@extends('admin.layout')

@section('content')
<div style="display: flex; gap: 50px; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 300px;height: 400px;">
        <h2>၀န်ထမ်း ရာထူး ပြအခြေခံ ဇယား</h2>
        <canvas id="pieChart"></canvas>
    </div>

    <div style="flex: 1; min-width: 300px;">
        <h2>ရာထူး နှင့် လစာ ပြအခြေခံ ဇယား</h2>
        <canvas id="lineChart" class="margin-top: 20px;height: 400px;"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // -----------------------------
    // Pie Chart: Users per Position
    // -----------------------------
    const pieUserData = @json($usersByPosition);
    const pieLabels = pieUserData.map(u => u.position ? u.position.title : 'Unknown');
    const pieData = pieUserData.map(u => u.total);
    const pieColors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];

    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                label: 'Users per Position',
                data: pieData,
                backgroundColor: pieColors,
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Users per Position' }
            }
        }
    });

    // -----------------------------
    // Line Chart: Salary by Position
    // -----------------------------
    const lineSalaryData = @json($salaryByPosition);
    const lineLabels = lineSalaryData.map(s => s.title);
    const lineData = lineSalaryData.map(s => s.total_salary);

    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: lineLabels,
            datasets: [{
                label: 'Total Salary',
                data: lineData,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Total Salary by Position' }
            }
        }
    });
</script>
@endsection
