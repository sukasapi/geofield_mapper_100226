<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;
use App\Models\Survey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SurveyController extends Controller
{
    public function index(Request $request): View
    {
        $surveys = Survey::query()
            ->when($request->user(), fn ($q) => $q->where('user_id', $request->user()->id))
            ->orderBy('name')
            ->get();

        return view('surveys.index', ['surveys' => $surveys]);
    }

    public function create(): View
    {
        return view('surveys.create');
    }

    public function store(StoreSurveyRequest $request): RedirectResponse
    {
        $data = $this->normalizeFields($request->validated());
        $survey = new Survey($data);
        $survey->user_id = $request->user()?->id;
        $survey->save();

        return redirect()->route('surveys.index')->with('success', __('Survey berhasil dibuat.'));
    }

    public function show(Request $request, Survey $survey): View
    {
        $this->authorizeSurvey($request, $survey);
        $survey->load('responses');

        return view('surveys.show', ['survey' => $survey]);
    }

    public function edit(Request $request, Survey $survey): View
    {
        $this->authorizeSurvey($request, $survey);

        return view('surveys.edit', ['survey' => $survey]);
    }

    public function update(UpdateSurveyRequest $request, Survey $survey): RedirectResponse
    {
        $this->authorizeSurvey($request, $survey);
        $data = $this->normalizeFields($request->validated());
        $survey->update($data);

        return redirect()->route('surveys.index')->with('success', __('Survey berhasil diperbarui.'));
    }

    public function destroy(Request $request, Survey $survey): RedirectResponse
    {
        $this->authorizeSurvey($request, $survey);
        $survey->delete();

        return redirect()->route('surveys.index')->with('success', __('Survey berhasil dihapus.'));
    }

    private function authorizeSurvey(Request $request, Survey $survey): void
    {
        if ($survey->user_id !== null && $request->user()?->id !== $survey->user_id) {
            abort(403);
        }
    }

    private function normalizeFields(array $data): array
    {
        if (!isset($data['fields']) || !is_array($data['fields'])) {
            return $data;
        }
        foreach ($data['fields'] as $i => $field) {
            if (isset($field['options_text']) && is_string($field['options_text'])) {
                $data['fields'][$i]['options'] = array_values(array_filter(array_map('trim', explode("\n", $field['options_text']))));
                unset($data['fields'][$i]['options_text']);
            }
            if (empty($data['fields'][$i]['name']) && !empty($field['label'])) {
                $data['fields'][$i]['name'] = \Illuminate\Support\Str::slug($field['label']);
            }
        }
        return $data;
    }
}
