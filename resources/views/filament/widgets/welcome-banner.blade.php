<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col md:flex-row items-center justify-between p-6 bg-linear-to-r from-primary-500 to-primary-600 rounded-xl text-black shadow-sm">
            <div>
                <h1 class="text-2xl font-bold tracking-tight md:text-3xl">
                    សួស្តី, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="mt-2 text-sm text-primary-100 max-w-xl font-medium">
                    សូមស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រងភោជនីយដ្ឋានអន្តរជាតិ។ ថ្ងៃនេះគឺជាឱកាសដ៏ល្អក្នុងការគ្រប់គ្រងការលក់ ពិនិត្យមើលស្តុកវត្ថុធាតុដើម និងបម្រើសេវាកម្មជូនអតិថិជនឱ្យបានល្អបំផុត!
                </p>
            </div>

            <div class="mt-4 md:mt-0 bg-white/10 backdrop-blur-md px-6 py-4 rounded-lg text-center border border-white/20">
                <span class="block text-xs uppercase tracking-wider text-primary-200">ម៉ោង</span>
                <span class="block text-2xl font-black mt-1">{{ now()->format('H:i A') }}</span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
