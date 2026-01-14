<div class="text-center">
    <div class="py-5">
        <h1 class="display-1 {{ $color ?? 'text-muted' }}">{{ $code ?? '' }}</h1>
        <h2 class="mb-3">{{ $title ?? 'Error' }}</h2>
        <p class="lead text-muted">{{ $message ?? '' }}</p>
        <div class="mt-4">
            @if(!empty($primary))
                <a href="{{ $primary['url'] }}" class="btn btn-primary me-2">{{ $primary['label'] }}</a>
            @endif
            @if(!empty($secondary))
                <a href="{{ $secondary['url'] }}" class="btn btn-outline-secondary">{{ $secondary['label'] }}</a>
            @endif
        </div>
    </div>
</div>
