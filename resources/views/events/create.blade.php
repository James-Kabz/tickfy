<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Event') }}
            <a href="{{ url('events') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded float-right">Back</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700">Name</label>
                            <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-lg" value="{{ old('name') }}">
                            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="w-full border-gray-300 rounded-lg">{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="date" class="block text-gray-700">Date</label>
                            <input type="date" name="date" id="date" class="w-full border-gray-300 rounded-lg" value="{{ old('date') }}">
                            @error('date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="start_time" class="block text-gray-700">Start Time</label>
                            <input type="datetime-local" name="start_time" id="start_time" class="w-full border-gray-300 rounded-lg" value="{{ old('start_time') }}">
                            @error('start_time') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="end_time" class="block text-gray-700">End Time</label>
                            <input type="datetime-local" name="end_time" id="end_time" class="w-full border-gray-300 rounded-lg" value="{{ old('end_time') }}">
                            @error('end_time') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="location" class="block text-gray-700">Location</label>
                            <input type="text" name="location" id="location" class="w-full border-gray-300 rounded-lg" value="{{ old('location') }}">
                            @error('location') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="block text-gray-700">Image</label>
                            <input type="file" name="image" id="image" class="w-full border-gray-300 rounded-lg">
                            @error('image') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
