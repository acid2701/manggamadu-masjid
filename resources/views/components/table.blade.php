@props([
    'headers' => [], // array of header labels
])

<div {{ $attributes->merge(['class' => 'w-full overflow-x-auto bg-canvas rounded-lg border border-hairline shadow-level-1']) }}>
    <table class="w-full text-left border-collapse">
        @if(!empty($headers))
            <thead>
                <tr class="bg-surface border-b border-hairline">
                    @foreach($headers as $header)
                        <th class="px-lg py-md text-micro-uppercase font-bold text-slate tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="divide-y divide-hairline text-body-sm text-ink">
            {{ $slot }}
        </tbody>
    </table>
</div>
