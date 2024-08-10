<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Admin</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link rel="shortcut icon" href="assets/img/logo.png" type="image/x-icon">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}">
    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
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
            padding: 20px;
        }

        /* Mobile view */
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
    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Jan 29 2024 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
    @if (session('warning'))
        <script>
            Swal.fire({
                icon: 'danger',
                title: 'Kode Presensi : ',
                html: '<h1 style = " font-weight: 900;"> </h1>',
            });
        </script>
    @endif
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Kode Presensi : ',
                html: '<h1 style = " font-weight: 900;"> {{ session('kodeAcak') }}</h1>',
            });
        </script>
    @endif
    @if (session('update'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                html: '<p > Data Berhasil Diperbarui!</p>',
            });
        </script>
    @endif
    @if (session('delete'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                html: '<p > Data Berhasil Dihapus!</p>',
            });
        </script>
    @endif
    @if (session('kosong'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Belum Ada Presensi!',
                html: '<p > Belum Ada Yang Melakukan Presensi!</p>',
            });
        </script>
    @endif


    @include('nav')

    <div class="sidebar" id="sidebar">
        <div class="px-3">
            <h3 class="fw-bold">Menu</h3>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="/dashboardAdmin">Dashboard</a>
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
                                <a class="nav-link active" href="/riwayatjadwal">Riwayat</a>
                            </li>
                        </ul>
                    </div>
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

    <!-- Sidebar Toggle Button -->
    <div class="toggle-sidebar" id="toggle-sidebar">
        <i class="bi bi-list"></i>
    </div>

    <div class="main-content">
        <main class="p-5 mt-5">

            <div class="pagetitle">
                <h1>Data Tables</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">Tables</li>
                        <li class="breadcrumb-item active">Data</li>
                    </ol>
                </nav>
            </div><!-- End Page Title -->

            <section class="section dashboard">
                <div class="row">
                    <!-- Reports -->
                    <div class="col-12">

                    </div><!-- End Reports -->

                    <div class="col-lg-12">

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Data Riwayat</h5>
                                <!-- Table with stripped rows -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#verticalycentered"
                                    class="btn btn-primary">Cetak Riwayat</button>
                                <div class="modal fade" id="verticalycentered" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('absen-anggota.show') }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Cetak Riwayat
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Dropdown untuk memilih organisasi -->
                                                    <div class="container mb-4"
                                                        style="border-radius: 5px; width: 70%; background-color: rgb(249, 250, 251); box-shadow: 0px 2px 20px rgba(3, 41, 112, 0.1);">
                                                        <div class="row">
                                                            <div
                                                                class="col-lg-3 d-flex justify-content-center align-items-center">
                                                                <label for="organization-select"
                                                                    class="form-label">Organisasi</label>
                                                            </div>
                                                            {{-- <div
                                                                class="col-lg-9 d-flex justify-content-center align-items-center">
                                                                <select class="form-select" id="organization-select"
                                                                    name="organisasi_id">
                                                                    <option selected>Pilih Organisasi</option>
                                                                    @foreach ($riwayatjadwal as $organisasi)
                                                                        <option
                                                                            value="{{ $organisasi->id_organisasi }}">
                                                                            {{ $organisasi->nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div> --}}
                                                            <div
                                                                class="col-lg-9 d-flex justify-content-center align-items-center">
                                                                <input type="number" class="form-control"
                                                                    name="organisasi_id"
                                                                    value="{{ session('idOrganisasiTersimpan') }}"
                                                                    disabled>
                                                            </div>
                                                            {{-- <div class="col-lg-9 d-flex justify-content-center align-items-center">
                                                                <select class="form-select" id="organization-select" name="organisasi_id" disabled>
                                                                    @foreach ($riwayatjadwal as $organisasi)
                                                                        <option value="{{ $organisasi->id_organisasi }}" 
                                                                            {{ session('idOrganisasiTersimpan') == $organisasi->id_organisasi ? 'selected' : '' }}>
                                                                            {{ $organisasi->nama }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div> --}}

                                                        </div>
                                                    </div>

                                                    <!-- Date range picker -->
                                                    <div class="container mb-4"
                                                        style="border-radius: 5px; width: 70%; background-color: rgb(249, 250, 251); box-shadow: 0px 2px 20px rgba(3, 41, 112, 0.1);">
                                                        <div class="row">
                                                            <div
                                                                class="col-lg-3 d-flex justify-content-center align-items-center">
                                                                <label for="start-date" class="form-label">Tanggal
                                                                    Pelaksanaan</label>
                                                            </div>
                                                            <div
                                                                class="col-lg-9 d-flex justify-content-center align-items-center">
                                                                <input type="date" class="form-control"
                                                                    id="start-date" name="start_date">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Button Kirim -->
                                                    <div class="d-flex justify-content-center">
                                                        <button type="submit" id="submitBtn"
                                                            class="btn btn-primary">Kirim</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Kode
                                                </th>
                                                <th>Nama</th>

                                                <th data-type="date" data-format="DD/MM/YYYY">Waktu Mulai</th>
                                                <th data-type="date" data-format="DD/MM/YYYY">Waktu Selesai</th>
                                                <th>Deskripsi</th>
                                                <th>Departemen</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($presensiData as $data)
                                                <tr>
                                                    <td>{{ $data->kode_acak }}</td>
                                                    <td>{{ $data->event_name }}</td>
                                                    <td>{{ $data->time_start }}</td>
                                                    <td>{{ $data->time_end }}</td>
                                                    <td>{{ $data->description }}</td>
                                                    <td>{{ $data->nama_organisasi }}</td>
                                                    <td>
                                                        <a type="button" class="px-2 delete-presensi"
                                                            data-presensi-id="{{ $data->id_presensi }}">
                                                            <i class="bi bi-trash"
                                                                style="color: black; font-size: 20px;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                    <!-- End Table with stripped rows -->
                                </div>

                            </div>
                        </div>

                    </div>
                </div>


            </section>


        </main><!-- End #main -->
    </div>
    <!-- ======= Footer ======= -->


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSidebar = document.getElementById('toggle-sidebar');
            const sidebar = document.getElementById('sidebar');

            toggleSidebar.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        });
    </script>

    <script>
        // Menangani klik pada tombol "Hapus"
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-presensi');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const presensiId = this.getAttribute('data-presensi-id');
                    // Menampilkan sweet alert
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda tidak akan dapat mengembalikan ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Oke, hapus saja!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika tombol "Oke" diklik, menuju ke route delete-presensi dengan ID presensi
                            window.location.href = `/delete-presensi/${presensiId}`;
                        }
                    });
                });
            });
        });
    </script>

    <script>
        document.getElementById('submitBtn').addEventListener('click', function() {
            this.setAttribute('formtarget', '_blank');
        });
    </script>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        function updateClock() {
            var now = new Date();
            var hours = ('0' + now.getHours()).slice(-2);
            var minutes = ('0' + now.getMinutes()).slice(-2);
            var seconds = ('0' + now.getSeconds()).slice(-2);
            var day = now.toLocaleDateString('en-US', {
                weekday: 'long'
            });
            var date = ('0' + now.getDate()).slice(-2);
            var month = now.toLocaleDateString('en-US', {
                month: 'long'
            });
            var year = now.getFullYear();

            document.getElementById('clock').textContent = hours + ':' + minutes + ':' + seconds + ' | ' + day + ', ' +
                date + ' ' + month + ' ' + year;
        }

        setInterval(updateClock, 1000); // Update setiap detik
    </script>
    <script>
        function submitForm() {
            document.getElementById("updateForm").submit();
        }
    </script>

    @include('sweetalert::alert')
</body>

</html>
