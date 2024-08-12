<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard Admin</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="assets/img/logo.png" type="image/x-icon">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <!-- Styles for the switch and sidebar -->
    <style>
        .switch-container {
            width: 360px;
            height: 50px;
            margin: 0 auto;
            position: relative;
            display: flex;
            background: #D2D2D2;
            border-radius: 25px;
        }

        .switch-button {
            width: 33.33%;
            height: 100%;
            text-align: center;
            line-height: 50px;
            font-size: 18px;
            font-family: Poppins, sans-serif;
            font-weight: 400;
            cursor: pointer;
            position: relative;
            z-index: 1;
            transition: color 0.3s;
            color: black;
        }

        .switch-button a {
            color: inherit;
            text-decoration: none;
        }

        .switch-button.active {
            color: white;
        }

        .switch-toggle {
            width: 33.33%;
            height: 100%;
            background: #4159AF;
            position: absolute;
            top: 0;
            left: 0;
            border-radius: 25px;
            transition: left 0.3s;
            z-index: 0;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 240px;
            background-color: #f8f9fa;
            padding-top: 3rem;
            transition: transform 0.3s ease;
            z-index: 1000;
            /* Ensure the sidebar is above other content */
        }

        .sidebar.hidden {
            transform: translateX(-240px);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 240px;
            /* Adjust with sidebar width */
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        .sidebar .nav-link.active {
            background-color: #e9ecef;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .toggle-sidebar {
                position: fixed;
                top: 10px;
                left: 10px;
                z-index: 1050;
                background-color: #ffffff;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 5px 10px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
            }

            .toggle-sidebar i {
                font-size: 24px;
            }
        }
    </style>

</head>

<body>
    @include('nav')

    <div class="sidebar" id="sidebar">
        <div class="px-3">
            <h3 class="fw-bold">Menu</h3>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="/dashboardAdmin">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#jadwalSubMenu" role="button"
                        aria-expanded="false" aria-controls="jadwalSubMenu">
                        Jadwal
                    </a>
                    <div class="collapse" id="jadwalSubMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link" href="/admin">Data Jadwal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/riwayatjadwal">Riwayat</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @if (session('role') == 1)
                    <li class="nav-item">
                        <a class="nav-link" href="/organisasi">Organisasi</a>
                    </li>
                @elseif (session('role') == 0)
                    <li class="nav-item">
                        <a class="nav-link" href="/userdata">Anggota</a>
                    </li>
                @elseif (session('role') == 1)
                    <li class="nav-item">
                        <a class="nav-link" href="/admindata">Admin/Sekretaris</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <!-- Sidebar Toggle Button -->
    <div class="toggle-sidebar" id="toggle-sidebar">
        <i class="bi bi-list"></i>
    </div>

    <div class="main-content">
        <main class="p-5 mt-5">
            <section class="section dashboard">
                <div class="row">
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Presensi <span>| Sedang Berjalan</span></h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-user-follow-line"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $belumCount }}</h6>
                                        <span class="text-muted small pt-2 ps-1">Berjalan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Revenue Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Presensi <span>| Sudah Ditutup</span></h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-user-unfollow-line"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $selesaiCount }}</h6>
                                        <span class="text-muted small pt-2 ps-1">Sudah Tertutup</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Revenue Card -->
                    <!-- Customers Card -->
                    <div class="col-xxl-4 col-xl-12">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Total <span>| Presensi Dibuat</span></h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-folder-user-line"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $totalPresensi }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Customers Card -->
                </div>

                <br>
                <div class="pagetitle">
                    <h1>Grafik Jumlah Kegiatan per Organisasi</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Grafik Kegiatan</li>
                        </ol>
                    </nav>
                    <!-- End Breadcrumb -->
                </div><!-- End Page Title -->

                <div class="card">
                    <div class="card-header">
                        Grafik Jumlah Kegiatan per Organisasi
                    </div>
                    <div class="card-body">
                        <canvas id="chartKegiatan" class="chartjs-render-monitor" width="400"
                            height="300"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        Jumlah Presensi Anggota Organisasi Tiap Minggu
                    </div>
                    <div class="card-body">
                        <form id="form-dropdown" action="{{ route('dashboard-admin.show') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <select name="organisasi_id" id="organisasi-dropdown" class="form-control">
                                    <option value="">Pilih Organisasi</option>
                                    @foreach ($organisasi as $org)
                                        <option value="{{ $org->id_organisasi }}">{{ $org->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <select name="bulan" id="month-dropdown" class="form-control">
                                    <option value="">Pilih Bulan</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </section>
        </main><!-- End #main -->
    </div>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.getElementById('sidebar');
            var toggleSidebar = document.getElementById('toggle-sidebar');

            toggleSidebar.addEventListener('click', function() {
                if (sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                } else {
                    sidebar.classList.add('show');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('chartJumlahAnggota').getContext('2d');

            var labels = [];
            var data = [];

            // Memuat data dari PHP ke dalam format yang diperlukan oleh Chart.js
            @foreach ($hasil_per_minggu as $hasil)
                labels.push('Minggu {{ $hasil['minggu'] }}');
                data.push({{ $hasil['jumlah_anggota'] }});
            @endforeach

            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Anggota Yang Melakukan Presensi',
                        backgroundColor: 'rgba(75, 119, 190, 0.8)', // Ubah warna latar belakang grafik
                        borderColor: 'rgba(75, 119, 190, 1)', // Ubah warna border grafik
                        borderWidth: 1,
                        data: data,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                },
            });
        });
    </script>
    <!-- Script for Chart -->
    <script>
        var ctx = document.getElementById('chartKegiatan').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! $organisasi->pluck('nama') !!},
                datasets: [{
                    label: 'Jumlah Kegiatan',
                    data: {!! $organisasi->pluck('presensi_count') !!},
                    backgroundColor: 'rgba(75, 119, 190, 0.8)', // Ubah warna latar belakang grafik
                    borderColor: 'rgba(75, 119, 190, 1)', // Ubah warna border grafik
                    borderWidth: 2
                }]
            },
            options: {
                animation: {
                    duration: 2000, // Durasi animasi (ms)
                    easing: 'easeInOutQuart' // Efek animasi (opsional)
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value, index, values) {
                                return value + ' kegiatan';
                            }
                        }
                    }
                },
                maintainAspectRatio: false,
                responsive: true
            }
        });
    </script>
</body>

</html>
