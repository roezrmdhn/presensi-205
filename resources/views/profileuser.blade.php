<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Users / Profile - NiceAdmin Bootstrap Template</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Jan 29 2024 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Mengubah Password!',
                html: '<p>Password Lama Yang Dimasukan Tidak Sesuai. Silahkan Periksa!</p>',
            });
        </script>
    @endif
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                html: '<p>Berhasil Mengubah Password!</p>',
            });
        </script>
    @endif

    @if (session('successs'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                html: '<p>Berhasil Mengubah Data!</p>',
            });
        </script>
    @endif
    @include('nav');
    <main class="py-5 px-5">

        <section class="section profile">
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-xxl-1 col-md-6 ml-4">
                    <section class="section dashboard">
                        <a href="/user">
                            <div class="card info-card sales-card py-4 px-4">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ">
                                    <i class="ri-home-4-line"></i>
                                </div>
                            </div>
                        </a>

                </div>


                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Overview</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit
                                        Profile</button>
                                </li>


                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-change-password">Ganti Password</button>
                                </li>

                            </ul>
                            <div class="tab-content pt-2">

                                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                    <h5 class="card-title">Profile Details</h5>

                                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                                        <img src="{{ $user->foto }}" alt="Profile" class="rounded-circle">
                                        <h2>{{ $user->name }}</h2>
                                        <h3>{{ $user->email }}</h3>

                                    </div>

                                </div>

                                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                    <!-- Profile Edit Form -->
                                    <form method="post"
                                        action="{{ route('user.update', ['id' => $user->id_anggota]) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <!-- Input untuk foto profil -->
                                        <div class="row mb-3">
                                            <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile
                                                Image</label>
                                            <div class="col-md-8 col-lg-9">
                                                <img src="{{ $user->foto }}" alt="Profile">
                                                <div class="pt-2">
                                                    {{-- <p>{{ $user->foto }}</p> --}}
                                                    <input name="foto" type="file" class="form-control"
                                                        id="foto">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Input untuk nama -->
                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nama</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="name" type="text" class="form-control" id="fullName"
                                                    value="{{ $user->name }}">
                                            </div>
                                        </div>

                                        <!-- Select untuk departemen -->
                                        <div class="row mb-3">
                                            <label for="departemen"
                                                class="col-md-4 col-lg-3 col-form-label">Departemen</label>
                                            <div class="col-md-8 col-lg-9">
                                                <select name="departemen" id="departemen" class="form-select">
                                                    @foreach ($organisasi as $org)
                                                        <option value="{{ $org->id_organisasi }}"
                                                            {{ $user->departemen == $org->nama ? 'selected' : '' }}>
                                                            {{ $org->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Input untuk jabatan -->
                                        <div class="row mb-3">
                                            <label for="jabatan"
                                                class="col-md-4 col-lg-3 col-form-label">Jabatan</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="jabatan" type="text" class="form-control"
                                                    id="jabatan" value="{{ $user->jabatan }}">
                                            </div>
                                        </div>

                                        <!-- Input untuk email -->
                                        <div class="row mb-3">
                                            <label for="email"
                                                class="col-md-4 col-lg-3 col-form-label">Email</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="email" type="email" class="form-control"
                                                    id="email" value="{{ $user->email }}">
                                            </div>
                                        </div>

                                        <!-- Input untuk alamat -->
                                        <div class="row mb-3">
                                            <label for="alamat"
                                                class="col-md-4 col-lg-3 col-form-label">Alamat</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="address" type="text" class="form-control"
                                                    id="alamat" value="{{ $user->address }}">
                                            </div>
                                        </div>

                                        <!-- Input untuk nomor telepon -->
                                        <div class="row mb-3">
                                            <label for="phone"
                                                class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="phone" type="text" class="form-control"
                                                    id="phone" value="{{ $user->phone }}">
                                            </div>
                                        </div>
                                        <!-- Input untuk menyimpan ID user -->
                                        <input type="hidden" name="userId" value="{{ $user->id_user }}">

                                        <!-- Tombol untuk menyimpan perubahan -->
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade pt-3" id="profile-change-password">
                                    <!-- Change Password Form -->
                                    <form method="post" action="{{ route('user.password') }}">
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="currentPassword"
                                                class="col-md-4 col-lg-3 col-form-label">Password Lama</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="currentPassword" type="password" class="form-control"
                                                    id="currentPassword">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Password
                                                Baru</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="newPassword" type="password" class="form-control"
                                                    id="newPassword">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="renewPassword"
                                                class="col-md-4 col-lg-3 col-form-label">Konfirmasi Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="newPassword_confirmation" type="password"
                                                    class="form-control" id="renewPassword">
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Ganti Password</button>
                                        </div>
                                    </form>



                                </div>

                            </div><!-- End Bordered Tabs -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>
