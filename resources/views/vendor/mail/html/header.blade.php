<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                {{-- <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Adams Academy Logo"> --}}
                <img src="{{asset('assets/img/logo.png')}}" class="logo" alt="CTG Bookshop">
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
