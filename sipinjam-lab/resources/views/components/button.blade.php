@props(['variant' => 'primary', 'size' => 'md', 'href' => null, 'type' => 'button', 'loading' => false])
@php
  $class = 'btn btn-'.$variant.($size === 'sm' ? ' btn-sm' : '').($loading ? ' is-loading' : '');
@endphp
@if($href)
  <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</a>
@else
  <button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }} @if($loading) aria-busy="true" @endif>{{ $slot }}</button>
@endif