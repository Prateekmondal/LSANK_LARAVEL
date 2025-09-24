@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Your Notifications</h4>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm">
                        <i class="fas fa-check-circle"></i> Mark All as Read
                    </button>
                </form>
            @endif
        </div>
        
        <div class="card-body">
            @if($notifications->count() > 0)
                <div class="list-group">
                    @foreach($notifications as $notification)
                        <a href="{{ $notification->data['link'] ?? '#' }}" 
                           class="list-group-item list-group-item-action {{ $notification->unread() ? 'unread-notification' : '' }}"
                           @if($notification->unread()) onclick="markAsRead('{{ $notification->id }}')" @endif>
                            <div class="d-flex w-100 justify-content-between">
                                <div>
                                    <h5 class="mb-1">
                                        <i class="fas {{ $notification->data['icon'] ?? 'fa-bell' }} me-2"></i>
                                        {{ $notification->data['message'] }}
                                    </h5>
                                    <small class="text-muted">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                @if($notification->unread())
                                    <span class="badge bg-primary rounded-pill">New</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
                
                <div class="mt-3 d-flex justify-content-center">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="alert alert-info mb-0">
                    You have no notifications.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    });
}
</script>
@endpush

@push('styles')
<style>
.unread-notification {
    background-color: #f8f9fa;
    border-left: 4px solid #0d6efd;
}
.notification-item:hover {
    background-color: #f1f1f1;
}
</style>
@endpush