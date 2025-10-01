@props(['class' => 'h-12 w-auto'])

<img
  src="{{ asset('images/logo-dinsos.png') }}"
  alt="Dinas Sosial"
  {{ $attributes->merge(['class' => $class]) }}
/>
