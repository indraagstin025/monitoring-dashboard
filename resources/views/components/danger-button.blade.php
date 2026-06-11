<button {{ $attributes->merge(['type' => 'submit', 'class' => 'md-button md-button-danger']) }}>
    {{ $slot }}
</button>
