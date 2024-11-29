<div class="p-4">
    <label class="inline-flex items-center cursor-pointer">
        <input type="checkbox" wire:model="isDeskripsiVisible" class="sr-only peer">
        <div
            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
        </div>
        <span class="ms-3 text-sm font-medium">Absen Diluar Kantor</span>
    </label>

    @if ($isDeskripsiVisible)
        <div class="mt-4">
            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea id="deskripsi" wire:model="deskripsi" rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
            <p class="mt-2 text-sm text-gray-500">Berikan penjelasan mengapa Anda absen di luar kantor.</p>
        </div>
    @endif
</div>
