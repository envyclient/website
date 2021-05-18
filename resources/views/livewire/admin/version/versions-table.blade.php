<div>

    <div class="space-y-4">

        {{--Versions Table--}}
        <x-table>
            <x-slot name="head">
                <x-table.heading>Name</x-table.heading>
                <x-table.heading>State</x-table.heading>
                <x-table.heading>Created</x-table.heading>
                <x-table.heading/>
            </x-slot>

            <x-slot name="body">
                @forelse($versions as $version)
                    <x-table.row wire:loading.class.delay="opacity-50" wire:key="row-{{ $version->id }}">

                        <x-table.cell class="flex items-center">
                            {{ $version->name }}
                        </x-table.cell>

                        <x-table.cell>
                            @if($version->beta)
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                    beta
                                </p>
                            @else
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    release
                                </p>
                            @endif
                        </x-table.cell>

                        <x-table.cell>
                            {{ $version->created_at->diffForHumans() }}
                        </x-table.cell>

                        <x-table.cell>
                            <x-button.link class="text-indigo-600 hover:text-indigo-900"
                                           wire:click="edit({{ $version->id }})">
                                Edit
                            </x-button.link>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="5">
                            <div class="flex justify-center items-center space-x-2">
                            <span class="font-medium py-8 text-cool-gray-400 text-xl">
                               No versions...
                            </span>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-table>

        {{--Pagination Links--}}
        {{ $versions->links() }}
    </div>


    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="edit">
            <x-slot name="title">Edit Version</x-slot>

            <x-slot name="content">

                {{--Name Input--}}
                <x-input.group for="editVersion.name" label="Name">
                    <x-input.text wire:model.defer="editVersion.name" id="editVersion.name" required/>
                </x-input.group>

                {{--Beta Input--}}
                <x-input.group for="version.beta" label="Beta" class="mt-4">
                    <x-input.toggle wire:model.defer="editVersion.beta" id="editVersion.beta"/>
                </x-input.group>

                {{--Changelog Input--}}
                <x-input.group for="editVersion.changelog" label="Changelog" class="mt-4">
                    <x-input.easymde wire:model.defer="editVersion.changelog" id="editVersion.changelog"
                                     maxlength="65535"/>
                </x-input.group>

            </x-slot>

            {{--Footer--}}
            <x-slot name="footer">
                <x-button.secondary wire:click="$set('edit', false)">Cancel</x-button.secondary>
                <x-button.primary type="submit" wire:loading.disabled>Save</x-button.primary>
            </x-slot>

        </x-modal.dialog>
    </form>

</div>
