<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Alumni</title>
    <style>
        /* Mengubah warna latar belakang dan font */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h4 {
            text-align: center;
            color: #4CAF50;
        }

        .card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background-color: #f9f9f9;
        }

        .card-body {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-danger {
            background-color: #f44336;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .btn-danger:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manajemen Data Alumni</h1>

        <!-- Formulir Create -->
        <div class="card mb-4">
            <div class="card-body">
                <h4>Tambah Data Alumni</h4>
                <form method="post" action="">
                    <div class="form-group mb-2">
                        <label for="nim">NIM:</label>
                        <input type="text" class="form-control" id="nim" name="nim" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="name">Nama:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="major">Jurusan:</label>
                        <input type="text" class="form-control" id="major" name="major" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="year">Angkatan:</label>
                        <input type="number" class="form-control" id="year" name="year" required>
                    </div>
                    <button type="submit" class="btn" name="add">Tambah Data</button>
                </form>
            </div>
        </div>

        <!-- Data Alumni -->
        <div class="card">
            <div class="card-body">
                <h4>Daftar Alumni</h4>

                <?php
                session_start();
                $file = 'alumni.csv';

                function readAlumniData($file) {
                    $data = [];
                    if (file_exists($file)) {
                        $handle = fopen($file, 'r');
                        while (($row = fgetcsv($handle)) !== false) {
                            $data[] = $row;
                        }
                        fclose($handle);
                    }
                    return $data;
                }

                function writeAlumniData($file, $data) {
                    $handle = fopen($file, 'w');
                    foreach ($data as $row) {
                        fputcsv($handle, $row);
                    }
                    fclose($handle);
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
                    $nim = $_POST['nim'];
                    $name = $_POST['name'];
                    $major = $_POST['major'];
                    $year = $_POST['year'];

                    $data = readAlumniData($file);
                    $data[] = [$nim, $name, $major, $year];

                    writeAlumniData($file, $data);
                    echo "<div class='alert alert-success'>Data berhasil ditambahkan!</div>";
                }

                if (isset($_POST['delete'])) {
                    $index = $_POST['index'];

                    $data = readAlumniData($file);
                    unset($data[$index]);
                    $data = array_values($data);

                    writeAlumniData($file, $data);
                    echo "<div class='alert alert-success'>Data berhasil dihapus!</div>";
                }

                $data = readAlumniData($file);

                if (!empty($data)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Jurusan</th>
                            <th>Angkatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $index => $alumnus): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($alumnus[0]); ?></td>
                            <td><?php echo htmlspecialchars($alumnus[1]); ?></td>
                            <td><?php echo htmlspecialchars($alumnus[2]); ?></td>
                            <td><?php echo htmlspecialchars($alumnus[3]); ?></td>
                            <td>
                                <form method="post" action="" style="display:inline;">
                                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                                    <button type="submit" class="btn btn-danger" name="delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>Tidak ada data untuk ditampilkan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
