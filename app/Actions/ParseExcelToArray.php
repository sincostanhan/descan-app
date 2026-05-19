<?php

namespace App\Actions;

use Maatwebsite\Excel\Facades\Excel;

class ParseExcelToArray
{
    public function handle($file)
    {
        // Membaca baris pertama pada sheet pertama Excel
        // Cukup gunakan empty anonymous class (new class {})
        $data = Excel::toArray(new class {}, $file);
        
        $sheet = $data[0] ?? [];
        if (empty($sheet)) {
            return ['columns' => [], 'content' => []];
        }

        // Asumsi: Baris pertama di Excel adalah Header/Nama Kolom
        $columns = $sheet[0];
        
        // Asumsi: Baris kedua dan seterusnya adalah Isi Data (termasuk baris penomoran)
        $content = array_slice($sheet, 1);

        // Format content agar berpasangan dengan nama kolomnya (Key-Value)
        $formattedContent = [];
        
        foreach ($content as $rowIndex => $row) {
            
            // --- LOGIKA PAMUNGKAS: DETEKSI PENOMORAN FLEKSIBEL ---
            // Kita perluas pencarian hingga baris ke-5 (index <= 4) untuk mengatasi header tebal
            if ($rowIndex <= 4) {
                $isNumberingRow = true;
                $lastNumber = 0;
                $hasData = false;

                foreach ($row as $cell) {
                    $val = trim((string) $cell);
                    
                    if ($val !== '') {
                        $hasData = true;
                        
                        // 1. Jika ada HURUF (A-Z, a-z), ini PASTI BUKAN baris penomoran (melainkan data/nama)
                        if (preg_match('/[a-zA-Z]/', $val)) {
                            $isNumberingRow = false;
                            break;
                        }

                        // 2. Ekstrak HANYA angkanya saja. Apapun simbolnya (' ( [ - ) akan otomatis hancur.
                        $cleanVal = preg_replace('/[^0-9]/', '', $val);
                        
                        if ($cleanVal !== '') {
                            $currentNumber = (int) $cleanVal;

                            // 3. Logika Cerdas: Angka harus NAIK (1, 2, 3 atau 1, 3, 4 jika ada yg di-merge)
                            // Dan jarak lompatannya tidak boleh lebih dari 5. 
                            // (Jika angkanya 2024 atau 15000, jaraknya pasti > 5, jadi ketahuan itu data asli)
                            if ($currentNumber <= $lastNumber || ($currentNumber - $lastNumber) > 5) {
                                $isNumberingRow = false;
                                break;
                            }

                            $lastNumber = $currentNumber; // Simpan untuk dicek dengan kolom sebelahnya
                        }
                    }
                }

                // Jika terbukti ini adalah baris penomoran
                if ($hasData && $isNumberingRow) {
                    continue; // Hancurkan/Skip baris ini!
                }
            }
            // --------------------------------------------------------------

            // Memproses baris data yang valid
            $rowData = [];
            foreach ($columns as $index => $columnName) {
                if (!empty($columnName)) {
                    $key = trim($columnName); 
                    $rowData[$key] = $row[$index] ?? null;
                }
            }
            
            // Mencegah baris kosong masuk ke database
            if (count(array_filter($rowData)) > 0) {
                $formattedContent[] = $rowData;
            }
        }

        return [
            'columns' => array_values(array_filter($columns)), // Hilangkan kolom kosong
            'content' => $formattedContent
        ];
    }
}
