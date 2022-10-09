@php
    $cImage = $currentImage ?? '';
    $cId = $currentId ?? '';
@endphp

<div class="modal micromodal-slide" id="modal-{{ $name }}" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-{{ $name }}-title">
            <header class="modal__header">
                <h2 class="text-xl text-gray-700" id="modal-{{ $name }}-title">Plese select file from {{ $name }}</h2>
                <button type="button" class="modal__close" aria-label="Close modal" data-micromodal-close></button>
            </header>
            <main class="modal__content" id="modal-{{ $name }}-content">
                <div class="flex:w-1/2:sm:w-1/3:md:w-1/4">
                    @foreach ($images as $image)
                    <div class="border rounded-md p-2 sm:p-4 flex-children:w-1/2:sm:w-1/3:md:w-1/4">
                        <img class="image" data-id="{{ $name }}_{{ $image->id }}" data-file="{{ $image->filename }}"
                            data-path="{{ asset('storage/products/') }}" data-modal="modal-{{ $name }}" src="{{ asset('storage/products/' . $image->filename) }}">
                        <div class="text-gray-700">{{ $image->title }}</div>
                    </div>
                    @endforeach
                </div>
            </main>
            <footer class="modal__footer">
                <button type="button" class="modal__btn" data-micromodal-close aria-label="Close this dialog window">Close</button>
            </footer>
        </div>
    </div>
</div>

<div class="flex justify-around items-center pb-2 mb-2 border-b-2">
    <button type="button" class="py-2 px-4 bg-gray-200" data-micromodal-trigger="modal-{{ $name }}">Select File</button>
    <div class="w-1/4">
        <img id="{{ $name }}_thumbnail" src="{{ $cImage ? asset('storage/products/' . $cImage) : "" }}">
    </div>
</div>
<input id="{{ $name }}_hidden" type="hidden" name="{{ $name }}" value="{{ $cId }}">
