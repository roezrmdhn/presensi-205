<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 20px;
        }

        h1 {
            margin: 0;
            padding: 0;
            font-size: 24px;
            text-align: left;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 100px;
            max-height: 100px;
        }

        hr {
            border: 1px solid #000;
        }

        .striped-table {
            width: 100%;
            border-collapse: collapse;
        }

        .striped-table th {
            background-color: #ccc;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .striped-table tbody tr:nth-child(even) {
            background-color: #e0e0e0;
        }

        .striped-table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        .striped-table td,
        .striped-table th {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Cetak Daftar Presensi</h1>
                {{-- <h4>{{ $organisasi->nama }}</h4> --}}
                {{-- <h4>{{ $organisasi->id_organisasi }}</h4> --}}
            </div>
            <div>
                @if ($organisasi->foto)
                    <img src="{{ asset($organisasi->foto) }}" alt="Foto Organisasi">
                @else
                    <p>Foto tidak tersedia.</p>
                @endif
            </div>
        </div>
        <hr size="2">

        <p>
            @if (isset($event_name))
                <p>Event Name : {{ $event_name }}</p>
            @else
                <p>Data tidak ditemukan.</p>
            @endif
        </p>
        <p>
            @if (isset($kode_acak))
                <p>Kode Presensi : {{ $kode_acak->kode_acak }}</p>
            @else
                <p>Data tidak ditemukan.</p>
            @endif
        </p>
        <p>Jumlah Presensi : {{ $countAbsen }} Anggota</p>

        <table class="striped-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Departemen</th>
                    <th>No Handphone</th>
                    <th>Waktu Presensi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->jabatan }}</td>
                        <td>{{ $item->departemen }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->waktu_presensi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

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

    <script type="text/javascript">
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
