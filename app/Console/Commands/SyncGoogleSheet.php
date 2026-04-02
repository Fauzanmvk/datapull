<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Google\Client;
use Google\Service\Sheets;
use Carbon\Carbon;

class SyncGoogleSheet extends Command
{
    protected $signature = 'sheet:sync';
    protected $description = 'Sync data from Google Sheets to database';

    public function handle()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google.json'));
        $client->addScope(Sheets::SPREADSHEETS_READONLY);
        $client->setHttpClient(new \GuzzleHttp\Client([
            'verify' => 'D:/laragon/etc/ssl/cacert.pem'
        ]));

        $service = new Sheets($client);
        $spreadsheetId = '1E3X8vvh_mmUnAvAjNzkjQ5mHtYF4Q1ZDYhwTP5R9lL4';

        $response = $service->spreadsheets_values->get($spreadsheetId, 'Form Responses!A2:JZ1000');
        $values = $response->getValues();

        if (empty($values)) {
            $this->info('Tidak ada data.');
            return;
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
                'kondisi_keamanan_situs'   => 277,
                'catatan_kondisi_keamanan' => 278,
            ],
            // ... paste the rest of your $cagarBudayaMap here
        ];

        $inserted = 0;
        $skipped = 0;

        foreach ($values as $row) {
            if (empty(array_filter($row))) continue;
            if (empty($row[0])) continue;

            try {
                $submittedAt = Carbon::createFromFormat('d/m/Y H:i:s', trim($row[0]))->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                continue;
            }

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
                    'kondisi_keamanan_situs'   => $get($cols['kondisi_keamanan_situs']),
                    'catatan_kondisi_keamanan' => $get($cols['catatan_kondisi_keamanan']),
                    'raw_payload'              => json_encode(array_map(fn($i) => $get($i), $cols)),
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);

                $inserted++;
            }
        }

        $this->info("Sync selesai! Inserted: {$inserted}, Skipped: {$skipped}");
    }
}