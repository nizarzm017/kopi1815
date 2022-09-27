<x-filament::page>
    <x-filament::card>
        <form action="{{ route('laba-rugi') }}" method="POST">
            @csrf
            <div class="space-y-2 mb-2">
                <div class="flex items-center justify-between space-x-2 rtl:space-x-reverse">
                    <label>
                        <span for="dari" class="text-sm font-medium leading-4 text-gray-700 dark:text-gray-300">Dari</span>
                        <sup class="font-medium text-danger-700">*</sup>
                    </label>
                </div>
                <div>
                    <input type="date" name="dari" class="bg-white relative w-50 border py-2 pl-3 rtl:pl-10 rtl:pr-3 text-left cursor-default rounded-lg shadow-sm dark:bg-gray-700 border-gray-300 dark:border-gray-600 opacity-70 dark:text-gray-300" required>
                </div>
            </div>
            <div class="space-y-2 mb-2">
                <div class="flex items-center justify-between space-x-2 rtl:space-x-reverse">
                    <label>
                        <span for="sampai" class="text-sm font-medium leading-4 text-gray-700 dark:text-gray-300">Sampai</span>
                        <sup class="font-medium text-danger-700">*</sup>
                    </label>
                </div>
                <div>
                    <input type="date" name="sampai" class="bg-white relative w-50 border py-2 pl-3 rtl:pl-10 rtl:pr-3 text-left cursor-default rounded-lg shadow-sm dark:bg-gray-700 border-gray-300 dark:border-gray-600 opacity-70 dark:text-gray-300" required>
                </div>
            </div>
            <button class="inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset filament-button dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 filament-page-button-action" type="submit">Cetak</button>
        </form>
    </x-filament::card>
</x-filament::page>
