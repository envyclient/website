@props(['status'])

@if($status === 'pending')
    <span class="badge bg-secondary">pending</span>
@elseif($status === 'denied')
    <span class="badge bg-danger">denied</span>
@elseif($status === 'extended')
    <span class="badge bg-info text-dark">extended</span>
@elseif($status === 'approved')
    <span class="badge bg-success">approved</span>
@endif
