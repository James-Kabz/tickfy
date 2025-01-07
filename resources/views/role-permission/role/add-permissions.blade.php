<x-app-layout>
  <div class="container mt-5">
    <div class="row">
      <div class="col-12">

        @if (session('status'))
          <div class="alert alert-success bg-green-500 text-white p-4 rounded-md shadow-sm">
            {{ session('status') }}
          </div>
        @endif

        <div class="card bg-white shadow-md rounded-md overflow-hidden">
          <div class="card-header px-4 py-5 flex justify-between items-center border-b border-gray-200">
            <h4 class="text-xl font-semibold text-gray-700">
              Role: {{ $role->name }}
            </h4>
            <a href="{{ url('roles') }}" class="text-white bg-red-500 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-sm">Back</a>
          </div>

          <div class="card-body px-4 py-5">

            <form action="{{ url('roles/'.$role->id.'/give-permissions') }}" method="POST">
              @csrf
              @method('PUT')

              <div class="mb-4">
                @error('permission')
                  <span class="text-danger text-red-500">{{ $message }}</span>
                @enderror

                <label for="" class="text-gray-700 font-medium block mb-2">Permissions</label>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                  @foreach ($permissions as $permission)
                    <div class="flex items-center">
                      <input
                       type="checkbox"
                       name="permission[]" 
                       value="{{ $permission->name }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} class="w-4 h-4 mr-3 text-red-600 focus:ring-red-500 rounded-sm">
                      <label class="text-gray-700 font-medium">{{ $permission->name }}</label>
                    </div>
                  @endforeach
                </div>

              </div>

              <div class="mb-3">
                <button type="submit" class="text-white bg-red-500 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-sm">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

</x-app-layout>
