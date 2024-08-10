    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>Pages / Login</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>

        <!-- Favicons -->
        <link href="{{ asset('assets/logo.png') }}" rel="icon">
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

        <!-- Template Main CSS File -->
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

        <!-- =======================================================
    * Template Name: NiceAdmin
    * Updated: Jan 29 2024 with Bootstrap v5.3.2
    * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
    </head>

    <body>
        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Error!',
                        text: '@foreach ($errors->all() as $error){{ $error }}@endforeach',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Error!',
                        text: '{{ session('error') }}',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif
        <main>
            <div class="container">

                <section
                    class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                                <!-- <div class="d-flex justify-content-center py-4">
                    <a href="index.html" class="logo d-flex align-items-center w-auto">
                    <img src="assets/img/logo.png" alt="">
                    <span class="d-none d-lg-block">NiceAdmin</span>
                    </a>
                </div> -->

                                <div class="card mb-3">

                                    <div class="card-body">

                                        <div class="pt-4 pb-2">
                                            <h5 class="card-title text-center pb-0 fs-4">Daftar Akun</h5>
                                            <p class="text-center small">Masukkan Data Diri Anda</p>
                                        </div>

                                        <form action="{{ route('registers') }}" method="POST"
                                            class="row g-3 needs-validation" novalidate>
                                            @csrf
                                            <div class="col-12">
                                                <label for="yourName" class="form-label">Nama</label>
                                                <input type="text" name="name" class="form-control" id="yourName"
                                                    required>
                                                <div class="invalid-feedback">Maukkan Nama</div>
                                            </div>

                                            <div class="col-12">
                                                <label for="yourEmail" class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" id="yourEmail"
                                                    required>
                                                <div class="invalid-feedback">Masukkan Email</div>
                                            </div>

                                            <div class="col-12">
                                                <label for="yourPassword" class="form-label">Password</label>
                                                <input type="password" name="password" class="form-control"
                                                    id="yourPassword" required>
                                                <div class="invalid-feedback">Masukkan Password</div>
                                            </div>

                                            <div class="col-12">
                                                <label for="yourjabatan" class="form-label">Jabatan</label>
                                                <input type="text" name="jabatan" class="form-control"
                                                    id="yourjabatan" required>
                                                <div class="invalid-feedback">Masukkan Jabatan</div>
                                            </div>

                                            <div class="col-12">
                                                <label for="yourDepartemen" class="form-label">Departemen</label>
                                                <select name="departemen" class="form-control" id="yourDepartemen"
                                                    required>
                                                    <option value="" disabled selected>Pilih Departemen</option>
                                                    @foreach ($organisasi as $org)
                                                        <option value="{{ $org->nama }}">{{ $org->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">Masukkan Departemen</div>
                                            </div>

                                            <div class="col-12">
                                                <label for="yourAddress" class="form-label">Alamat</label>
                                                <input type="text" name="address" class="form-control"
                                                    id="yourAddress" required>
                                                <div class="invalid-feedback">Masukkan Alamat</div>
                                            </div>

                                            <div class="col-12">
                                                <label for="yourPhone" class="form-label">No Handphone</label>
                                                <input type="text" name="phone" class="form-control" id="yourPhone" required>
                                                <div class="invalid-feedback">Masukkan No Handphone yang valid</div>
                                            </div>


                                            <div class="col-12">
                                                <button class="btn btn-primary w-100" type="submit">Buat
                                                    Akun</button>
                                            </div>
                                            <div class="col-12">
                                                <p class="small mb-0">Sudah Punya Akun? <a href="/">Masuk</a>
                                                </p>
                                            </div>
                                        </form>


                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                </section>

            </div>
        </main><!-- End #main -->

        <script>
            document.getElementById('yourPhone').addEventListener('input', function (e) {
                var value = e.target.value;
                e.target.value = value.replace(/\D/g, ''); // Menghapus semua karakter non-angka
            });
        </script>

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>

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

    </body>

    </html>
