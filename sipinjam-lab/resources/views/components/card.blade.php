@props(['title', 'code' => null, 'state' => 'normal'])
<article {{ $attributes->merge(['class' => 'card'.($state === 'selected' ? ' is-selected' : '').($state === 'unavailable' ? ' is-unavailable' : '')]) }}>
  <div class="card-header">
    <h3 class="card-title">{{ $title }}</h3>
    @if($code)<span class="code-label">{{ $code }}</span>@endif
  </div>
  <div class="card-body">{{ $slot }}</div>
  @isset($footer)<div class="card-footer">{{ $footer }}</div>@endisset
</article>