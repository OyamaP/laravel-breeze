<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Products List') }}
            </h2>
            <div>
                <form method="get" action="{{ route('user.items.index') }}">
                    <div class="flex">
                        <div class="ml-4">
                            <span class="block text-sm">表示順</span>
                            <select name="sort" id="sort">
                                <option value="{{ \Constant::SORT_ORDER['recommend'] }}" @if(\Request::get('sort') === \Constant::SORT_ORDER['recommend']) selected @endif>
                                    おすすめ順
                                </option>
                                <option value="{{ \Constant::SORT_ORDER['higherPrice'] }}" @if(\Request::get('sort') === \Constant::SORT_ORDER['higherPrice']) selected @endif>
                                    料金の高い順
                                </option>
                                <option value="{{ \Constant::SORT_ORDER['lowerPrice'] }}" @if(\Request::get('sort') === \Constant::SORT_ORDER['lowerPrice']) selected @endif>
                                    料金の安い順
                                </option>
                                <option value="{{ \Constant::SORT_ORDER['later'] }}" @if(\Request::get('sort') === \Constant::SORT_ORDER['later']) selected @endif>
                                    新しい順
                                </option>
                                <option value="{{ \Constant::SORT_ORDER['older'] }}" @if(\Request::get('sort') === \Constant::SORT_ORDER['older']) selected @endif>
                                    古い順
                                </option>
                            </select>
                        </div>
                        <div class="ml-4">
                            <span class="block text-sm">表示件数</span>
                            <select name="pagination" id="pagination">
                                <option value="20" @if(\Request::get('pagination') === '20') selected  @endif>20件</option>
                                <option value="50" @if(\Request::get('pagination') === '50') selected  @endif>50件</option>
                                <option value="100" @if(\Request::get('pagination') === '100') selected  @endif>100件</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex:w-1/2:sm:w-1/3:md:w-1/4">
                        @foreach ($products as $product)
                        <a href="{{ route('user.items.show', ['item' => $product->id]) }}"
                            class="inline-block border rounded-md p-2 sm:p-4 flex-children:w-1/2:sm:w-1/3:md:w-1/4">
                            <x-thumbnail filename="{{ $product->filename1 ?? '' }}" type="products" />
                            <div class="mt-4">
                                <p class="text-gray-500 text-xs tracking-widest title-font mb-1">{{ $product->category }}</p>
                                <p class="text-gray-900 title-font text-lg font-medium">{{ $product->name }}</p>
                                <p class="mt-1">{{ number_format($product->price) }}<span class="text-sm text-gray-700">円(税込)</span></p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    {{ $products->appends([ 'sort' => \Request::get('sort') , 'pagination' => \Request::get('pagination') ])->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        const selects = document.querySelectorAll('#sort, #pagination');
        Array.from(selects).forEach(function(select) {
            select.addEventListener('change', function() {
                this.form.submit();
            })
        })
    </script>
</x-app-layout>
