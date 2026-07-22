@extends('backend.layout-backend')

@section('content')
    <style>
        .filter-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        .filter-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 6px;
        }
    </style>
    <div class="container my-4">
        <nav aria-label="breadcrumb" class="bg-light shadow-sm rounded p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="#" class="text-decoration-none">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Laporan</li>
            </ol>
        </nav>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="fas fa-file-alt me-2"></i>Laporan Bulanan Siswa
                </h5>

                <div class="row filter-card g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="filterTahun" class="form-label filter-label">
                            <i class="fas fa-calendar me-1"></i>Tahun
                        </label>
                        <select id="filterTahun" class="form-select">
                            <option value="">Pilih Tahun</option>
                            @for ($year = date('Y'); $year >= 2000; $year--)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="filterBulan" class="form-label filter-label">
                            <i class="fas fa-calendar-alt me-1"></i>Bulan
                        </label>
                        <select id="filterBulan" class="form-select">
                            <option value="">Pilih Bulan</option>
                            @php
                                $months = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                    4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                    7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                    10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                            @endphp
                            @foreach ($months as $num => $name)
                                <option value="{{ $num }}" {{ $num == date('n') ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button class="btn btn-primary flex-fill" onclick="filterLaporan()">
                            <i class="fas fa-search me-1"></i>Tampilkan
                        </button>
                        <button class="btn btn-outline-secondary" onclick="resetFilter()">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>Daftar Laporan
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Total Laporan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pilih tahun dan bulan, lalu klik <strong>Tampilkan</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src={{ asset('js/htmx4.0.0-beta5.min.js') }}></script>

    <script>
        function filterLaporan() {
            const tahun = $('#filterTahun').val();
            const bulan = $('#filterBulan').val();

            if (!tahun || !bulan) {
                toastr.warning('Silakan pilih tahun dan bulan terlebih dahulu');
                return;
            }

            // TODO: fetch data via AJAX
            toastr.info('Memuat laporan...');
        }

        function resetFilter() {
            $('#filterTahun').val(new Date().getFullYear());
            $('#filterBulan').val(new Date().getMonth() + 1);
            $('#dataTable tbody').html(`
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle me-1"></i>
                        Pilih tahun dan bulan, lalu klik <strong>Tampilkan</strong>
                    </td>
                </tr>
            `);
        }
    </script>
@endsection
