@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (config('app.name') === 'J-Clothes' || trim($slot) === 'J-Clothes')
<img src="https://raw.githubusercontent.com/Ezra-Ben/clothe-scm/main/public/images/j-clothes-logo.png" class="logo" alt="J-Clothes Logo" style="height: 75px; width: auto;">
@elseif (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
