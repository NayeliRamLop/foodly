@extends('adminlte::page')

@section('title', 'Notificaciones')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="m-0">Notificaciones</h1>
        @if($notifications->where('read_at', null)->count())
            <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-check-double mr-1"></i> Marcar todas como leídas
                </button>
            </form>
        @endif
    </div>
@stop

@section('content')
<div class="container-fluid px-0">
    @if($notifications->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="fas fa-bell-slash fa-3x mb-3"></i>
                <p class="mb-0">No tienes notificaciones todavía.</p>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($notifications as $notification)
                        @php
                            $data    = $notification->data;
                            $message = $data['message'] ?? 'Nueva notificación';
                            $url     = $data['url'] ?? null;
                            $isRead  = !is_null($notification->read_at);
                            $icon    = match($data['type'] ?? '') {
                                'recipe_like'    => 'fa-heart text-danger',
                                'recipe_comment' => 'fa-comment text-warning',
                                'follow'         => 'fa-user-plus text-success',
                                'profile_visit'  => 'fa-eye text-info',
                                default          => 'fa-bell text-secondary',
                            };
                        @endphp
                        <li class="list-group-item list-group-item-action {{ $isRead ? '' : 'list-group-item-light font-weight-bold' }}"
                            style="{{ $isRead ? '' : 'border-left: 4px solid #F28241;' }}">
                            <div class="d-flex align-items-start gap-3">
                                <div class="pt-1">
                                    <i class="fas {{ $icon }} fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div>{{ $message }}</div>
                                    <div class="text-muted small mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    @if($url)
                                        <a href="{{ route('notifications.open', $notification->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @endif
                                    @if(!$isRead)
                                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Marcar como leída">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@stop

@section('css')
<style>
    .list-group-item {
        padding: 1rem 1.25rem;
    }
    .gap-2 { gap: 0.5rem; }
    .gap-3 { gap: 0.75rem; }
</style>
@stop