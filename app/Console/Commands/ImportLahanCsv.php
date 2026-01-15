<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportLahanCsv extends Command
{
    protected $signature = 'import:lahan {source=GEKO}';
    protected $description = 'Import & merge CSV lahan (GEKO / BHL)';

    public function handle()
    {
        $source = strtoupper($this->argument('source'));
        $path = storage_path("app/data/Lahan{$source}.csv");

        if (!file_exists($path)) {
            $this->error('File CSV tidak ditemukan');
            return;
        }

        $file = fopen($path, 'r');
        $header = array_map('trim', fgetcsv($file, 0, ';'));

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($file, 0, ';')) !== false) {
                $data = array_combine($header, $row);

                if ($source === 'BHL') {
                    $lahanNo = $data['kd_lahan'] ?? $data['no_lahan'] ?? null;

                    $mapped = [
                        'lahan_no'      => $lahanNo,
                        'document_no'   => $data['no_dok'] ?? null,
                        'farmer_no'     => $data['kd_petani'] ?? null,
                        'land_area'     => $data['luas_lahan'] ?? null,
                        'planting_area' => $data['luas_tanam'] ?? null,
                        'coordinate'    => $data['koordinat'] ?? null,
                        'latitude'      => $data['lat'] ?? null,
                        'longitude'     => $data['lng'] ?? null,
                    ];
                } else {
                    $lahanNo = $data['lahan_no'] ?? null;

                    $mapped = [
                        'lahan_no'      => $lahanNo,
                        'document_no'   => $data['document_no'] ?? null,
                        'farmer_no'     => $data['farmer_no'] ?? null,
                        'land_area'     => $data['land_area'] ?? null,
                        'planting_area' => $data['planting_area'] ?? null,
                        'coordinate'    => $data['coordinate'] ?? null,
                        'latitude'      => $data['latitude'] ?? null,
                        'longitude'     => $data['longitude'] ?? null,
                        'village'       => $data['village'] ?? null,
                        'kecamatan'     => $data['kecamatan'] ?? null,
                        'city'          => $data['city'] ?? null,
                        'province'      => $data['province'] ?? null,
                        'created_time'  => $this->toDate($data['created_time'] ?? null),
                    ];
                }

                if (!$lahanNo) continue;

                $exists = DB::table('lahans')
                    ->where('lahan_no', $lahanNo)
                    ->exists();

                if (!$exists) {
                    DB::table('lahans')->insert(array_merge($mapped, [
                        'source_data'  => $source,
                        'is_duplicate' => false,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]));
                }

                DB::table('lahan_raw_data')->insert([
                    'lahan_no'    => $lahanNo,
                    'source_data' => $source,
                    'is_duplicate'=> $exists,
                    'raw_payload' => json_encode($this->nullifyArray($data)),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }

            DB::commit();
            fclose($file);
            $this->info("Import {$source} berhasil");
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }

    private function toDate($value)
    {
        if (!$value || $value === '\N') return null;
        $t = strtotime($value);
        return $t ? date('Y-m-d H:i:s', $t) : null;
    }

    private function nullifyArray(array $data)
    {
        return array_map(fn ($v) => ($v === '\N' || $v === '') ? null : $v, $data);
    }
}
