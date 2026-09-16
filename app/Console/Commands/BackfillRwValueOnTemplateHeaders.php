<?php

namespace App\Console\Commands;

use App\Models\StatisticTemplateHeader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillRwValueOnTemplateHeaders extends Command
{
    protected $signature = 'backfill:rw-value {--dry-run : Tampilkan apa yang akan diubah tanpa menyimpan}';

    protected $description = 'Isi rw_value pada header rt_rw lama (dibuat sebelum kolom rw_value ada), '
        . 'diparse dari kolom key berformat "rt-{rt}-rw-{rw}-village-{village_id}".';

    /**
     * Satu-kali dijalankan setelah migration add_rw_value_to_statistic_template_headers_table.
     * Idempotent — hanya menyentuh baris yang rw_value-nya masih null, aman dijalankan berkali-kali.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        $headers = StatisticTemplateHeader::where('axis', 'row')
            ->whereNull('rw_value')
            ->whereNotNull('key')
            ->get();

        if ($headers->isEmpty()) {
            $this->info('Tidak ada header yang perlu di-backfill.');
            return self::SUCCESS;
        }

        $updated = 0;
        $skipped = 0;

        DB::transaction(function () use ($headers, $isDryRun, &$updated, &$skipped) {
            foreach ($headers as $header) {
                // Format key: rt-{rt}-rw-{rw}-village-{village_id}
                if (!preg_match('/^rt-(.+)-rw-(.+)-village-\d+$/', $header->key, $matches)) {
                    $skipped++;
                    $this->warn("Lewati header #{$header->id} (key tidak cocok pola: {$header->key})");
                    continue;
                }

                $rwValue = $matches[2];

                if ($isDryRun) {
                    $this->line("Header #{$header->id}: rw_value akan diisi '{$rwValue}' (key: {$header->key})");
                } else {
                    $header->update(['rw_value' => $rwValue]);
                }

                $updated++;
            }
        });

        $isDryRun
            ? $this->info("Dry-run selesai. {$updated} header akan diupdate, {$skipped} dilewati.")
            : $this->info("Selesai. {$updated} header berhasil di-backfill, {$skipped} dilewati.");

        return self::SUCCESS;
    }
}