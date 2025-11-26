<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Service - Sistem Perhitungan Gaji</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .content {
            padding: 40px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 1.1em;
        }

        input[type="number"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1.1em;
            transition: all 0.3s;
        }

        input[type="number"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-calculate {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.2em;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            margin-top: 10px;
        }

        .btn-calculate:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .result {
            margin-top: 30px;
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
        }

        .result-item {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 2px solid #e0e0e0;
            font-size: 1.1em;
        }

        .result-item:last-child {
            border-bottom: none;
        }

        .result-item.highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.3em;
            margin-top: 15px;
        }

        .result-label {
            font-weight: 600;
        }

        .result-value {
            font-weight: 700;
            color: #667eea;
        }

        .result-item.highlight .result-value {
            color: white;
        }

        .deduction {
            color: #dc3545;
        }

        .addition {
            color: #28a745;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.8em;
            }
            
            .content {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💼 Payroll Service</h1>
            <p>Sistem Perhitungan Gaji Karyawan</p>
        </div>

        <div class="content">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="baseSalary">Gaji Pokok (Rp)</label>
                    <input type="number" id="baseSalary" name="baseSalary" 
                           placeholder="Masukkan gaji pokok" 
                           value="<?php echo isset($_POST['baseSalary']) ? $_POST['baseSalary'] : ''; ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="performanceRating">Performance Rating (%)</label>
                    <input type="number" id="performanceRating" name="performanceRating" 
                           placeholder="Masukkan persentase performa (contoh: 85)" 
                           step="0.01"
                           value="<?php echo isset($_POST['performanceRating']) ? $_POST['performanceRating'] : ''; ?>" 
                           required>
                </div>

                <button type="submit" class="btn-calculate">🧮 Hitung Gaji</button>
            </form>

            <?php
            class PayrollService {
                // Menghitung gaji bersih setelah pajak 10% dan potongan BPJS 5%
                public function calculateNetSalary($baseSalary) {
                    $pajak = $baseSalary * 0.10;
                    $bpjs = $baseSalary * 0.05;
                    $netSalary = $baseSalary - $pajak - $bpjs;
                    
                    return [
                        'netSalary' => $netSalary,
                        'pajak' => $pajak,
                        'bpjs' => $bpjs
                    ];
                }

                // Memberikan bonus (berdasarkan persentase) pada gaji pokok
                public function calculateBonus($baseSalary, $performanceRating) {
                    $bonus = $baseSalary * ($performanceRating / 100);
                    return $bonus;
                }

                // Format angka ke Rupiah
                public function formatRupiah($angka) {
                    return 'Rp ' . number_format($angka, 0, ',', '.');
                }
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $baseSalary = floatval($_POST['baseSalary']);
                $performanceRating = floatval($_POST['performanceRating']);

                $payroll = new PayrollService();
                
                // Hitung gaji bersih
                $salaryData = $payroll->calculateNetSalary($baseSalary);
                
                // Hitung bonus
                $bonus = $payroll->calculateBonus($baseSalary, $performanceRating);
                
                // Total gaji (gaji bersih + bonus)
                $totalSalary = $salaryData['netSalary'] + $bonus;

                echo '<div class="result">';
                echo '<h2 style="margin-bottom: 20px; color: #667eea;">📊 Hasil Perhitungan</h2>';
                
                echo '<div class="result-item">';
                echo '<span class="result-label">Gaji Pokok</span>';
                echo '<span class="result-value">' . $payroll->formatRupiah($baseSalary) . '</span>';
                echo '</div>';
                
                echo '<div class="result-item">';
                echo '<span class="result-label">Pajak (10%)</span>';
                echo '<span class="result-value deduction">- ' . $payroll->formatRupiah($salaryData['pajak']) . '</span>';
                echo '</div>';
                
                echo '<div class="result-item">';
                echo '<span class="result-label">BPJS (5%)</span>';
                echo '<span class="result-value deduction">- ' . $payroll->formatRupiah($salaryData['bpjs']) . '</span>';
                echo '</div>';
                
                echo '<div class="result-item">';
                echo '<span class="result-label">Gaji Bersih</span>';
                echo '<span class="result-value">' . $payroll->formatRupiah($salaryData['netSalary']) . '</span>';
                echo '</div>';
                
                echo '<div class="result-item">';
                echo '<span class="result-label">Bonus (' . $performanceRating . '%)</span>';
                echo '<span class="result-value addition">+ ' . $payroll->formatRupiah($bonus) . '</span>';
                echo '</div>';
                
                echo '<div class="result-item highlight">';
                echo '<span class="result-label">💰 Total Gaji Diterima</span>';
                echo '<span class="result-value">' . $payroll->formatRupiah($totalSalary) . '</span>';
                echo '</div>';
                
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>