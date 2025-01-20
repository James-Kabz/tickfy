<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Event') }}
            <a href="{{ url('events') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded float-right">Back</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('events.edit', $event->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Copy fields from create.blade.php, but prefill with $event data -->
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700">Name</label>
                            <input type="text" name="name" id="name"
                                class="w-full border-gray-300 rounded-lg" value="{{ $event->name }}">
                            @error('name')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700">Description</label>
                            <textarea type="text" name="description" id="description" class="w-full form-textarea rounded-lg" rows="5">{{ $event->description }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="date" class="block text-gray-700">Date</label>
                            <input type="date" name="date" id="date"
                                class="w-full border-gray-300 rounded-lg" value="{{ $event->date }}">
                            @error('date')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="start_time" class="block text-gray-700">Start Time</label>
                            <input type="datetime-local" name="start_time" id="start_time"
                                class="w-full border-gray-300 rounded-lg" value="{{ $event->start_time }}">
                            @error('start_time')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="end_time" class="block text-gray-700">End Time</label>
                            <input type="datetime-local" name="end_time" id="end_time"
                                class="w-full border-gray-300 rounded-lg" value="{{ $event->end_time }}">
                            @error('end_time')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="location" class="block text-gray-700">Location</label>
                            <input type="text" name="location" id="location"
                                class="w-full border-gray-300 rounded-lg" value="{{ $event->location }}">
                            @error('location')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="image" class="block text-gray-700">Image</label>
                            <input type="file" name="image" id="image"
                                class="w-full border-gray-300 rounded-lg" value="{{ $event->image }}">
                            @if ($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="Event logo"
                                    class="mt-2 w-80 h-80">
                            @endif
                            @error('image')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>


                        <!-- Repeat for other fields like description, date, start_time, end_time, location, and image -->

                        <div class="mb-4">
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update
                                Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
