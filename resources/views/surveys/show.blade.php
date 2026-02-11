<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $survey->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('surveys.edit', $survey) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
                <a href="{{ route('surveys.fill', $survey) }}" class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">Isi Survey</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="font-medium text-gray-800 mb-2">Field</h3>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    @foreach($survey->fields ?? [] as $f)
                        <li>{{ $f['label'] ?? $f['name'] ?? '' }} ({{ $f['type'] ?? 'text' }}) @if(!empty($f['required'])) * @endif</li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-gray-800 mb-4">Response ({{ $survey->responses->count() }})</h3>
                <ul class="divide-y divide-gray-200">
                    @forelse($survey->responses as $resp)
                        <li class="py-3">
                            <span class="text-sm text-gray-500">{{ $resp->created_at->format('d/m/Y H:i') }}</span>
                            @if($resp->lat && $resp->lng)
                                <span class="text-sm text-gray-600"> — Lokasi: {{ $resp->lat }}, {{ $resp->lng }}</span>
                            @endif
                            <div class="mt-1 text-sm text-gray-700">
                                @foreach($resp->answers ?? [] as $key => $val)
                                    <span class="font-medium">{{ $key }}:</span> {{ is_array($val) ? json_encode($val) : $val }} &nbsp;
                                @endforeach
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500 py-4">Belum ada response.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
