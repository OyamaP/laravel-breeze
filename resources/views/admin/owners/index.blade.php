<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Owners List
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="md:p-6 bg-white border-b border-gray-200">
                    <section class="text-gray-600 body-font">
                        <div class="container p-2 md:px-5 mx-auto">
                            <x-flash-message status="session('status')" />
                            <div class="flex justify-end mb-4">
                                <button onclick="location.href='{{ route('admin.owners.create') }}'" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">New Regist</button>
                            </div>
                            <div class="lg:w-2/3 pb-2 w-full mx-auto overflow-auto">
                                <table class="table-auto w-full text-left whitespace-no-wrap">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">Name</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Email</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Created_at</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($owners as $owner)
                                            <tr>
                                                <td class="px-4 py-3">{{ $owner->name }}</td>
                                                <td class="px-4 py-3">{{ $owner->email }}</td>
                                                <td class="px-4 py-3">{{ $owner->created_at->diffForHumans() }}</td>
                                                <td class="px-4 py-3">
                                                    <button onclick="location.href='{{ route('admin.owners.edit', ['owner' => $owner->id]) }}'" class="text-white bg-indigo-400 border-0 py-2 px-4 focus:outline-none hover:bg-indigo-500 rounded">Edit</button>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <form id="delete_{{ $owner->id }}" method="post" action="{{ route('admin.owners.destroy', ['owner' => $owner->id]) }}">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="button" onclick="deletePost(this)" data-id="{{ $owner->id }}" class="text-white bg-red-400 border-0 py-2 px-4 focus:outline-none hover:bg-red-500 rounded">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $owners->links() }}
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <script>
        function deletePost(e) {
            'use strict';
            if (confirm('本当に削除してもいいですか?')) {
                document.getElementById('delete_' + e.dataset.id).submit();
            }
        }
    </script>
</x-app-layout>
