<?php
/**
 * PAYROLL SERVICE - SISTEM PERHITUNGAN GAJI
 * Program untuk menghitung gaji bersih dengan potongan pajak dan BPJS
 * serta bonus berdasarkan performance rating
 */

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

// Fungsi untuk membaca input dari user
function getInput($prompt) {
    echo $prompt;
    $handle = fopen("php://stdin", "r");
    $input = trim(fgets($handle));
    fclose($handle);
    return $input;
}

// Fungsi untuk mencetak garis pemisah
function printLine($char = "=", $length = 60) {
    echo str_repeat($char, $length) . "\n";
}

// Header Program
function printHeader() {
    system('cls'); // Untuk Windows, gunakan 'clear' untuk Linux/Mac
    printLine("=");
    echo "           PAYROLL SERVICE - SISTEM PERHITUNGAN GAJI\n";
    echo "         Menghitung Gaji Bersih dengan Pajak 10% dan BPJS 5%\n";
    printLine("=");
    echo "\n";
}

// Main Program
printHeader();

// Input data
$baseSalary = floatval(getInput("Masukkan Gaji Pokok (Rp): "));
$performanceRating = floatval(getInput("Masukkan Performance Rating (%): "));

// Validasi input
if ($baseSalary <= 0 || $performanceRating < 0) {
    echo "\n[ERROR] Input tidak valid! Gaji harus > 0 dan Rating >= 0\n";
    exit;
}

// Proses perhitungan
$payroll = new PayrollService();

// Hitung gaji bersih
$salaryData = $payroll->calculateNetSalary($baseSalary);

// Hitung bonus
$bonus = $payroll->calculateBonus($baseSalary, $performanceRating);

// Total gaji (gaji bersih + bonus)
$totalSalary = $salaryData['netSalary'] + $bonus;

// Tampilkan hasil
echo "\n";
printLine("=");
echo "                    HASIL PERHITUNGAN GAJI\n";
printLine("=");
echo "\n";

echo "Gaji Pokok               : " . $payroll->formatRupiah($baseSalary) . "\n";
printLine("-");
echo "Potongan Pajak (10%)     : - " . $payroll->formatRupiah($salaryData['pajak']) . "\n";
echo "Potongan BPJS (5%)       : - " . $payroll->formatRupiah($salaryData['bpjs']) . "\n";
printLine("-");
echo "Gaji Bersih              : " . $payroll->formatRupiah($salaryData['netSalary']) . "\n";
echo "\n";
echo "Bonus (" . $performanceRating . "%)            : + " . $payroll->formatRupiah($bonus) . "\n";
printLine("=");
echo "TOTAL GAJI DITERIMA      : " . $payroll->formatRupiah($totalSalary) . "\n";
printLine("=");

echo "\n";
echo "Terima kasih telah menggunakan Payroll Service!\n";
echo "\n";
?>