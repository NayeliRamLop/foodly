@extends('adminlte::page')

@section('title', 'NOTIFICACIONES')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h1 class="m-0" style="font-size: 2.6rem; color: #F28241;">Notificaciones</h1>
        @if($notifications->count())
            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="mt-2 mt-md-0">
                @csrf
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="fas fa-check-double mr-1"></i> Marcar todas como leídas
                </button>
            </form>
        @endif
    </div>
@stop

@section('content')
    <div class="card notifications-card">
        <div class="card-body">
            @forelse($notifications as $notification)
                @php($data = $notification->data)
                <div class="notification-item {{ $notification->read_at ? 'is-read' : 'is-unread' }}">
                    <div>
                        <p class="mb-1 notification-message">{{ $data['message'] ?? 'Tienes una nueva notificación.' }}</p>
                        <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <div class="notification-actions">
                        @if(!empty($data['url']))
                            <a href="{{ $data['url'] }}" class="btn btn-sm btn-outline-primary">Ver</a>
                        @endif
                        @if(!$notification->read_at)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">Marcar leída</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-notifications text-center py-5">
                    <i class="fas fa-bell-slash fa-3x mb-3" style="color: #F28241;"></i>
                    <p class="mb-0">No tienes notificaciones todavía.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $notifications->links() }}
    </div>
@stop

@section('css')
<style>
    .notifications-card {
        border: 0;
        border-radius: 18px;
        box-shadow: 0 14px 30px rgba(0,0,0,0.06);
    }
    .notification-item {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #ececec;
    }
    .notification-item:last-child {
        border-bottom: 0;
    }
    .is-unread .notification-message {
        font-weight: 700;
        color: #4b2e1f;
    }
    .is-read .notification-message {
        color: #6c757d;
    }
    .notification-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    @media (max-width: 768px) {
        .notification-item {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@stop
