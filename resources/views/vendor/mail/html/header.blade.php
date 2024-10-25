@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                <div class="flex justify-center items-center">
                    <span class="logo">
                        {{ config('app.logo') }}
                    </span>
                    <h1 class="text-md font-semibold">
                        {{ config('app.name') }}
                    </h1>
                </div>
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
