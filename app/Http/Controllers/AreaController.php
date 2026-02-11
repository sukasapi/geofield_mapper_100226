<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
use App\Models\Area;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AreaController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $areas = Area::query()
            ->when($request->user(), fn ($q) => $q->where('user_id', $request->user()->id))
            ->orderBy('name')
            ->get();

        if ($request->wantsJson()) {
            $features = $areas->map(fn (Area $area) => [
                'type' => 'Feature',
                'id' => $area->id,
                'geometry' => $area->boundary,
                'properties' => [
                    'name' => $area->name,
                    'code' => $area->code,
                    'area_ha' => $area->area_ha,
                    'attributes' => $area->attributes,
                ],
            ]);

            return response()->json([
                'type' => 'FeatureCollection',
                'features' => $features,
            ]);
        }

        return view('areas.index', ['areas' => $areas]);
    }

    public function store(StoreAreaRequest $request): RedirectResponse|JsonResponse
    {
        $data = $this->normalizeBoundary($request->validated());
        $area = new Area($data);
        $area->user_id = $request->user()?->id;
        $area->save();
 
        if ($request->wantsJson()) {
            return response()->json($area->load([]), 201);
        }

        return redirect()->route('areas.index')->with('success', __('Area berhasil ditambahkan.'));
    }

    public function show(Request $request, Area $area): JsonResponse
    {
        $this->authorizeView($request, $area);

        $feature = [
            'type' => 'Feature',
            'id' => $area->id,
            'geometry' => $area->boundary,
            'properties' => [
                'name' => $area->name,
                'code' => $area->code,
                'area_ha' => $area->area_ha,
                'attributes' => $area->attributes,
            ],
        ];

        return response()->json($feature);
    }

    public function update(UpdateAreaRequest $request, Area $area): RedirectResponse|JsonResponse
    {
        $this->authorizeView($request, $area);
        $data = $this->normalizeBoundary($request->validated());
        $area->update($data);

        if ($request->wantsJson()) {
            return response()->json($area->fresh());
        }

        return redirect()->route('areas.index')->with('success', __('Area berhasil diperbarui.'));
    }

    public function destroy(Request $request, Area $area): RedirectResponse|JsonResponse
    {
        $this->authorizeView($request, $area);
        $area->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('areas.index')->with('success', __('Area berhasil dihapus.'));
    }

    private function authorizeView(Request $request, Area $area): void
    {
        if ($area->user_id !== null && $request->user()?->id !== $area->user_id) {
            abort(403);
        }
    }

    private function normalizeBoundary(array $data): array
    {
        if (isset($data['boundary']) && is_string($data['boundary'])) {
            $data['boundary'] = json_decode($data['boundary'], true) ?? $data['boundary'];
        }
        return $data;
    }
}
