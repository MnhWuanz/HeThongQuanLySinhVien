<div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-4">
    <dl class="space-y-2">
        @foreach($data as $key => $value)
            <div class="grid grid-cols-3 gap-4">
                <dt class="font-medium text-gray-700 dark:text-gray-300">
                    {{ ucfirst(str_replace('_', ' ', $key)) }}:
                </dt>
                <dd class="col-span-2 text-gray-900 dark:text-gray-100">
                    @if(is_array($value) || is_object($value))
                        <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    @elseif(is_bool($value))
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $value ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ $value ? 'Có' : 'Không' }}
                        </span>
                    @elseif(is_null($value))
                        <span class="text-gray-400 italic">null</span>
                    @else
                        {{ $value }}
                    @endif
                </dd>
            </div>
        @endforeach
    </dl>
</div>
