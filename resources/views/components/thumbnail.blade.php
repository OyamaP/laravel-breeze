@php
if($type === 'shops') $dir = 'storage/shops/';
if($type === 'products') $dir = 'storage/products/';
$path = empty($filename) ? 'images/no_image.jpg' : $dir . $filename;
@endphp

<img src="{{ asset($path) }}" @if(isset($classes)) class="{{ $classes }}" @endif >
