<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Shops List
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 md:p-6 bg-white border-b border-gray-200">
                    <x-flash-message status="session('status')" />
                    <div class="flex flex-wrap flex-col sm:flex-row sm:justify-between">
                        @foreach ($shops as $shop)
                        <a href="{{ route('owner.shops.edit', ['shop' => $shop->id]) }}"
                            class="inline-block w-full sm:w-[calc(50%_-_0.5rem)] border rounded-md p-4
                                [&:nth-child(n+2)]:mt-4 sm:[&:nth-child(-n+2)]:mt-0 sm:[&:nth-child(n+3)]:mt-4">
                            @if($shop->is_selling)
                                <span class="inline-block border p-2 rounded-md mb-2 bg-blue-400 text-white">Now on sale</span>
                            @else
                                <span class="inline-block border p-2 rounded-md mb-2 bg-red-400 text-white">Stopped</span>
                            @endif
                            <div class="text-xl">{{ $shop->name }}</div>
                            <x-thumbnail :filename="$shop->filename" type="shops" />
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
