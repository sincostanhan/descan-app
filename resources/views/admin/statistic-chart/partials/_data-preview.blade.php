<div class="overflow-x-auto rounded border border-base-200 shadow-sm">
    <table class="table table-zebra table-sm md:table-md w-full">
        <thead class="bg-base-200/50 text-base-content text-sm">
            <tr>
                @foreach($statisticalTableEntry->columns as $col)
                    <th class="whitespace-nowrap">{{ $col }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($statisticalTableEntry->content as $row)
                <tr>
                    @foreach($statisticalTableEntry->columns as $col)
                        <td class="whitespace-nowrap">{{ $row[$col] ?? '-' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($statisticalTableEntry->columns) }}" class="text-center py-8 text-base-content/60">
                        Data tabel masih kosong.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>