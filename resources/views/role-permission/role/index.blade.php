<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles') }}
        </h2>
    </x-slot>
    <div class="container max-w-7xl mt-2 mx-auto px-4">
        <div class="flex flex-col">
            <div class="w-full">
                @if (session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card mt-3 bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="card-header bg-gray-100 p-4 border-b border-gray-200 flex justify-between items-center">
                        <h4 class="text-lg font-semibold">Manage Roles</h4>
                        <a href="{{ url('roles/create') }}"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-add"></i> Add Role
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $role->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                                                <a href="{{ url('roles/' . $role->id . '/give-permissions') }}"
                                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                                    Add/Edit Role Permission
                                                </a>
                                                {{-- @can('edit role') --}}
                                                <a href="{{ url('roles/' . $role->id . '/edit') }}"
                                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                {{-- @endcan --}}

                                                {{-- @can('delete role') --}}
                                                <a href="{{ url('roles/' . $role->id . '/delete') }}"
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                {{-- @endcan --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
