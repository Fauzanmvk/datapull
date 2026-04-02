<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Google\Client;
use Google\Service\Sheets;
use Carbon\Carbon;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug-sheet', function () {
    $client = new Client();
    $client->setAuthConfig(storage_path('app/google.json'));
    $client->addScope(Sheets::SPREADSHEETS_READONLY);
    $client->setHttpClient(new \GuzzleHttp\Client([
        'verify' => 'D:/laragon/etc/ssl/cacert.pem'
    ]));
    $service = new Sheets($client);
    $spreadsheetId = '1E3X8vvh_mmUnAvAjNzkjQ5mHtYF4Q1ZDYhwTP5R9lL4';
    $response = $service->spreadsheets_values->get($spreadsheetId, 'Form Responses!A1:ZZ1');
    $headers = $response->getValues()[0] ?? [];
    $result = [];
    foreach ($headers as $index => $header) {
        $result[] = "[$index] $header";
    }
    return response()->json($result);
});

Route::get('/sync-sheet', function () {

    $client = new Client();
    $client->setAuthConfig(storage_path('app/google.json'));
    $client->addScope(Sheets::SPREADSHEETS_READONLY);
    $client->setHttpClient(new \GuzzleHttp\Client([
        'verify' => 'D:/laragon/etc/ssl/cacert.pem'
    ]));

    $service = new Sheets($client);
    $spreadsheetId = '1E3X8vvh_mmUnAvAjNzkjQ5mHtYF4Q1ZDYhwTP5R9lL4';
    $range = 'Form Responses!A2:JZ1000';

    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();

    if (empty($values)) {
        return "Tidak ada data.";
    }

    $cagarBudayaMap = [
        'Masjid Shiratal Mustaqim' => [
            'nama_petugas'             => 2,
            'kegiatan'                 => 3,
            'dokumentasi_kegiatan'     => 4,
            'jumlah_pengunjung'        => 5,
            'wisatawan_nusantara'      => 6,
            'pelajar_mahasiswa'        => 7,
            'wisatawan_mancanegara'    => 8,
            'tamu_dinas'               => 9,
            'dokumentasi_kunjungan'    => 10,
            'temuan_kerusakan'         => 11,
            'deskripsi_kerusakan'      => 12,
            'dokumentasi_kerusakan'    => 13,
        ],
        'Makam La Mohang Daeng Mangkona' => [
            'nama_petugas'             => 14,
            'kegiatan'                 => 15,
            'dokumentasi_kegiatan'     => 16,
            'jumlah_pengunjung'        => 17,
            'wisatawan_nusantara'      => 18,
            'wisatawan_mancanegara'    => 19,
            'pelajar_mahasiswa'        => 20,
            'tamu_dinas'               => 21,
            'dokumentasi_kunjungan'    => 22,
            'temuan_kerusakan'         => 23,
            'deskripsi_kerusakan'      => 24,
            'dokumentasi_kerusakan'    => 25,
        ],
        'Situs Gunung Selendang' => [
            'nama_petugas'             => 26,
            'kegiatan'                 => 27,
            'dokumentasi_kegiatan'     => 28,
            'jumlah_pengunjung'        => 29,
            'wisatawan_mancanegara'    => 30,
            'wisatawan_nusantara'      => 31,
            'pelajar_mahasiswa'        => 32,
            'tamu_dinas'               => 33,
            'dokumentasi_kunjungan'    => 34,
            'temuan_kerusakan'         => 35,
            'deskripsi_kerusakan'      => 36,
            'dokumentasi_kerusakan'    => 37,
        ],
        'Situs Kutai Muara Kaman' => [
            'nama_petugas'             => 38,
            'kegiatan'                 => 39,
            'dokumentasi_kegiatan'     => 40,
            'jumlah_pengunjung'        => 41,
            'dokumentasi_kunjungan'    => 42,
            'temuan_kerusakan'         => 43,
            'deskripsi_kerusakan'      => 44,
            'dokumentasi_kerusakan'    => 45,
            'wisatawan_mancanegara'    => 46,
            'wisatawan_nusantara'      => 47,
            'pelajar_mahasiswa'        => 48,
            'tamu_dinas'               => 49,
        ],
        'Masjid Jami Adji Amir Hasanoeddin' => [
            'nama_petugas'             => 50,
            'kegiatan'                 => 51,
            'dokumentasi_kegiatan'     => 52,
            'jumlah_pengunjung'        => 53,
            'dokumentasi_kunjungan'    => 54,
            'temuan_kerusakan'         => 55,
            'deskripsi_kerusakan'      => 56,
            'dokumentasi_kerusakan'    => 57,
            'wisatawan_mancanegara'    => 58,
            'wisatawan_nusantara'      => 59,
            'pelajar_mahasiswa'        => 60,
            'tamu_dinas'               => 61,
        ],
        'Komplek Makam Raja Kutai Kertanegara' => [
            'nama_petugas'             => 62,
            'kegiatan'                 => 63,
            'dokumentasi_kegiatan'     => 64,
            'jumlah_pengunjung'        => 65,
            'dokumentasi_kunjungan'    => 66,
            'temuan_kerusakan'         => 67,
            'deskripsi_kerusakan'      => 68,
            'dokumentasi_kerusakan'    => 69,
            'wisatawan_mancanegara'    => 70,
            'wisatawan_nusantara'      => 71,
            'pelajar_mahasiswa'        => 72,
            'tamu_dinas'               => 73,
        ],
        'Makam Awang Long Senopati' => [
            'nama_petugas'             => 74,
            'kegiatan'                 => 75,
            'dokumentasi_kegiatan'     => 76,
            'jumlah_pengunjung'        => 77,
            'dokumentasi_kunjungan'    => 78,
            'temuan_kerusakan'         => 79,
            'deskripsi_kerusakan'      => 80,
            'dokumentasi_kerusakan'    => 81,
            'wisatawan_mancanegara'    => 82,
            'wisatawan_nusantara'      => 83,
            'pelajar_mahasiswa'        => 84,
            'tamu_dinas'               => 85,
        ],
        'Makam Aji Dilanggar' => [
            'nama_petugas'             => 86,
            'kegiatan'                 => 87,
            'dokumentasi_kegiatan'     => 88,
            'jumlah_pengunjung'        => 89,
            'dokumentasi_kunjungan'    => 90,
            'temuan_kerusakan'         => 91,
            'deskripsi_kerusakan'      => 92,
            'dokumentasi_kerusakan'    => 93,
            'wisatawan_mancanegara'    => 94,
            'wisatawan_nusantara'      => 95,
            'pelajar_mahasiswa'        => 96,
            'tamu_dinas'               => 97,
        ],
        'Keraton dan Tiang Prasasti Sambaliung' => [
            'nama_petugas'             => 98,
            'kegiatan'                 => 99,
            'dokumentasi_kegiatan'     => 100,
            'jumlah_pengunjung'        => 101,
            'dokumentasi_kunjungan'    => 102,
            'temuan_kerusakan'         => 103,
            'deskripsi_kerusakan'      => 104,
            'dokumentasi_kerusakan'    => 105,
            'wisatawan_mancanegara'    => 106,
            'wisatawan_nusantara'      => 107,
            'pelajar_mahasiswa'        => 108,
            'tamu_dinas'               => 109,
        ],
        'Masjid Kuno Pasir Belengkong' => [
            'nama_petugas'             => 110,
            'kegiatan'                 => 111,
            'dokumentasi_kegiatan'     => 112,
            'jumlah_pengunjung'        => 113,
            'dokumentasi_kunjungan'    => 114,
            'temuan_kerusakan'         => 115,
            'deskripsi_kerusakan'      => 116,
            'dokumentasi_kerusakan'    => 117,
            'wisatawan_mancanegara'    => 118,
            'wisatawan_nusantara'      => 119,
            'pelajar_mahasiswa'        => 120,
            'tamu_dinas'               => 121,
        ],
        'Museum Pasir Belengkong' => [
            'nama_petugas'             => 122,
            'kegiatan'                 => 123,
            'dokumentasi_kegiatan'     => 124,
            'jumlah_pengunjung'        => 125,
            'dokumentasi_kunjungan'    => 126,
            'temuan_kerusakan'         => 127,
            'deskripsi_kerusakan'      => 128,
            'dokumentasi_kerusakan'    => 129,
            'wisatawan_mancanegara'    => 130,
            'wisatawan_nusantara'      => 131,
            'pelajar_mahasiswa'        => 132,
            'tamu_dinas'               => 133,
        ],
        'Makam Raja Pasir Belengkong' => [
            'nama_petugas'             => 134,
            'kegiatan'                 => 135,
            'dokumentasi_kegiatan'     => 136,
            'jumlah_pengunjung'        => 137,
            'dokumentasi_kunjungan'    => 138,
            'temuan_kerusakan'         => 139,
            'deskripsi_kerusakan'      => 140,
            'dokumentasi_kerusakan'    => 141,
            'wisatawan_mancanegara'    => 142,
            'wisatawan_nusantara'      => 143,
            'pelajar_mahasiswa'        => 144,
            'tamu_dinas'               => 145,
        ],
        'Lamin Pepas Eheng' => [
            'nama_petugas'             => 146,
            'kegiatan'                 => 147,
            'dokumentasi_kegiatan'     => 148,
            'jumlah_pengunjung'        => 149,
            'dokumentasi_kunjungan'    => 150,
            'temuan_kerusakan'         => 151,
            'deskripsi_kerusakan'      => 152,
            'dokumentasi_kerusakan'    => 153,
            'wisatawan_mancanegara'    => 154,
            'wisatawan_nusantara'      => 155,
            'pelajar_mahasiswa'        => 156,
            'tamu_dinas'               => 157,
        ],
        'Lamin Mancong' => [
            'nama_petugas'             => 158,
            'kegiatan'                 => 159,
            'dokumentasi_kegiatan'     => 160,
            'jumlah_pengunjung'        => 161,
            'dokumentasi_kunjungan'    => 162,
            'temuan_kerusakan'         => 163,
            'deskripsi_kerusakan'      => 164,
            'dokumentasi_kerusakan'    => 165,
            'wisatawan_mancanegara'    => 166,
            'wisatawan_nusantara'      => 167,
            'pelajar_mahasiswa'        => 168,
            'tamu_dinas'               => 169,
        ],
        'Lamin Tolan' => [
            'nama_petugas'             => 170,
            'kegiatan'                 => 171,
            'dokumentasi_kegiatan'     => 172,
            'jumlah_pengunjung'        => 173,
            'dokumentasi_kunjungan'    => 174,
            'temuan_kerusakan'         => 175,
            'deskripsi_kerusakan'      => 176,
            'dokumentasi_kerusakan'    => 177,
            'wisatawan_mancanegara'    => 178,
            'wisatawan_nusantara'      => 179,
            'pelajar_mahasiswa'        => 180,
            'tamu_dinas'               => 181,
        ],
        'Gua Pindi' => [
            'nama_petugas'             => 182,
            'kegiatan'                 => 183,
            'dokumentasi_kegiatan'     => 184,
            'jumlah_pengunjung'        => 185,
            'dokumentasi_kunjungan'    => 186,
            'temuan_kerusakan'         => 187,
            'deskripsi_kerusakan'      => 188,
            'dokumentasi_kerusakan'    => 189,
            'wisatawan_mancanegara'    => 190,
            'wisatawan_nusantara'      => 191,
            'pelajar_mahasiswa'        => 192,
            'tamu_dinas'               => 193,
        ],
        'Gua Karim' => [
            'nama_petugas'             => 194,
            'kegiatan'                 => 195,
            'dokumentasi_kegiatan'     => 196,
            'jumlah_pengunjung'        => 197,
            'dokumentasi_kunjungan'    => 198,
            'temuan_kerusakan'         => 199,
            'deskripsi_kerusakan'      => 200,
            'dokumentasi_kerusakan'    => 201,
            'wisatawan_mancanegara'    => 202,
            'wisatawan_nusantara'      => 203,
            'pelajar_mahasiswa'        => 204,
            'tamu_dinas'               => 205,
        ],
        'Ceruk Tewet Atas' => [
            'nama_petugas'             => 206,
            'kegiatan'                 => 207,
            'dokumentasi_kegiatan'     => 208,
            'jumlah_pengunjung'        => 209,
            'dokumentasi_kunjungan'    => 210,
            'temuan_kerusakan'         => 211,
            'deskripsi_kerusakan'      => 212,
            'dokumentasi_kerusakan'    => 213,
            'wisatawan_mancanegara'    => 214,
            'wisatawan_nusantara'      => 215,
            'pelajar_mahasiswa'        => 216,
            'tamu_dinas'               => 217,
        ],
        'Situs Batu Raya' => [
            'nama_petugas'             => 218,
            'kegiatan'                 => 219,
            'dokumentasi_kegiatan'     => 220,
            'jumlah_pengunjung'        => 221,
            'dokumentasi_kunjungan'    => 222,
            'temuan_kerusakan'         => 223,
            'deskripsi_kerusakan'      => 224,
            'dokumentasi_kerusakan'    => 225,
            'wisatawan_mancanegara'    => 226,
            'wisatawan_nusantara'      => 227,
            'pelajar_mahasiswa'        => 228,
            'tamu_dinas'               => 229,
        ],
        'Situs Sungai Marang II' => [
            'nama_petugas'             => 242,
            'kegiatan'                 => 243,
            'dokumentasi_kegiatan'     => 244,
            'jumlah_pengunjung'        => 245,
            'dokumentasi_kunjungan'    => 246,
            'temuan_kerusakan'         => 247,
            'deskripsi_kerusakan'      => 248,
            'dokumentasi_kerusakan'    => 249,
            'wisatawan_mancanegara'    => 250,
            'wisatawan_nusantara'      => 251,
            'pelajar_mahasiswa'        => 252,
            'tamu_dinas'               => 253,
        ],
        'Situs Bekas Keraton Bulungan I' => [
            'nama_petugas'             => 254,
            'kegiatan'                 => 255,
            'dokumentasi_kegiatan'     => 256,
            'jumlah_pengunjung'        => 257,
            'dokumentasi_kunjungan'    => 258,
            'temuan_kerusakan'         => 259,
            'deskripsi_kerusakan'      => 260,
            'dokumentasi_kerusakan'    => 261,
            'wisatawan_mancanegara'    => 262,
            'wisatawan_nusantara'      => 263,
            'pelajar_mahasiswa'        => 264,
            'tamu_dinas'               => 265,
        ],
        'Walaya Purupun' => [
            'nama_petugas'             => 266,
            'kegiatan'                 => 267,
            'dokumentasi_kegiatan'     => 268,
            'jumlah_pengunjung'        => 269,
            'dokumentasi_kunjungan'    => 270,
            'temuan_kerusakan'         => 271,
            'deskripsi_kerusakan'      => 272,
            'dokumentasi_kerusakan'    => 273,
            'wisatawan_mancanegara'    => 274,
            'wisatawan_nusantara'      => 275,
            'pelajar_mahasiswa'        => 276,
            'tamu_dinas'               => 277,
        ],
    ];

    $inserted = 0;
    $skipped = 0;

    foreach ($values as $rowIndex => $row) {
        if (empty(array_filter($row))) continue;
        if (empty($row[0])) continue;

        try {
            $submittedAt = Carbon::createFromFormat('d/m/Y H:i:s', trim($row[0]))->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            continue;
        }

        // Get cagar budaya from column 1
        $cagarBudayaSelected = trim($row[1] ?? '');

        // kondisi keamanan shared at cols 278-279
        $kondisiKeamanan = isset($row[278]) && $row[278] !== '' ? $row[278] : null;
        $catatanKeamanan = isset($row[279]) && $row[279] !== '' ? $row[279] : null;

        foreach ($cagarBudayaMap as $namaCagarBudaya => $cols) {
            $get = fn($colIndex) => ($colIndex !== null && isset($row[$colIndex]) && $row[$colIndex] !== '')
                ? $row[$colIndex]
                : null;

            $namaPetugas = $get($cols['nama_petugas']);
            if (empty($namaPetugas)) continue;
            if (str_contains($namaPetugas, 'http')) continue;

            $exists = DB::table('form_responses')
                ->where('submitted_at', $submittedAt)
                ->where('cagar_budaya', $namaCagarBudaya)
                ->where('nama_petugas', $namaPetugas)
                ->exists();

            if ($exists) { $skipped++; continue; }

            DB::table('form_responses')->insert([
                'submitted_at'             => $submittedAt,
                'cagar_budaya'             => $namaCagarBudaya,
                'nama_petugas'             => $namaPetugas,
                'kegiatan'                 => $get($cols['kegiatan']),
                'dokumentasi_kegiatan'     => $get($cols['dokumentasi_kegiatan']),
                'jumlah_pengunjung'        => ($v = $get($cols['jumlah_pengunjung'])) !== null ? (int)$v : null,
                'wisatawan_nusantara'      => ($v = $get($cols['wisatawan_nusantara'])) !== null ? (int)$v : null,
                'pelajar_mahasiswa'        => ($v = $get($cols['pelajar_mahasiswa'])) !== null ? (int)$v : null,
                'wisatawan_mancanegara'    => ($v = $get($cols['wisatawan_mancanegara'])) !== null ? (int)$v : null,
                'tamu_dinas'               => ($v = $get($cols['tamu_dinas'])) !== null ? (int)$v : null,
                'dokumentasi_kunjungan'    => $get($cols['dokumentasi_kunjungan']),
                'temuan_kerusakan'         => $get($cols['temuan_kerusakan']),
                'deskripsi_kerusakan'      => $get($cols['deskripsi_kerusakan']),
                'dokumentasi_kerusakan'    => $get($cols['dokumentasi_kerusakan']),
                'kondisi_keamanan_situs'   => $kondisiKeamanan,
                'catatan_kondisi_keamanan' => $catatanKeamanan,
                'raw_payload'              => json_encode(array_map(fn($i) => $get($i), $cols)),
                'created_at'               => now(),
                'updated_at'               => now(),
            ]);

            $inserted++;
        }
    }

    return "Sync selesai! Inserted: {$inserted}, Skipped (duplicate): {$skipped}";
});
Route::get('/export-responses', function () {
    $query = DB::table('form_responses');
    
    // If specific IDs are passed, filter by them
    if (request()->has('ids')) {
        $ids = explode(',', request()->get('ids'));
        $query->whereIn('id', $ids);
    }
    
    $data = $query->get();
    $filename = 'form_responses_' . now()->format('d-m-Y') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=$filename",
    ];
    
    $callback = function() use ($data) {
        $file = fopen('php://output', 'w');
        fputcsv($file, [
            'Tanggal', 'Cagar Budaya', 'Nama Petugas', 'Kegiatan',
            'Jumlah Pengunjung', 'Wisatawan Nusantara', 'Wisatawan Mancanegara',
            'Pelajar/Mahasiswa', 'Tamu Dinas', 'Temuan Kerusakan',
            'Deskripsi Kerusakan', 'Kondisi Keamanan', 'Catatan Keamanan'
        ]);
        foreach ($data as $row) {
            fputcsv($file, [
                $row->submitted_at, $row->cagar_budaya, $row->nama_petugas,
                $row->kegiatan, $row->jumlah_pengunjung, $row->wisatawan_nusantara,
                $row->wisatawan_mancanegara, $row->pelajar_mahasiswa, $row->tamu_dinas,
                $row->temuan_kerusakan, $row->deskripsi_kerusakan,
                $row->kondisi_keamanan_situs, $row->catatan_kondisi_keamanan
            ]);
        }
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
});