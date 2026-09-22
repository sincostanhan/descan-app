<?php

namespace App\Http\Controllers;

use App\Actions\CreateMetadataStatistik;
use App\Actions\DeleteMetadataStatistik;
use App\Actions\UpdateMetadataStatistik;
use App\Http\Requests\StoreMetadataStatistikRequest;
use App\Http\Requests\UpdateMetadataStatistikRequest;
use App\Models\MetadataStatistik;
use App\Support\FilenameSanitizer;
use App\Traits\HasPaginationLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MetadataStatistikController extends Controller
{
    use HasPaginationLimit;

    // Halaman Publik
    public function indexPublic(Request $request)
    {
        $perPage = $this->getPaginationLimit($request);

        $query = MetadataStatistik::query()
            ->when($request->get('search'), function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            });

        $metadataStatistiks = $query
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return view('metadata-statistik', compact('metadataStatistiks', 'perPage'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $this->getPaginationLimit($request);

        $sortBy = $request->get('sort_by');
        $sortDir = strtolower($request->get('sort_dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        $query = MetadataStatistik::query()
            ->when($request->get('search'), function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            });

        if ($sortBy) {
            $allowedSorts = ['title', 'updated_at'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortDir);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $metadataStatistiks = $query->paginate($perPage);

        return view('admin.metadata-statistik.index', compact('metadataStatistiks', 'perPage'));
    }

    public function create()
    {
        return view('admin.metadata-statistik.create');
    }

    public function store(StoreMetadataStatistikRequest $request, CreateMetadataStatistik $createMetadataStatistik)
    {
        $createMetadataStatistik->handle($request->validated());

        return redirect()->route('admin.metadata-statistik.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(MetadataStatistik $metadata_statistik)
    {
        return view('admin.metadata-statistik.edit', ['metadataStatistik' => $metadata_statistik]);
    }

    public function update(UpdateMetadataStatistikRequest $request, MetadataStatistik $metadata_statistik, UpdateMetadataStatistik $updateMetadataStatistik)
    {
        $updateMetadataStatistik->handle($metadata_statistik, $request->validated());

        return redirect()->route('admin.metadata-statistik.index');
    }

    public function destroy(MetadataStatistik $metadata_statistik, DeleteMetadataStatistik $deleteMetadataStatistik)
    {
        $deleteMetadataStatistik->handle($metadata_statistik);

        return redirect()->route('admin.metadata-statistik.index');
    }

    public function download(MetadataStatistik $metadata_statistik)
    {
        abort_unless(
            $metadata_statistik->file_path && Storage::disk('public')->exists($metadata_statistik->file_path),
            404
        );

        $extension = pathinfo($metadata_statistik->file_path, PATHINFO_EXTENSION);
        // $filename = Str::slug($metadata_statistik->title) . '.' . $extension;
        $filename = FilenameSanitizer::fromTitle($metadata_statistik->title) . '.' . $extension;

        return Storage::disk('public')->download($metadata_statistik->file_path, $filename);
    }
}