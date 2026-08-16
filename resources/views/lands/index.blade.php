<x-app-layout>
    <x-slot name="header">My Farm</x-slot>

    <div class="space-y-6">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Manage the land plots on your farm.</p>
            <a href="{{ route('lands.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 shadow-sm hover:shadow-md transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Add Land
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @forelse($lands as $land)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-150">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $land->name }}</h3>
                    @if($land->location)
                        <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            {{ $land->location }}
                        </p>
                    @endif
                    @if($land->plantation_year)
                        <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Planted {{ $land->plantation_year }} ({{ date('Y') - $land->plantation_year }} yrs old)
                        </p>
                    @endif
                    <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('lands.edit', $land) }}" class="flex-1 text-center text-sm font-medium text-gray-600 border border-gray-200 rounded-lg py-2 hover:bg-gray-50 transition-colors duration-150">
                            Edit
                        </a>
                        <form action="{{ route('lands.destroy', $land) }}" method="POST" onsubmit="return confirm('Delete this land?')" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button class="w-full text-center text-sm font-medium text-red-600 border border-red-200 rounded-lg py-2 hover:bg-red-50 transition-colors duration-150">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="md:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v18m0-18c-2 2-6 2-8 0m8 0c2 2 6 2 8 0m-8 6c-2 2-6 2-8 0m8 0c2 2 6 2 8 0m-8 6c-2 2-6 2-8 0m8 0c2 2 6 2 8 0" /></svg>
                    <p class="text-gray-500 mb-4">No lands added yet.</p>
                    <a href="{{ route('lands.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors duration-150">
                        Add Your First Land
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>