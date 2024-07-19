@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
        <img src="https://certification.lionenergy.com/le-logo-horizontal.svg" width="300" style="display:block; margin:0 auto;" alt="Lion Energy">
{{--{{ $slot }}--}}
@endif
</a>
</td>
</tr>
