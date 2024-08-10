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
        h1, h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .striped-table {
        width: 100%;
        border-collapse: collapse;
    }

    .striped-table th {
        background-color: #ccc; /* Warna abu-abu yang lebih gelap untuk header */
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    .striped-table tbody tr:nth-child(even) {
        background-color: #e0e0e0; /* Warna abu-abu yang lebih gelap untuk baris genap */
    }

    .striped-table tbody tr:nth-child(odd) {
        background-color: #f2f2f2; /* Warna abu-abu muda untuk baris ganjil */
    }

    .striped-table td, .striped-table th {
        padding: 8px;
        border: 1px solid #ddd;
    }
    </style>
</head>
<body>
    <br>
    <br>
    <h1>Cetak Daftar Presensi</h1>
    <br>
    <hr size="2">
    <p>Jumlah Presensi : {{ $countAbsen }} Anggota</p>
    <p>@if(isset($kode_acak))
            <p>Kode Presensi : {{ $kode_acak->kode_acak }}</p>
        @else
            <p>Data tidak ditemukan.</p>
        @endif</p>
        <table class="striped-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>jabatan</th>
                    <th>Departemen</th>
                    <th>No Handphone</th>
                    <th>Waktu Presensi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
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

</body>
</html>
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
