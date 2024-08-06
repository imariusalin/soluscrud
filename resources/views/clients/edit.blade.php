<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Client') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('clients.update', $client['data']['id']) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" name="email" id="email" class="mt-1 block w-full" value="{{ old('email', $client['data']['email']) }}" required>
                                @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                                <input type="password" name="password" id="password" class="mt-1 block w-full">
                                @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="language" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Language</label>
                                <select name="language" id="language_id" class="mt-1 block w-full" required>
                                    @foreach ($languages['data'] as $language)
                                        <option value="{{ $language['id'] }}" {{ old('language', $client['data']['language']['id']) == $language['id'] ? 'selected' : '' }}>
                                            {{ $language['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('language')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full" required>
                                    <option value="active" {{ old('status', $client['data']['status']) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="locked" {{ old('status', $client['data']['status']) === 'locked' ? 'selected' : '' }}>Locked</option>
                                    <option value="suspended" {{ old('status', $client['data']['status']) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Update Client</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
