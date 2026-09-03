<?php

namespace App\Http\Controllers\AdminBps;

use App\Actions\CreateStatisticTemplate;
use App\Actions\UpdateStatisticTemplate;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStatisticTemplateRequest;
use App\Http\Requests\UpdateStatisticTemplateRequest;
use App\Models\StatisticTemplate;
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
        $statistic_template->load(['headers' => fn ($q) => $q->orderBy('order')]);

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
}