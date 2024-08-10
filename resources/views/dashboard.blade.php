<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Anggota</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Jan 29 2024 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                html: '<p>Berhasil Melakukan Presensi!</p>',
            });
        </script>
    @endif
    @if (session('done'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Sudah Melakukan Presensi!',
                html: '<p>Anda Sudah Melakukan Presensi Sebelumnya!</p>',
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menemukan Kode!',
                html: '<p>Kode Tidak Ditemukan. Silahkan Masukan Cek Kode Yang Anda Masukan!</p>',
            });
        </script>
    @endif
    @if (session('notStart'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Diluar jam event!',
                html: '<p>Waktu presensi tidak sesuai, silahkan coba lagi nanti.</p>',
            });
        </script>
    @endif
    @if (session('notAllowed'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Departemen tidak diizinkan!',
                html: '<p>Silahkan periksa kembali jadwal presensi yang sesuai!</p>',
            });
        </script>
    @endif
    @if (session('gagal'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Presensi Sudah Ditutup!',
                html: '<p>Presensi Sudah Ditutup. Silahkan Konfirmasi Pada Admin!</p>',
            });
        </script>
    @endif

    <!-- ======= Header ======= -->
    @include('nav')

    <main class="p-5 mt-5">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-8">
                    <div class="row">
                        <!-- Card with an image on left -->

                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="{{ 'assets/img/gambar presensi.png' }}"
                                        style=" width: 50%; padding-top: 5%;padding-bottom: 5%;"
                                        class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body ">
                                        <h5 class="card-title">Card with an image on left</h5>
                                        <p class="card-text">This is a wider card with supporting text below as a
                                            natural lead-in to additional content. This content is a little bit longer.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Card with an image on left -->

                        <section class="section">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Tabel Absensi Anggota</h5>

                                            <!-- Table with stripped rows -->
                                            <div class="table-responsive">
                                                <table class="table datatable table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th><b>Kode Absen</b></th>
                                                            <th>Jam Presensi</th>
                                                            <th>Tanggal Presensi</th>
                                                            <th>Nama Kegiatan</th>
                                                            <th>Nama Pembuat</th>
                                                            <th>Deskripsi Kegiatan</th>
                                                            <th>Waktu Mulai</th>
                                                            <th>Waktu Selesai</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($dataPresensi as $presensi)
                                                            @foreach ($presensi->detail_presensi as $detail_presensi)
                                                                <tr>
                                                                    <td>{{ $presensi->kode_acak }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($detail_presensi->jam_presensi)->format('H:i:s') }}</td>
                                                                    <td>{{ $detail_presensi->tanggal_presensi }}</td>
                                                                    <td>{{ $presensi->event_name }}</td>
                                                                    <td>
                                                                        @if ($presensi->nama_organisasi === 'Semua Organisasi')
                                                                            <p>Admin</p>
                                                                        @else
                                                                            {{ $presensi->nama_organisasi }}
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $presensi->description }}</td>
                                                                    <td>{{ $presensi->time_start }}</td>
                                                                    <td>{{ $presensi->time_end }}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- End Table with stripped rows -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>


                    </div>
                </div><!-- End Left side columns -->

                <!-- Right side columns -->
                <div class="col-lg-4">
                    <!-- Budget Report -->
                    <div class="card">


                        <div class="card-body pb-0">
                            <h5 class="card-title">Presensi <span>| Tekan Tombol Presensi Jika Ingin Melakukan Presensi</span></h5>
                            <div id="realTimeClock" style="text-align: right; margin-right: 5%;"></div>
                            <div id="todayDate" style="text-align: right; margin-right: 5%;"></div>
                            <div class="d-flex justify-content-center" style="text-align:center; margin-bottom:5%; margin-top:-10%; margin-left:5%;"></div>

                            <img src="assets/img/absence.png" alt="Absence Image" style="display:block; margin:auto; max-width:50%; margin-top: 10%;">
                            <br>
                            <a class="text-center" id="absensiButton" type="button" style="background-color:blue; height: 5%; width: 65%; margin-bottom: 10%; margin-top: 10%; color: white; font-size: 2vw; font-weight: 900; display:block; margin:auto; text-align:center;">
                                Presensi
                            </a>
                            <br>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="verticalycentered" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Form Pengisian Absensi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <label for="nama">Kode Absen</label>
                                        <input type="text" id="paslon" class="form-control"
                                            placeholder="Contoh form text ...">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input type="text" id="paslon" class="form-control"
                                            placeholder="Contoh form text ...">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Departemen</label>
                                        <input type="text" id="paslon" class="form-control"
                                            placeholder="Contoh form text ...">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Budget Report -->


            </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('absensiButton').addEventListener('click', function() {
                Swal.fire({
                    title: 'Input Kode Acak',
                    html: '<input id="kodeAcak" class="swal2-input" placeholder="Masukkan kode acak">',
                    showCancelButton: true,
                    confirmButtonText: 'Tambah',
                    cancelButtonText: 'Batal',
                    focusConfirm: false,
                    preConfirm: () => {
                        const kodeAcak = Swal.getPopup().querySelector('#kodeAcak').value;
                        if (!kodeAcak) {
                            Swal.showValidationMessage('Kode acak harus diisi');
                        }
                        return {
                            kodeAcak: kodeAcak
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect ke halaman absensi dengan kode acak yang dimasukkan
                        window.location.href = '/absensi/' + result.value.kodeAcak;
                    }
                });
            });
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
    <script>
        function newClock() {
            var now = new Date();
            var options = {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            var timeString = now.toLocaleTimeString('en-US', options);
            document.getElementById('realTimeClock').innerHTML = timeString;

            var dateOptions = {
                timeZone: 'Asia/Jakarta',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            var dateString = now.toLocaleDateString('en-US', dateOptions);
            document.getElementById('todayDate').innerHTML = dateString;
        }

        setInterval(newClock, 1000); // Update every second
    </script>

</body>

</html>
