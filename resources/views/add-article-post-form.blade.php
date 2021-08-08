<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
        {{ __('Bollywood Shaadis - Add Article Form') }}
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('store-form') }}">
            @csrf

            <!-- Email Address -->
            <div class="mt-4">
                <label class="block font-medium text-sm text-gray-700" for="password">
                {{ __('Article Content') }}
                </label>
                <textarea name="body" id="body" required placeholder="Enter Article Content" required class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">{{ old('body') }}</textarea>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-3">
                    {{ __('Save Article') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
