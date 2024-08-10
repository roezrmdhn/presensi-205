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
        }

        .main-content {
            margin-left: 240px;
            /* Adjust with sidebar width */
            padding: 20px;
        }

        .sidebar .nav-link.active {
            background-color: #e9ecef;
        }
    </style>

</head>

<body>
    @include('nav')

    <div class="sidebar">
        <div class="px-3">
            <h3 class="fw-bold">Menu</h3>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="/dashboardAdmin">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin">Jadwal</a>
                </li>
                @if (session('isAdmin') == 1)
                    <li class="nav-item">
                        <a class="nav-link" href="/organisasi">Organisasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/userdata">Anggota</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admindata">Admin/Sekretaris</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="main-content">
        <main class="p-5 mt-5">

            <div class="pagetitle">
                <h1>Grafik Jumlah Kegiatan dan Jumlah Presensi Anggota Organisasi Tiap Minggu</h1>
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

            <section class="section dashboard">
                <div class="card">
                    <div class="card-header">
                        Grafik Jumlah Kegiatan Per Minggu
                        <br>
                        {{ $organisasi->nama }} di bulan {{ $nama_bulan }}
                    </div>
                    <div class="card-body">
                        <canvas id="chartJumlahKegiatan" class="chartjs-render-monitor" width="400"
                            height="300"></canvas>
                    </div>
                    <div class="card-header">
                        Grafik Jumlah Presensi Anggota Per Minggu
                        <br>
                        {{ $organisasi->nama }} di bulan {{ $nama_bulan }}
                    </div>
                    <div class="card-body">
                        <canvas id="chartJumlahAnggota" class="chartjs-render-monitor" width="400"
                            height="300"></canvas>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('dashboardAdmin') }}" class="btn btn-secondary">Kembali</a>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php
    // Mengonversi data PHP menjadi format JSON
    $labels = [];
    $dataAnggota = [];
    $dataKegiatan = [];

    foreach ($hasil_per_minggu as $hasil) {
        $labels[] = 'Minggu ' . $hasil['minggu'];
        $dataAnggota[] = $hasil['jumlah_anggota'];
        $dataKegiatan[] = $hasil['jumlah_kegiatan'];
    }

    // Encode array menjadi format JSON
    $labelsJson = json_encode($labels);
    $dataAnggotaJson = json_encode($dataAnggota);
    $dataKegiatanJson = json_encode($dataKegiatan);
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    var ctxAnggota = document.getElementById('chartJumlahAnggota').getContext('2d');
    var ctxKegiatan = document.getElementById('chartJumlahKegiatan').getContext('2d');

    // Mendapatkan data dari PHP yang sudah di-encode menjadi JSON
    var labels = <?php echo $labelsJson; ?>;
    var dataAnggota = <?php echo $dataAnggotaJson; ?>;
    var dataKegiatan = <?php echo $dataKegiatanJson; ?>;

    // Chart untuk Jumlah Anggota
    new Chart(ctxAnggota, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Anggota Yang Melakukan Presensi',
                backgroundColor: 'rgba(75, 119, 190, 0.8)', // Ubah warna latar belakang grafik
                borderColor: 'rgba(75, 119, 190, 1)', // Ubah warna border grafik
                borderWidth: 1,
                data: dataAnggota,
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: { // Perubahan dari yAxes menjadi y
                    beginAtZero: true,
                    ticks: {
                            stepSize: 1,
                            callback: function(value, index, values) {
                                return value + ' Anggota';
                            }
                        }
                }
            }
        }
    });

    // Chart untuk Jumlah Kegiatan
    new Chart(ctxKegiatan, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Kegiatan',
                backgroundColor: 'rgba(75, 119, 190, 0.8)', // Ubah warna latar belakang grafik
                borderColor: 'rgba(75, 119, 190, 1)', // Ubah warna border grafik
                borderWidth: 1,
                data: dataKegiatan,
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: { // Perubahan dari yAxes menjadi y
                    beginAtZero: true,
                    ticks: {
                            stepSize: 1,
                            callback: function(value, index, values) {
                                return value + ' kegiatan';
                            }
                        }
                }
            }
        }
    });
});
    </script>

</body>

</html>
