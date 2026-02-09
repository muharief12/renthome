@if ($record->agreements->isEmpty())
<p class="text-gray-500">Tidak ada perjanjian tersedia.</p>
@else
<div class="space-y-6 prose max-w-none">
    @foreach ($record->agreements as $index => $agreement)
    <div class="p-4 border rounded-lg bg-gray-50">
        <div>
            {{ $index + 1 }}. {{ $agreement->title }}
        </div>
        <div class="prose max-w-none text-sm prose-ul:list-disc prose-ol:list-decimal">
            {!! $agreement->desc !!}
        </div>
        <hr />
        <br />
    </div>
    @endforeach
</div>
@endif