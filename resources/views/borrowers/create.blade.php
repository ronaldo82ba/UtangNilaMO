<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Borrower') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('borrowers.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="contact_number" :value="__('Contact Number')" />
                        <x-text-input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full" :value="old('contact_number')" />
                        <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="address" :value="__('Address')" />
                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address')" />
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="notes" :value="__('Notes')" />
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('borrowers.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                        <x-primary-button>{{ __('Save Borrower') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
