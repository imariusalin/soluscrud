<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Server') }}
        </h2>
    </x-slot>
    <div class="py-12 flex justify-center">
        <div class="max-w-3xl w-full bg-white dark:bg-gray-800 shadow-md rounded-lg p-8">
            <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Create Server</h1>
            <form method="POST" action="{{ route('servers.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Server Name</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <input type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" id="password" name="password" required>
                        @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="user" class="block text-sm font-medium text-gray-700 dark:text-gray-300">User ID</label>
                        <select id="user" name="user" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" required>
                            @foreach ($solusUsers['data'] as $user)
                                <option value="{{ $user['id'] }}" {{ old('user') == $user['id'] ? 'selected' : '' }}>{{ $user['email'] }}</option>
                            @endforeach
                        </select>
                        @error('user')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="os" class="block text-sm font-medium text-gray-700 dark:text-gray-300">OS</label>
                        <select id="os" name="os" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" required>
                            @foreach ($osTemplates['data'] as $os)
                                <optgroup label="{{ $os['name'] }}">
                                    @foreach ($os['versions'] as $version)
                                        <option value="{{ $version['id'] }}" {{ old('os') == $version['id'] ? 'selected' : '' }}>
                                            {{ $os['name'] }} {{ $version['version'] }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('os')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="primary_ip" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Primary IP</label>
                        <select id="primary_ip" name="primary_ip" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" required>
                            @foreach ($ipBlocks as $ip)
                                <option value="{{ $ip }}" {{ old('primary_ip') == $ip ? 'selected' : '' }}>{{ $ip }}</option>
                            @endforeach
                        </select>
                        @error('primary_ip')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Create Server</button>
                </div>
                @if ($errors->has('error'))
                    <div class="mt-4 text-red-500 text-sm">
                        {{ $errors->first('error') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-app-layout>
