<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-flash-message status="session('status')" />
                    @if( 0 < count($products))
                        <div class="flex justify-between mb-4">
                            <div class="text-lg">TotalPrice {{ number_format($totalPrice) }}<span class="text-sm text-gray-700">円(税込)</span></div>
                            <button onclick="location.href='{{ route('user.cart.checkout') }}'" class="flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">Buy</button>
                        </div>
                        @foreach ($products as $product)
                        <div class="flex flex-col md:flex-row justify-between md:items-center mb-2">
                            <div class="md:w-3/12">
                                <x-thumbnail filename="{{ $product->imageFirst->filename ?? '' }}" type="products" />
                            </div>
                            <div class="md:w-3/12 md:pl-2">{{ $product->name }}</div>
                            <div class="">
                                単価{{ number_format($product->price) }}<span class="text-sm text-gray-700">円(税込)</span>
                            </div>
                            <x-delete-button route='user.cart.delete' param='item' :id='$product->id' value='Change'>
                                <select name="quantity" class="rounded border appearance-none border-gray-300 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-base pl-3 pr-10 mr-3">
                                    @for($i = 0; $i <= $product->pivot->quantity; $i++)
                                    <option value="{{ $i }}" @if($i === $product->pivot->quantity) selected @endif >
                                        {{ $i }}@if($i === 0) (削除) @endif
                                    </option>
                                    @endfor
                                </select>
                            </x-delete-button>
                        </div>
                        @endforeach
                    @else
                        カートに商品が入っていません
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
