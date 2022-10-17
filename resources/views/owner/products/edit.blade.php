<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Product Edit
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    <x-flash-message status="session('status')" />
                    <form id="update" method="post" action="{{ route('owner.products.update', ['product' => $product->id]) }}">
                        @method('put')
                        @csrf
                        <div class="-m-2">
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="name" class="leading-7 text-sm text-gray-600">ProductName ※required</label>
                                    <input type="text" id="name" name="name" value="{{ $product->name }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="information" class="leading-7 text-sm text-gray-600">ProductInformation ※required</label>
                                    <textarea id="information" name="information" rows="10" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $product->information }}</textarea>
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="name" class="leading-7 text-sm text-gray-600">Price ※required</label>
                                    <input type="number" id="price" name="price" value="{{ $product->price }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="sort_order" class="leading-7 text-sm text-gray-600">Sort</label>
                                    <input type="number" id="sort_order" name="sort_order" value="{{ $product->sort_order }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="current_quantity" class="leading-7 text-sm text-gray-600">Current Quantity</label>
                                    <input type="hidden" id="current_quantity" name="current_quantity" value="{{ $quantity }}" required >
                                    <div class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 py-1 px-3 leading-8">{{ $quantity }}</div>
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="flex justify-around">
                                    <div class="flex items-center"><input type="radio" id="type" name="type" value="{{ \Constant::PRODUCT_LIST['add'] }}" class="mr-2" checked>Add</div>
                                    <div class="flex items-center"><input type="radio" id="type" name="type" value="{{ \Constant::PRODUCT_LIST['sub'] }}" class="mr-2">Sub</div>
                                </div>
                             </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="quantity" class="leading-7 text-sm text-gray-600">Quantity ※required</label>
                                    <input type="number" id="quantity" name="quantity" value="0" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    <span class="text-sm">0以上の整数値で入力してください</span>
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="shop_id" class="leading-7 text-sm text-gray-600">Shop</label>
                                    <select id="shop_id" name="shop_id" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        <option hidden value="">Select Shop</option>
                                        @foreach($shops as $shop)
                                            <option value="{{ $shop->id }}" @if($shop->id === $product->shop_id) selected @endif>
                                                {{ $shop->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="category" class="leading-7 text-sm text-gray-600">Category</label>
                                    <select id="category" name="category" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        <option hidden value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <optgroup label="{{ $category->name }}">
                                            @foreach($category->secondary as $secondary)
                                                <option value="{{ $secondary->id }}" @if($secondary->id === $product->secondary_category_id) selected @endif>
                                                    {{ $secondary->name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <x-select-image :images="$images" currentId="{{ $product->image1 }}" currentImage="{{ $product->imageFirst->filename ?? '' }}" name="image1" />
                                <x-select-image :images="$images" currentId="{{ $product->image2 }}" currentImage="{{ $product->imageSecond->filename ?? '' }}" name="image2" />
                                <x-select-image :images="$images" currentId="{{ $product->image3 }}" currentImage="{{ $product->imageThird->filename ?? '' }}" name="image3" />
                                <x-select-image :images="$images" currentId="{{ $product->image4 }}" currentImage="{{ $product->imageFourth->filename ?? '' }}" name="image4" />
                                <x-micromodal-end name="image" />
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="flex justify-around">
                                    <div class="flex items-center"><input type="radio" id="is_selling" name="is_selling" value="1" class="mr-2" @if( $product->is_selling === 1 ) checked @endif>Now on sale</div>
                                    <div class="flex items-center"><input type="radio" id="is_selling" name="is_selling" value="0" class="mr-2" @if( $product->is_selling === 0 ) checked @endif>Stopped</div>
                                </div>
                             </div>
                        </div>
                    </form>
                    <div class="p-2 w-full flex justify-around mt-4">
                        <button type="button" onclick="location.href='{{ route('owner.products.index') }}'" class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">Back</button>
                        <button type="submit" form="update" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">Update</button>
                        <x-delete-button route='owner.products.destroy' param='product' :id='$product->id' method='delete' />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        'use strict'
        document.querySelectorAll('.image').forEach(image => {
            image.addEventListener('click', (e) => {
                const data = e.target.dataset;
                const imageName = data.id.substr(0, 6);
                document.getElementById(imageName + '_thumbnail').src = data.path + '/' + data.file; // サムネイル表示
                document.getElementById(imageName + '_hidden').value = data.id.replace(imageName + '_', ''); // input value にid代入
                MicroModal.close(data.modal); // close modal
            });
        });
    </script>
</x-app-layout>
