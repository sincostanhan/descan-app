<?php

namespace App\Http\Controllers\AdminBps;

use App\Actions\CreateStatisticTemplate;
use App\Actions\LogTemplateChange;
use App\Actions\RestoreTemplateHeader;
use App\Actions\UpdateStatisticTemplate;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStatisticTemplateRequest;
use App\Http\Requests\UpdateStatisticTemplateRequest;
use App\Models\StatisticTemplate;
use App\Models\StatisticTemplateLog;
use App\Traits\HasPaginationLimit;
use Illuminate\Http\Request;

class StatisticTemplateController extends Controller
{
    use HasPaginationLimit;

    public function index(Request $request)
    {
        $perPage = $this->getPaginationLimit($request);

        $templates = StatisticTemplate::query()
            ->when($request->get('search'), fn ($q, $search) => $q->where('title', 'like', "%{$search}%"))
            ->withCount('entries')
            ->with(['logs' => fn ($q) => $q->latest()->with('changer')])
            ->latest()
            ->paginate($perPage);

        return view('admin-bps.statistic-templates.index', compact('templates', 'perPage'));
    }

    public function create()
    {
        return view('admin-bps.statistic-templates.create');
    }

    public function store(StoreStatisticTemplateRequest $request, CreateStatisticTemplate $action)
    {
        $action->handle($request->validated());

        return redirect()->route('admin-bps.statistic-templates.index')
            ->with('success', 'Template tabel berhasil dibuat.');
    }

    public function edit(StatisticTemplate $statistic_template)
    {
        // $statistic_template->load(['headers' => fn ($q) => $q->orderBy('order')]);
        // whereNull('village_id'): BPS hanya perlu melihat/mengedit header level TEMPLATE
        // (shared, mode manual + seluruh kolom). Header milik Kelurahan tertentu (hasil generate
        // otomatis mode rt_rw) sengaja TIDAK dimuat di sini — BPS tidak berwenang mengeditnya,
        // dan menampilkannya cuma menambah beban render tanpa guna di halaman ini.
        $statistic_template->load([
            'headers' => fn ($q) => $q->whereNull('village_id')->orderBy('order'),
        ]);

        return view('admin-bps.statistic-templates.edit', compact('statistic_template'));
    }

    public function update(UpdateStatisticTemplateRequest $request, StatisticTemplate $statistic_template, UpdateStatisticTemplate $action)
    {
        $action->handle($statistic_template, $request->validated());

        return redirect()->route('admin-bps.statistic-templates.index')
            ->with('success', 'Template tabel berhasil diperbarui.');
    }

    public function destroy(StatisticTemplate $statistic_template)
    {
        // Soft delete. Jika masih ada Kelurahan yang memakainya, statistic_table_entries.statistic_template_id
        // pakai restrictOnDelete di level DB — tapi karena ini soft delete (bukan hard delete),
        // constraint itu TIDAK akan ke-trigger. Jadi kita cek manual di sini:
        if ($statistic_template->entries()->exists()) {
            return back()->withErrors([
                'template' => 'Template tidak bisa dihapus karena masih dipakai oleh Kelurahan.',
            ]);
        }

        $statistic_template->delete();

        return redirect()->route('admin-bps.statistic-templates.index')
            ->with('success', 'Template tabel berhasil dihapus.');
    }

    // public function restoreLog(StatisticTemplate $statistic_template, StatisticTemplateLog $log, RestoreTemplateHeader $action)
    public function restoreLog(StatisticTemplate $statistic_template, StatisticTemplateLog $log, RestoreTemplateHeader $action, LogTemplateChange $logChange)
    {
        abort_unless($log->statistic_template_id === $statistic_template->id, 404);
        abort_unless($log->canBeRestored(), 422, 'Log ini tidak bisa dipulihkan.');

        // $action->handle($log->affected_header_id);
        $header = $action->handle($log->affected_header_id);
    
        $logChange->handle(
            $statistic_template,
            $header->axis === 'row' ? 'row_restored' : 'column_restored',
            "Memulihkan header {$header->axis} \"{$header->label}\" yang sebelumnya dihapus.",
            $header->id
        );

        return back()->with('success', 'Kolom/baris berhasil dipulihkan.');
    }
}