<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Tickfy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-blue-600 text-white py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <h1 class="text-2xl font-bold">Tickfy</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="#" class="hover:underline">Home</a></li>
                    <li><a href="{{ route('welcome') }}" class="hover:underline">Events</a></li>
                    <li><a href="#" class="hover:underline">Contact</a></li>
                    <li><a href="{{url('login')}}" class="hover:underline">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Events Section -->
    <main class="py-10">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Upcoming Events</h2>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($events as $event)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        @if ($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}" class="w-full h-48 object-cover">
                        @else
                            <img src="https://via.placeholder.com/300x200" alt="{{ $event->name }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-800">{{ $event->name }}</h3>
                            <p class="text-sm text-gray-600 mt-2">
                                <i class="fas fa-calendar-alt mr-1"></i> {{ $event->date }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $event->location }}
                            </p>
                            <p class="text-gray-700 mt-3">
                                {{ Str::limit($event->description, 100) }}
                            </p>
                            <a href="{{ route('events.show', $event->id) }}" class="inline-block mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                Learn More
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500">No events found.</p>
                @endforelse
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 Tickfy. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
