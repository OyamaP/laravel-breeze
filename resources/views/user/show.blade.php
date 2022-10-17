<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="md:flex md:justify-between">
                        <div class="md:w-[calc(50%_-_0.5rem)] mb-4 md:mb-0">
                            <x-swiper :filenames="$filenames" type="products" />
                        </div>
                        <div class="md:w-[calc(50%_-_0.5rem)]">
                            <h2 class="text-sm title-font text-gray-500 tracking-widest">{{ $product->category->name }}</h2>
                            <h1 class="text-gray-900 text-3xl title-font font-medium mb-2">{{ $product->name }}</h1>
                            <p class="leading-relaxed">{{ $product->information }}</p>
                            <div class="flex flex-wrap justify-between items-center mt-4">
                                <p class="title-font font-medium text-2xl text-gray-900 mr-4 mb-2">{{ number_format($product->price) }}<span class="text-sm text-gray-700">円(税込)</span></p>
                                <form method="post" action="{{ route('user.cart.add') }}" class="flex mb-2">
                                    @csrf
                                    <div class="flex items-center">
                                        <span class="mr-3">数量</span>
                                        <select name="quantity" class="rounded border appearance-none border-gray-300 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-base pl-3 pr-10 mr-3">
                                            @for($i = 1; $i <= $quantity; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <button class="flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">カートに入れる</button>
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-400 my-8"></div>
                    <div class="mb-4 text-center">この商品を販売しているショップ</div>
                    <div class="mb-4 text-center">{{ $product->shop->name }}</div>
                    <div class="mb-4 text-center">
                        <x-thumbnail filename="{{ $product->shop->filename ?? '' }}" type="shop" classes="mx-auto w-40 h-40 object-cover rounded-full" />
                    </div>
                    <div class="modal micromodal-slide" id="modal-{{ $product->shop->name }}" aria-hidden="true">
                        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
                            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-{{ $product->shop->name }}-title">
                                <header class="modal__header">
                                    <h2 class="text-xl text-gray-700" id="modal-{{ $product->shop->name }}-title">{{ $product->shop->name }}</h2>
                                    <button type="button" class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                                </header>
                                <main class="modal__content" id="modal-{{ $product->shop->name }}-content">
                                    <p class="break-words">{{ $product->shop->information }}</p>
                                </main>
                                <footer class="modal__footer">
                                    <button type="button" class="modal__btn" data-micromodal-close aria-label="Close this dialog window">Close</button>
                                </footer>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 text-center">
                        <button type="button" class="text-white bg-gray-400 border-0 py-2 px-6 focus:outline-none hover:bg-gray-500 rounded" data-micromodal-trigger="modal-{{ $product->shop->name }}">ショップの詳細を見る</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
