<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SurveyResponseController extends Controller
{
    public function create(Request $request, Survey $survey): View
    {
        $this->authorizeSurvey($request, $survey);

        return view('survey-responses.create', ['survey' => $survey]);
    }

    public function store(Request $request, Survey $survey): RedirectResponse
    {
        $this->authorizeSurvey($request, $survey);

        $validated = $request->validate([
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
            'boundary' => ['nullable', 'string'],
            'answers' => ['required', 'array'],
        ]);

        $answers = $validated['answers'];

        $boundary = null;
        if (!empty($validated['boundary'])) {
            $boundary = json_decode($validated['boundary'], true);
        }

        $response = new SurveyResponse([
            'survey_id' => $survey->id,
            'user_id' => $request->user()?->id,
            'lat' => $validated['lat'] ?? null,
            'lng' => $validated['lng'] ?? null,
            'boundary' => $boundary,
            'answers' => $answers,
        ]);
        $response->save();

        return redirect()->route('surveys.show', $survey)->with('success', __('Response berhasil disimpan.'));
    }

    private function authorizeSurvey(Request $request, Survey $survey): void
    {
        if ($survey->user_id !== null && $request->user()?->id !== $survey->user_id) {
            abort(403);
        }
    }
}
