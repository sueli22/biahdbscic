@extends('admin.layout')

@section('content')
    <div class="mt-3">
        <!-- Create Button -->

        <div class="mt-4 d-flex align-items-center col-4 mb-5">
            <label for="excelFile" class="form-label me-3 mb-0" style="min-width: 120px;">Excel ဖိုင် ရွေးပါ</label>
            <input type="file" id="excelFile" accept=".xlsx,.xls,.csv" class="form-control" />
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <button id="toggleChartBtn" class="btn btn-info">အကြောင်းပြန်ထားသော မေးလ်များ</button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="excelModal" tabindex="-1" aria-labelledby="excelModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('excel.calculate') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="excelModalLabel">Excel Data - Add Unit Price</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered" id="excelTable">
                                <thead>
                                    <tr>
                                        <th>လ</th>
                                        <th>အမျိုးအစား</th>
                                        <th>အရေအတွက်</th>
                                        <th>Unit Price ထည့်ပါ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Filled by JS -->
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary calculateBtn" id="calculateBtn">Calculate &
                                Download Excel</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="sendBackModal" tabindex="-1" aria-labelledby="sendBackModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('sendmail.sendback') }}" method="POST" enctype="multipart/form-data"
                        id="sendBackForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="sendBackModalLabel">📧 အီးမေးလ် ပြန်ပို့ခြင်း</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label">ပြန်ပို့ရန်</label>
                                <input type="email" name="to" class="form-control @error('to') is-invalid @enderror"
                                    value="{{ old('to') }}">
                                @error('to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ဌာန</label>
                                <input type="text" name="department"
                                    class="form-control @error('department') is-invalid @enderror"
                                    value="{{ old('department') }}">
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ဖုန်းနံပါတ် (Optional)</label>
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ခေါင်းစဉ်</label>
                                <input type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ပေးပို့သူ အမည် နှင့် ရာထူး</label>
                                <textarea name="body" class="form-control @error('body') is-invalid @enderror" rows="4">{{ old('body') }}</textarea>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ဖိုင်တွဲ (Optional)</label>
                                <input type="file" name="file"
                                    class="form-control @error('file') is-invalid @enderror">
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">📨 စာပို့မည်</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်မည်</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <canvas id="otherMailChart" style="width:100%; max-height:400px; display:none;"></canvas>

        <div class="card">
            <table id="mailTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ပေးပို့သူ (From)</th>
                        <th>ဌာန (Department)</th>
                        <th>ခေါင်းစဉ် (Title)</th>
                        <th>ပို့သည့်ရက် (Date)</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mails as $mail)
                        <tr>
                            <td>{{ $mail->from }}</td>
                            <td>{{ $mail->department }}</td>
                            <td>{{ $mail->title }}</td>

                            <td>{{ $mail->created_at->format('Y-m-d') }}</td>
                            <td>
                                @if ($mail->file)
                                    <a href="{{ asset('storage/' . $mail->file) }}" target="_blank" download
                                        title="Download ဖိုင်">
                                        <i class="bi bi-arrow-down-circle" style="font-size: 1.2rem; color: #007bff;"></i>
                                    </a>
                                @else
                                    မရှိပါ
                                @endif
                            </td>
                            <td>
                                @if ($mail->file)
                                    <a href="javascript:void(0);" class="show-detail" data-from="{{ $mail->from }}"
                                        data-department="{{ $mail->department }}" data-title="{{ $mail->title }}"
                                        data-phone="{{ $mail->phone }}" data-body="{{ $mail->body }}"
                                        data-file="{{ asset('storage/' . $mail->file) }}">
                                        <i class="bi bi-eye" style="font-size: 1.2rem; color: #007bff;"
                                            title="အသေးစိတ်ကြည့်ရန်"></i>
                                    </a>
                                @else
                                    <a href="javascript:void(0);" class="show-detail" data-from="{{ $mail->from }}"
                                        data-department="{{ $mail->department }}" data-title="{{ $mail->title }}"
                                        data-phone="{{ $mail->phone }}" data-body="{{ $mail->body }}"
                                        data-file="">
                                        <i class="bi bi-eye" style="font-size: 1.2rem; color: #007bff;"
                                            title="အသေးစိတ်ကြည့်ရန်"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <a href="javascript:void(0);" class="send-back-btn" data-from="{{ $mail->from }}"
                                    data-department="{{ $mail->department }}" data-phone="{{ $mail->phone }}"
                                    data-title="Re: {{ $mail->title }}"
                                    data-body="--- Original message ---&#10;{{ $mail->body }}" title="ပြန်ပို့မည်"
                                    data-bs-toggle="modal" data-bs-target="#sendBackModal">
                                    <i class="bi bi-reply-fill" style="font-size: 1.3rem; color: #28a745;"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mail Detail Modal -->
        <div class="modal fade" id="mailDetailModal" tabindex="-1" aria-labelledby="mailDetailLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mailDetailLabel">အီးမေးလ်အသေးစိတ်</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>ပေးပို့သူ:</strong> <span id="detailFrom"></span></p>
                        <p><strong>ဌာန:</strong> <span id="detailDepartment"></span></p>
                        <p><strong>ခေါင်းစဉ်:</strong> <span id="detailTitle"></span></p>
                        <p><strong>ဖုန်း:</strong> <span id="detailPhone"></span></p>
                        <p><strong>စာအကြောင်းအရာ:</strong></p>
                        <p id="detailBody"></p>
                        <p><strong>ဖိုင်တွဲ:</strong> <a href="#" id="detailFile" target="_blank">ဖိုင်ကြည့်ရန်</a>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်မည်</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script src="{{ asset('js/sendmail.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            $(document).ready(function() {
                var chartVisible = false;

                // Prepare data from backend
                var mailCounts = @json($otherMails->groupBy('department')->map->count());
                var chartLabels = Object.keys(mailCounts);
                var chartData = Object.values(mailCounts);

                // Pie chart configuration
                var ctx = document.getElementById('otherMailChart').getContext('2d');
                var mailChart = new Chart(ctx, {
                    type: 'pie', // Pie chart
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            data: chartData,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(153, 102, 255, 0.6)',
                                'rgba(255, 159, 64, 0.6)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.raw;
                                    }
                                }
                            }
                        }
                    }
                });

                // Toggle chart/table
                $('#toggleChartBtn').click(function() {
                    chartVisible = !chartVisible;
                    if (chartVisible) {
                        $('#otherMailChart').show();
                        $('.card').hide();
                        $(this).text('Show Table');
                    } else {
                        $('#otherMailChart').hide();
                        $('.card').show();
                        $(this).text('Show Pie Chart');
                    }
                });
            });
        </script>
    @endsection
