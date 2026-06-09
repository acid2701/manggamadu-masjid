@props([
    'type' => 'text', // text, email, password, number, textarea, select, file, toggle
    'name',
    'label' => null,
    'placeholder' => '',
    'value' => null,
    'error' => null,
    'required' => false,
    'options' => [], // for select type: [['value' => '', 'label' => '']]
    'rows' => 3, // for textarea
    'checked' => false, // for checkbox/toggle
])

@php
    $errorClass = $error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-hairline-strong focus:border-brand-green-dark focus:ring-brand-green';
    
    $inputClasses = "w-full bg-canvas text-ink text-body-md rounded-md px-md py-sm border transition-all duration-150 ease-in-out {$errorClass}";
    
    $heightClass = in_array($type, ['text', 'email', 'password', 'number', 'select', 'file']) ? 'h-[44px]' : '';
    $finalClasses = "{$inputClasses} {$heightClass}";
@endphp

<div class="w-full flex flex-col gap-xxs">
    @if($label && $type !== 'toggle')
        <label for="{{ $name }}" class="text-body-sm font-semibold text-charcoal">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    @if($type === 'textarea')
        <textarea 
            id="{{ $name }}" 
            name="{{ $name }}" 
            placeholder="{{ $placeholder }}"
            rows="{{ $rows }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => $finalClasses]) }}
        >{{ $value }}</textarea>
    
    @elseif($type === 'select')
        <select 
            id="{{ $name }}" 
            name="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => $finalClasses]) }}
        >
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach($options as $option)
                <option value="{{ $option['value'] }}" {{ (string) $value === (string) $option['value'] ? 'selected' : '' }}>
                    {{ $option['label'] }}
                </option>
            @endforeach
        </select>
    
    @elseif($type === 'file')
        <input 
            type="file" 
            id="{{ $name }}" 
            name="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full text-body-sm text-steel file:mr-md file:py-xs file:px-md file:rounded-full file:border-0 file:text-body-sm file:font-semibold file:bg-brand-green-soft file:text-brand-green-dark hover:file:bg-brand-green/20 file:cursor-pointer']) }}
        />

    @elseif($type === 'toggle')
        <label for="{{ $name }}" class="inline-flex items-center cursor-pointer select-none">
            <div class="relative">
                <input 
                    type="checkbox" 
                    id="{{ $name }}" 
                    name="{{ $name }}" 
                    class="sr-only peer"
                    {{ $checked ? 'checked' : '' }}
                    {{ $attributes->merge() }}
                />
                <div class="w-10 h-6 bg-hairline-strong rounded-full peer peer-focus:ring-2 peer-focus:ring-brand-green peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-green-dark"></div>
            </div>
            @if($label)
                <span class="ml-sm text-body-sm font-semibold text-charcoal">{{ $label }}</span>
            @endif
        </label>

    @else
        <input 
            type="{{ $type }}" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            placeholder="{{ $placeholder }}"
            value="{{ $value }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => $finalClasses]) }}
        />
    @endif

    @if($error)
        <span class="text-xs text-red-500 mt-[2px] font-semibold">{{ $error }}</span>
    @endif
</div>
