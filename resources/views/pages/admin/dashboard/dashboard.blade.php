@section('title', 'شمارنده')

@section('css')@endsection

<div>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-transparent py-3">
            <h5 class="card-title mb-0 fw-bold">کامپوننت شمارنده</h5>
        </div>
        <div class="card-body">
            <h2>Count: {{ $count }}</h2>
 
            <button wire:click="increment">+</button>
            <button wire:click="decrement">-</button>
        </div>
    </div>
</div>

@section('script')@endsection