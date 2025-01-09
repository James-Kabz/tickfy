<x-app-layout>
    <x-slot name="header">
        <div class="p-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Permissions to Role') }} : {{ $role->name }}
                <a href="{{ url('roles') }}"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded float-right">Back</a>
            </h2>
        </div>
    </x-slot>
    <div class="max-w-7xl mx-auto mt-2  px-4">
        <div class="p-4">
            <div class="bg-white shadow-md rounded-md overflow-hidden">
                <div class="px-4 py-5">
                    <form action="{{ url('roles/' . $role->id . '/give-permissions') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            @error('permission')
                                <span class="text-danger text-red-500">{{ $message }}</span>
                            @enderror

                            <h4 class="text-2xl font-extrabold">Select Permissions</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                                @foreach ($permissions as $permission)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="permission[]" value="{{ $permission->name }}"
                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                            class="w-4 h-4 mr-3 text-red-600 focus:ring-red-500 rounded-sm">
                                        <label class="text-gray-700 font-medium">{{ $permission->name }}</label>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                        <div>
                        </div>
                        <button type="submit"
                            class="text-white mb-4 bg-red-500 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-sm">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
