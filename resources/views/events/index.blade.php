<x-app-layout>
    <x-slot name="header">
        <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 justify-between ">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                {{ __('Events') }}
            </h2>
            <form action="{{ route('events.search') }}" method="GET" class="mb-3 lg:mb-0">
                <input type="text" name="find"
                    class="py-2 px-6 text-gray-900 font-bold border rounded-lg w-48 lg:w-64" placeholder="Search by Name">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded-lg">Search</button>
            </form>
            @can('create event')
                <button class="">
                    <a href="{{ route('events.create') }}"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Event</a>
                </button>
            @endcan
        </div>
    </x-slot>
    <style>
        .transition {
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
    </style>

    <div class="max-w-7xl mx-auto mt-2 mb-5  px-4">
        <div class="row">
            <div class="col-md-12">
                @if (session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($events->isEmpty())
                    <div
                        class="flex mt-5 items-center ml-20 justify-center p-6 bg-blue-100 border border-solid border-blue-600 text-blue-700 text-center font-bold rounded-lg shadow-lg">
                        <p class="text-xl">{{ __('No Events found.') }}</p>
                    </div>
                @else
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                        @foreach ($events as $event)
                            <div
                                class="bg-white shadow-md rounded-lg overflow-hidden transition transform hover:bg-gray-300 hover:-translate-y-1">
                                <a href="{{ route('events.show', $event->id) }}" class="flex items-center p-4">
                                    <div class="flex-grow">
                                        @if ($event->image)
                                            <div class="flex justify-center mt-8">
                                                <div class="rounded-xl p-1">
                                                    <img src="{{ asset('storage/' . $event->image) }}" alt="Event Image"
                                                        class="w-96 lg:w-96 lg:h-60 rounded-xl mx-auto">
                                                </div>
                                            </div>
                                        @endif
                                        <span class="uppercase text-sm md:text-lg lg:text-xl text-gray-600">
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('F j, Y g:i A') }}
                                        </span>
                                        <h1 class="text-2xl font-extrabold text-blue-500 text-center">
                                            {{ $event->name }}
                                        </h1>
                                        <hr>
                                        <h1 class="text-xl text-center text-blue-400">{{ $event->location }}</h1>
                                        <div class="grid grid-cols-1 mt-20">
                                            @if ($event->ticketTypes->isNotEmpty())
                                                <ul>
                                                    @foreach ($event->ticketTypes as $ticketType)
                                                        <li class="text-red-500 text-xl">
                                                            {{ $ticketType->name }} - Kshs{{ $ticketType->price }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p>No ticket types available for this event.</p>
                                            @endif
                                        </div>
                                    </div>
                                </a>

                                {{-- Add the button to show tickets purchased for the event --}}
                                @can('view tickets')
                                    <div class="p-4 text-center">
                                        <a href="{{ route('tickets.show', $event->id) }}"
                                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                            View Purchased Tickets
                                        </a>
                                    </div>
                                @endcan
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
