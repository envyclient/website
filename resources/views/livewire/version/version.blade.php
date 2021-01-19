<tr>
    <th scope="row">{{ $index }}</th>
    <td>
        @if($editMode)
            <input type="text" class="form-control" wire:model.defer="version.name">
        @else
            {{ $version->name }}
        @endif
    </td>
    <td>
        @if($editMode)
            <div class="form-check form-switch">
                <input class="form-check-input"
                       type="checkbox"
                       wire:model.lazy="version.beta">
            </div>
        @else
            @if($version->beta)
                &#10004;
            @else
                &#10006;
            @endif
        @endif
    </td>
    <td>{{ $version->version }}</td>
    <td>{{ $version->assets }}</td>
    <td>{{ $version->created_at->diffForHumans() }}</td>
    <td>
        @if($editMode)
            <button class="btn btn-success"
                    onclick="confirm('Confirm Save?') || event.stopImmediatePropagation()"
                    wire:click="save">
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="currentColor"
                          d="M15,9H5V5H15M12,19A3,3 0 0,1 9,16A3,3 0 0,1 12,13A3,3 0 0,1 15,16A3,3 0 0,1 12,19M17,3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V7L17,3Z"/>
                </svg>
            </button>
        @else
            <button class="btn btn-primary" wire:click="$set('editMode', true)">
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="currentColor"
                          d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"/>
                </svg>
            </button>
            <button class="btn btn-danger"
                    onclick="confirm('Confirm Delete?') || event.stopImmediatePropagation()"
                    wire:click="delete">
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="currentColor"
                          d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/>
                </svg>
            </button>
        @endif
    </td>
</tr>
