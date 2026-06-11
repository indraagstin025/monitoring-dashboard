<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('targets.show', $target->id) }}" class="md-icon-button" title="Kembali">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <p class="eyebrow">Konfigurasi Target</p>
                <h2 class="font-black text-2xl" style="color: var(--md-text)">Edit Target: {{ $target->name }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="surface-card p-6 sm:p-8">

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-lg border text-sm" style="background: var(--md-danger-soft); border-color: var(--md-border); color: var(--md-danger)">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('targets.update', $target->id) }}" method="POST">
                    @csrf @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Nama Target</label>
                            <input type="text" name="name" value="{{ old('name', $target->name) }}" required class="monitor-input w-full text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Protocol</label>
                            <select name="protocol" class="monitor-select w-full text-sm">
                                @foreach(['https','http','tcp'] as $proto)
                                    <option value="{{ $proto }}" {{ old('protocol', $target->protocol) === $proto ? 'selected' : '' }}>{{ strtoupper($proto) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Host (Domain / IP)</label>
                            <input type="text" name="host" value="{{ old('host', $target->host) }}" required class="monitor-input w-full text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Port <span class="font-normal">(Opsional)</span></label>
                            <input type="number" name="port" value="{{ old('port', $target->port) }}" class="monitor-input w-full text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Interval Cek <span class="font-normal">(Detik)</span></label>
                            <input type="number" name="check_interval" value="{{ old('check_interval', $target->check_interval) }}" required min="10" class="monitor-input w-full text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Timeout <span class="font-normal">(Detik)</span></label>
                            <input type="number" name="timeout" value="{{ old('timeout', $target->timeout) }}" required min="1" max="30" class="monitor-input w-full text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Alert Threshold</label>
                            <input type="number" name="alert_threshold" value="{{ old('alert_threshold', $target->alert_threshold) }}" min="1" max="10" class="monitor-input w-full text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Grup <span class="font-normal">(Opsional)</span></label>
                            <input type="text" name="group_name" value="{{ old('group_name', $target->group_name) }}" placeholder="Production" class="monitor-input w-full text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="hidden" name="notify_on_recovery" value="0">
                                <input type="checkbox" name="notify_on_recovery" value="1"
                                    {{ old('notify_on_recovery', $target->notify_on_recovery) ? 'checked' : '' }}
                                    class="rounded">
                                <span class="text-sm" style="color: var(--md-text-soft)">Kirim notifikasi saat server kembali online (RECOVERED)</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row sm:items-center gap-3 pt-5 border-t" style="border-color: var(--md-border)">
                        <button type="submit" class="md-button">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('targets.show', $target->id) }}" class="md-button md-button-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
