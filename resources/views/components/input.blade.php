<div>
    <div class="form-floating mb-3">
        <input {{ $attributes->class(['form-control'])->merge(['type' => 'text']) }} placeholder="{{ $slot }}" required>
        <label for="floatingInput">{{ $slot }}</label>
    </div>
</div>