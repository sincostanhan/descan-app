<?php

namespace App\Http\Controllers;

use App\Actions\ResolveMapBaseGeometries;
use App\Actions\ResolveMapChoroplethData;
use App\Actions\ResolveMapFeatureData;
use App\Models\RegionGeometry;
use App\Models\StatisticTemplate;
use App\Models\StatisticTemplateHeader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicMapDashboardController extends Controller
{
    /**
     * Halaman utama Dashboard Peta Publik — render shell + dropdown pertama (Template).
     */
    public function index()
    {
        $templates = StatisticTemplate::where('is_mapped', true)
            ->where('is_active', true)
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('public.map.index', compact('templates'));
    }

    /**
     * AJAX: daftar kolom (axis=column, leaf) milik template terpilih, untuk dropdown kedua.
     */
    public function columns(StatisticTemplate $statistic_template): JsonResponse
    {
        abort_unless($statistic_template->is_mapped && $statistic_template->is_active, 404);

        $columns = $statistic_template->headers()
            ->where('axis', 'column')
            ->where('is_leaf', true)
            ->orderBy('order')
            ->get(['id', 'label']);

        return response()->json($columns);
    }

    /**
     * AJAX: daftar RT/RW yang PUNYA geometri (bukan dari organizations.daftar_rt),
     * supaya dropdown RT/RW tidak menampilkan wilayah tanpa poligon.
     * RegionGeometry otomatis ter-scope ke kelurahan aktif via trait BelongsToVillage.
     */
    public function rtRwOptions(): JsonResponse
    {
        $options = RegionGeometry::orderBy('rw')->orderBy('rt')->get(['rt', 'rw']);

        return response()->json($options);
    }

    /**
     * AJAX: SELURUH poligon RT/RW TANPA data statistik — lapisan dasar (warna netral)
     * yang tampil sejak halaman dibuka, sebelum Tabel/Kolom dipilih.
     */
    public function baseGeometries(ResolveMapBaseGeometries $action): JsonResponse
    {
        return response()->json($action->handle());
    }

    /**
     * AJAX: data GeoJSON + properti popup untuk 1 kombinasi template + kolom + RT/RW.
     * Dipertahankan untuk kompatibilitas, meski frontend sekarang memakai dataAll().
     */
    public function data(Request $request, ResolveMapFeatureData $action): JsonResponse
    {
        $validated = $request->validate([
            'template_id' => ['required', 'integer', 'exists:statistic_templates,id'],
            'column_id' => ['required', 'integer', 'exists:statistic_template_headers,id'],
            'rt' => ['required', 'string'],
            'rw' => ['required', 'string'],
        ]);

        $template = StatisticTemplate::where('is_mapped', true)
            ->where('is_active', true)
            ->findOrFail($validated['template_id']);

        $columnHeader = StatisticTemplateHeader::where('statistic_template_id', $template->id)
            ->where('axis', 'column')
            ->where('is_leaf', true)
            ->findOrFail($validated['column_id']);

        $feature = $action->handle($template, $columnHeader, $validated['rt'], $validated['rw']);

        if (!$feature) {
            return response()->json(['message' => 'Data tidak ditemukan untuk kombinasi ini.'], 404);
        }

        return response()->json($feature);
    }

    /**
     * AJAX: SELURUH RT/RW (FeatureCollection) untuk 1 kombinasi template + kolom —
     * dipakai render choropleth (semua poligon tampil sekaligus, warna beda sesuai nilai).
     */
    public function dataAll(Request $request, ResolveMapChoroplethData $action): JsonResponse
    {
        $validated = $request->validate([
            'template_id' => ['required', 'integer', 'exists:statistic_templates,id'],
            'column_id' => ['required', 'integer', 'exists:statistic_template_headers,id'],
        ]);

        $template = StatisticTemplate::where('is_mapped', true)
            ->where('is_active', true)
            ->findOrFail($validated['template_id']);

        $columnHeader = StatisticTemplateHeader::where('statistic_template_id', $template->id)
            ->where('axis', 'column')
            ->where('is_leaf', true)
            ->findOrFail($validated['column_id']);

        return response()->json($action->handle($template, $columnHeader));
    }
}