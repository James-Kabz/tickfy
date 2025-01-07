<!-- resources/views/permissions/index.blade.php -->
<x-app-layout>
    <div class="container mt-2 mx-auto px-4">
        <div class="row">
            <div class="col-md-12">
                @if (session('status'))
                    <div class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card mt-3 bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="card-header bg-gray-100 p-4 border-b border-gray-200">
                        <h4 class="text-lg font-semibold">Edit Role
                            <a href="{{ url('roles') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded float-right">Back</a>
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ url('roles/'.$role->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Role Name</label>
                                <input id="name" type="text" 
                                value="{{$role->name}}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="name" required>
                            </div>
                            <div>
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded float-right">Update Role</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
