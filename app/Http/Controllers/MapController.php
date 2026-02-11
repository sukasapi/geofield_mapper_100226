<?php

namespace App\Http\Controllers;

use App\Models\ImportedRecord;
use App\Models\SurveyResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MapController extends Controller
{
    public function index(Request $request): View
    {
        return view('map.index');
    }

    public function importedRecords(Request $request): JsonResponse
    {
        $records = ImportedRecord::query()
            ->with('fieldMapping')
            ->whereHas('fieldMapping', function ($q) use ($request) {
                if ($request->user()) {
                    $q->where('user_id', $request->user()->id);
                }
            })
            ->get();

        $features = $records->map(fn (ImportedRecord $r) => [
            'type' => 'Feature',
            'id' => $r->id,
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [(float) $r->lng, (float) $r->lat],
            ],
            'properties' => array_merge(
                $r->attributes ?? [],
                ['_mapping_name' => $r->fieldMapping?->name ?? '']
            ),
        ]);

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    public function surveyResponses(Request $request): JsonResponse
    {
        $responses = SurveyResponse::query()
            ->with('survey')
            ->whereHas('survey', function ($q) use ($request) {
                if ($request->user()) {
                    $q->where('user_id', $request->user()->id);
                }
            })
            ->get();

        $features = [];
        foreach ($responses as $r) {
            if ($r->lat !== null && $r->lng !== null) {
                $features[] = [
                    'type' => 'Feature',
                    'id' => $r->id,
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [(float) $r->lng, (float) $r->lat],
                    ],
                    'properties' => array_merge(
                        $r->answers ?? [],
                        ['_survey' => $r->survey?->name ?? '']
                    ),
                ];
            } elseif (!empty($r->boundary) && isset($r->boundary['type'], $r->boundary['coordinates'])) {
                $features[] = [
                    'type' => 'Feature',
                    'id' => $r->id,
                    'geometry' => $r->boundary,
                    'properties' => array_merge(
                        $r->answers ?? [],
                        ['_survey' => $r->survey?->name ?? '']
                    ),
                ];
            }
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}
