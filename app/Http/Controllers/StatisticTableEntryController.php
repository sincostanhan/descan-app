<?php

namespace App\Http\Controllers;

use App\Actions\CreateStatisticTableEntry;
use App\Actions\UpdateStatisticTableEntry;
use App\Http\Requests\StoreStatisticTableEntryRequest;
use App\Http\Requests\UpdateStatisticTableEntryRequest;
use App\Models\StatisticTableEntry;
use App\Models\StatisticTemplate;
use App\Traits\HasPaginationLimit;
use Illuminate\Http\Request;

class StatisticTableEntryController extends Controller
{
    use HasPaginationLimit;

    /**
     * Daftar tabel statistik yang SUDAH diisi Kelurahan ini.
     */
    public function index(Request $request)
    {
        $perPage = $this->getPaginationLimit($request);

        $entries = StatisticTableEntry::with('template')
            ->when($request->get('search'), fn ($q, $s) => $q->whereHas('template', fn ($t) => $t->where('title', 'like', "%{$s}%")))
            ->latest()
            ->paginate($perPage);

        return view('admin.statistic-table-entries.index', compact('entries', 'perPage'));
    }

    /**
     * Halaman "Pilih Template" — wajib dilewati sebelum bisa mengisi tabel baru.
     * Hanya menampilkan template aktif yang BELUM pernah diisi kelurahan ini.
     */
    public function selectTemplate()
    {
        $filledTemplateIds = StatisticTableEntry::pluck('statistic_template_id');

        $templates = StatisticTemplate::where('is_active', true)
            ->whereNotIn('id', $filledTemplateIds)
            ->orderBy('title')
            ->get();

        return view('admin.statistic-table-entries.select-template', compact('templates'));
    }

    /**
     * Halaman spreadsheet editor untuk mengisi nilai berdasarkan template terpilih.
     */
    public function create(StatisticTemplate $statistic_template)
    {
        // Eager-load 3 level ke bawah cukup untuk mayoritas kasus header bertingkat.
        // Kalau nanti ada template dengan hierarki >3 level, load ini perlu direkursi manual.
        $statistic_template->load([
            'rowHeaders.children.children',
            'columnHeaders.children.children',
            'cells',
        ]);

        return view('admin.statistic-table-entries.create', [
            'template' => $statistic_template,
        ]);
    }

    public function store(StoreStatisticTableEntryRequest $request, StatisticTemplate $statistic_template, CreateStatisticTableEntry $action)
    {
        $action->handle($statistic_template, $request->validated());

        return redirect()->route('admin.statistic-table-entries.index')
            ->with('success', 'Tabel statistik berhasil disimpan.');
    }

    public function edit(StatisticTableEntry $statistic_table_entry)
    {
        $statistic_table_entry->load([
            'template.rowHeaders.children.children',
            'template.columnHeaders.children.children',
            'template.cells',
            'values',
        ]);

        return view('admin.statistic-table-entries.edit', [
            'entry' => $statistic_table_entry,
        ]);
    }

    public function update(UpdateStatisticTableEntryRequest $request, StatisticTableEntry $statistic_table_entry, UpdateStatisticTableEntry $action)
    {
        $action->handle($statistic_table_entry, $request->validated());

        return redirect()->route('admin.statistic-table-entries.index')
            ->with('success', 'Tabel statistik berhasil diperbarui.');
    }

    public function destroy(StatisticTableEntry $statistic_table_entry)
    {
        $statistic_table_entry->delete();

        return redirect()->route('admin.statistic-table-entries.index')
            ->with('success', 'Tabel statistik berhasil dihapus.');
    }
}