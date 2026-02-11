<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Survey') }}
            </h2>
            <a href="{{ route('surveys.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Buat Survey</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-green-800">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <ul class="divide-y divide-gray-200">
                    @forelse($surveys as $survey)
                        <li class="px-6 py-4 flex justify-between items-center">
                            <div>
                                <span class="font-medium text-gray-800">{{ $survey->name }}</span>
                                <span class="text-gray-500 text-sm ml-2">/{{ $survey->slug }}</span>
                                <span class="block text-sm text-gray-600">{{ $survey->responses->count() }} response</span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('surveys.show', $survey) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Lihat</a>
                                <a href="{{ route('surveys.edit', $survey) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
                                <a href="{{ route('surveys.fill', $survey) }}" class="text-green-600 hover:text-green-800 text-sm">Isi Survey</a>
                                <form action="{{ route('surveys.destroy', $survey) }}" method="POST" class="inline" onsubmit="return confirm('Hapus survey ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="px-6 py-8 text-center text-gray-500">Belum ada survey. Klik "Buat Survey" untuk membuat.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
