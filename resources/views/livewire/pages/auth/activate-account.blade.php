<div>
    <form wire:submit.prevent="activate">
        <div>
            <x-input-label for="activation_code" :value="__('Activation Code')" />
            <x-text-input wire:model="activation_code" id="activation_code" class="block mt-1 w-full" required />
            <x-input-error :messages="$errors->get('activation_code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Activate Account') }}
            </x-primary-button>
        </div>
    </form>
</div>