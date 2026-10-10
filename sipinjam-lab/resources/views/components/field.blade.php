@props(['name', 'label', 'type' => 'text', 'value' => '', 'placeholder' => '', 'help' => null, 'error' => null, 'required' => false])
@php $id = 'field-'.$name; @endphp
<div class="field {{ $error ? 'has-error' : '' }}">
  <label class="field-label" for="{{ $id }}">{{ $label }}@if($required) <span class="field-required">(wajib)</span>@endif</label>
  <input class="field-input" id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ $value }}" placeholder="{{ $placeholder }}" @if($error) aria-invalid="true" aria-describedby="{{ $id }}-msg" @elseif($help) aria-describedby="{{ $id }}-msg" @endif {{ $attributes }}>
  @if($error)
    <p class="field-error-text" id="{{ $id }}-msg">{{ $error }}</p>
  @elseif($help)
    <p class="field-help" id="{{ $id }}-msg">{{ $help }}</p>
  @endif
</div>