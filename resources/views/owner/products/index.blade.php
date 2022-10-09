<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Products List
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 sm:p-4 md:p-6 bg-white border-b border-gray-200">
                    <x-flash-message status="session('status')" />
                    <div class="flex justify-end mb-4">
                        <button onclick="location.href='{{ route('owner.products.create') }}'" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">New Regist</button>
                    </div>
                    <div class="flex:w-1/2:sm:w-1/3:md:w-1/4">
                        @foreach ($shops as $shop)
                            @foreach ($shop->product as $product)
                            <a href="{{ route('owner.products.edit', ['product' => $product->id]) }}"
                                class="inline-block border rounded-md p-2 sm:p-4 flex-children:w-1/2:sm:w-1/3:md:w-1/4">
                                <div class="text-gray-700">{{ $shop->name }}</div>
                                <x-thumbnail filename="{{ $product->imageFirst->filename ?? '' }}" type="products" />
                                <div class="text-gray-700">{{ $product->name }}</div>
                            </a>
                            @endforeach
                        @endforeach
                    </div>
                    {{-- {{ $shops->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
