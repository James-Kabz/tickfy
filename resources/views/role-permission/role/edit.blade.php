<!-- resources/views/permissions/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="p-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Role') }}
                <a href="{{ url('roles') }}"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded float-right">Back</a>
            </h2>
        </div>
    </x-slot>
    <div class="max-w-6xl mt-2 mx-auto px-4">
        <div class="mt-3 bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-4">
                <form action="{{ url('roles/' . $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Role
                            Name</label>
                        <input id="name" type="text" value="{{ $role->name }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            name="name" required>
                    </div>
                    <button type="submit"
                        class="bg-red-500 mb-4 hover:bg-red-700 text-white font-bold py-2 px-4 rounded float-right">Update
                        Role</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
