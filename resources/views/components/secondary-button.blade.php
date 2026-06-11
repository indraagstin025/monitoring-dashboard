<button {{ $attributes->merge(['type' => 'button', 'class' => 'md-button md-button-secondary disabled:opacity-40']) }}>
    {{ $slot }}
</button>
