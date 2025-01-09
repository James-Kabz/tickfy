<x-app-layout>
    <!-- resources/views/users/edit.blade.php -->
<div class="container mx-auto mt-5">
    <div class="flex flex-col">
        <div class="w-full">

            @if ($errors->any())
                <ul class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="bg-gray-100 p-4 border-b border-gray-200">
                    <h4 class="text-lg font-semibold">Edit User
                        <a href="{{ url('users') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded float-right">Back</a>
                    </h4>
                </div>
                <div class="p-4">
                    <form action="{{ url('users/'.$user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-gray-700">Name</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-control mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" />
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700">Email</label>
                            <input type="text" name="email" readonly value="{{ $user->email }}" class="form-control mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" />
                        </div>
                        {{-- <div class="mb-4">
                            <label for="password" class="block text-gray-700">Password</label>
                            <input type="text" name="password" class="form-control mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" />
                            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div> --}}
                        <div class="mb-4">
                            <label for="roles" class="block text-gray-700">Roles</label>
                            <select name="roles[]" class="form-control mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" multiple>
                                <option value="" disabled>Select Role</option>
                                @foreach ($roles as $role)
                                    <option
                                        value="{{ $role }}"
                                        {{ in_array($role, $userRoles) ? 'selected' : '' }}
                                    >
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>
                            @error('roles') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
  
                        <div class="mb-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>