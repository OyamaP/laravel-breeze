<form id="delete_{{ $id }}" method="post" action="{{ route($route, [$param => $id]) }}" class="flex items-center">
    @if($method ?? '' === 'delete')
    @method('delete')
    @endif
    @csrf
    {{ $slot }}
    <button type="button" onclick="deletePost(this)" data-id="{{ $id }}" class="text-white bg-red-400 border-0 py-2 px-4 sm:px-8 focus:outline-none hover:bg-red-500 rounded text-lg">{{ $value ?? 'Delete' }}</button>
</form>
<script>
    function deletePost(e) {
        'use strict';
        if (confirm('本当に削除してもいいですか?')) {
            document.getElementById('delete_' + e.dataset.id).submit();
        }
    }
</script>
