<!-- Slider main container -->
<div class="swiper">
    <!-- Additional required wrapper -->
    <div class="swiper-wrapper">
        <!-- Slides -->
        @foreach($filenames as $filename)
        <div class="swiper-slide">
            <x-thumbnail :filename="$filename" :type="$type" />
        </div>
        @endforeach
    </div>

    {{-- filenameが複数ない場合はswiperをOFF --}}
    @if(1 < count($filenames))
    <!-- If we need pagination -->
    <div class="swiper-pagination"></div>

    <!-- If we need navigation buttons -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>

    <!-- If we need scrollbar -->
    <div class="swiper-scrollbar"></div>

    @vite(['resources/js/swiper.js'])
    @endif
</div>
