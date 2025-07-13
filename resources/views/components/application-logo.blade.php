
 <!-- Logo Image -->
    <img src="{{ asset('favicon.svg') }}" alt="J-CLOTHES Logo" style="height: 50px; width: auto;">
@props(['showText' => true])

<div {{ $attributes->merge(['style' => 'display: flex; align-items: center; gap: 12px;']) }}>
    @if($showText)
    <!-- Text Column - J-CLOTHES above Clothing Store -->
    <div style="display: flex; flex-direction: column; justify-content: center; line-height: 1.1;">
        <!-- J-CLOTHES Text (top) -->
        <div style="color: #8B4513; font-size: 20px; font-weight: bold; font-family: Arial, sans-serif; margin-bottom: 2px;">
            J-Clothes
        </div>
        
        <!-- Clothing Store Text (bottom) -->
        <div style="color: #8B4513; font-size: 14px; font-weight: 500; font-family: Arial, sans-serif;">
            Clothing Store
        </div>
    </div>
    @endif
</div>
