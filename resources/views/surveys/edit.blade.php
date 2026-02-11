<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Survey') }}: {{ $survey->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @php
                    $fieldsData = old('fields', $survey->fields ?? []);
                    foreach ($fieldsData as &$f) {
                        $f['optionsText'] = isset($f['options']) && is_array($f['options']) ? implode("\n", $f['options']) : '';
                    }
                    unset($f);
                @endphp
                <form action="{{ route('surveys.update', $survey) }}" method="POST" x-data="surveyForm(@json($fieldsData))">
                    @csrf
                    @method('PUT')
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Survey *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $survey->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700">Field (pertanyaan)</label>
                            <button type="button" @click="addField()" class="text-sm text-indigo-600 hover:text-indigo-800">+ Tambah field</button>
                        </div>
                        <template x-for="(field, i) in fields" :key="i">
                            <div class="border border-gray-200 rounded-md p-4 mb-3 bg-gray-50">
                                <input type="hidden" :name="'fields[' + i + '][type]'" :value="field.type">
                                <input type="hidden" :name="'fields[' + i + '][name]'" :value="field.name">
                                <input type="hidden" :name="'fields[' + i + '][label]'" :value="field.label">
                                <input type="hidden" :name="'fields[' + i + '][required]'" :value="field.required ? 1 : 0">
                                <input type="hidden" :name="'fields[' + i + '][options_text]'" :value="field.optionsText || ''">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-2">
                                    <input type="text" x-model="field.label" placeholder="Label" class="rounded-md border-gray-300 shadow-sm">
                                    <select x-model="field.type" class="rounded-md border-gray-300 shadow-sm">
                                        <option value="text">Teks</option>
                                        <option value="number">Angka</option>
                                        <option value="textarea">Teks panjang</option>
                                        <option value="select">Pilihan</option>
                                    </select>
                                </div>
                                <div class="flex items-center gap-2 mb-2">
                                    <input type="checkbox" x-model="field.required" :id="'req-edit-' + i">
                                    <label :for="'req-edit-' + i">Wajib diisi</label>
                                </div>
                                <div x-show="field.type === 'select'" class="mt-2">
                                    <label class="block text-xs text-gray-600">Opsi (satu per baris)</label>
                                    <textarea x-model="field.optionsText" placeholder="Opsi 1&#10;Opsi 2" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm"></textarea>
                                </div>
                                <button type="button" @click="removeField(i)" class="mt-2 text-sm text-red-600 hover:text-red-800">Hapus field</button>
                            </div>
                        </template>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('surveys.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Batal</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function surveyForm(initialFields) {
            const defaultField = { type: 'text', label: '', name: '', required: false, optionsText: '' };
            const fields = (initialFields && initialFields.length) ? initialFields.map(f => ({ ...defaultField, ...f })) : [defaultField];
            return {
                fields: fields,
                addField() {
                    this.fields.push({ type: 'text', label: '', name: '', required: false, optionsText: '' });
                },
                removeField(i) {
                    this.fields.splice(i, 1);
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
