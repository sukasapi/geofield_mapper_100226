<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImportRequest;
use App\Http\Requests\UploadImportRequest;
use App\Imports\GenericSheetImport;
use App\Models\FieldMapping;
use App\Models\ImportedRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function index(): View
    {
        return view('imports.index');
    }

    public function upload(UploadImportRequest $request): View|RedirectResponse
    {
        $file = $request->file('file');
        File::ensureDirectoryExists(Storage::disk('imports')->path(''));
        $path = $file->store('', 'imports');
        $fullPath = Storage::disk('imports')->path($path);

        try {
            $data = Excel::toArray(new GenericSheetImport, $fullPath)[0] ?? [];
        } catch (\Throwable $e) {
            Storage::disk('imports')->delete($path);
            return redirect()->route('imports.index')->with('error', 'File tidak dapat dibaca: '.$e->getMessage());
        }

        if (count($data) < 2) {
            Storage::disk('imports')->delete($path);
            return redirect()->route('imports.index')->with('error', 'File harus memiliki header dan minimal satu baris data.');
        }

        $headers = $data[0];
        $previewRows = array_slice($data, 1, 10);

        return view('imports.mapping', [
            'file_path' => $path,
            'headers' => $headers,
            'preview_rows' => $previewRows,
        ]);
    }

    public function store(StoreImportRequest $request): RedirectResponse
    {
        $fullPath = Storage::disk('imports')->path($request->validated('file_path'));
        if (!is_file($fullPath)) {
            return redirect()->route('imports.index')->with('error', 'File tidak ditemukan. Silakan upload ulang.');
        }

        $mapping = new FieldMapping([
            'name' => $request->validated('name'),
            'file_path' => $request->validated('file_path'),
            'lat_column' => $request->validated('lat_column'),
            'lng_column' => $request->validated('lng_column'),
            'address_column' => $request->validated('address_column'),
            'attribute_columns' => $request->validated('attribute_columns') ?? [],
        ]);
        $mapping->user_id = $request->user()?->id;
        $mapping->save();

        try {
            $data = Excel::toArray(new GenericSheetImport, $fullPath)[0] ?? [];
        } catch (\Throwable $e) {
            return redirect()->route('imports.index')->with('error', 'Gagal memproses file: '.$e->getMessage());
        }

        $headers = $data[0];
        $latCol = $request->validated('lat_column');
        $lngCol = $request->validated('lng_column');
        $attrCols = $request->validated('attribute_columns') ?? [];
        $headerIndex = array_flip($headers);

        $count = 0;
        foreach (array_slice($data, 1) as $row) {
            $lat = $this->getColumnValue($row, $headers, $latCol);
            $lng = $this->getColumnValue($row, $headers, $lngCol);
            if ($lat === null || $lng === null) {
                continue;
            }
            $lat = (float) $lat;
            $lng = (float) $lng;
            $attributes = [];
            foreach ($attrCols as $col) {
                $val = $this->getColumnValue($row, $headers, $col);
                if ($val !== null) {
                    $attributes[$col] = $val;
                }
            }
            ImportedRecord::create([
                'field_mapping_id' => $mapping->id,
                'lat' => $lat,
                'lng' => $lng,
                'attributes' => $attributes,
            ]);
            $count++;
        }

        return redirect()->route('map.index')->with('success', "Import berhasil. {$count} record ditambahkan.");
    }

    private function getColumnValue(array $row, array $headers, ?string $column): mixed
    {
        if ($column === null || $column === '') {
            return null;
        }
        $idx = array_search($column, $headers, true);
        if ($idx === false) {
            return null;
        }
        return $row[$idx] ?? null;
    }
}
