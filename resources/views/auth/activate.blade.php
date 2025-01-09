<x-guest-layout>
    <div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Activate Your Account</h1>

        @if (session('error'))
            <p class="text-red-500">{{ session('error') }}</p>
        @endif

        @if ($errors->any())
            <div class="mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-red-500">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('set-password', $token) }}">
            @csrf
            <div class="mb-4">
                <!-- Password Requirements Box -->
                <div class="bg-red-100 border border-red-400 text-gray-900 px-4 py-3 rounded mb-4">
                    <h3 class="font-semibold">Your password must contain the following:</h3>
                    <ul class="list-disc list-inside">
                        <li>Upper & Lowercase letters</li>
                        <li>Numbers</li>
                        <li>Special Characters</li>
                    </ul>
                </div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                    Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
            </div>
            <!-- Show Password Checkbox -->
            <div class="mt-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" id="showPasswords" class="form-checkbox h-5 w-5 text-red-600">
                    <span class="ml-2 text-gray-700">Show Password</span>
                </label>
            </div>
            <div>
                <button type="submit"
                    class="w-full bg-red-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Set Password
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('showPasswords').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('password_confirmation');
            const type = this.checked ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            confirmPasswordField.setAttribute('type', type);
        });
    </script>
</x-guest-layout>
