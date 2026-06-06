<tr data-id="{{ $tech->id }}">
    <td>
        <div class="d-flex align-items-center gap-3">
            <div class="avatar avatar-sm">
                @if($tech->avatar)
                    <img src="{{ asset('storage/' . $tech->avatar) }}" class="rounded-circle" style="width:32px;height:32px;object-fit:cover">
                @else
                    <span class="avatar-initial rounded-circle bg-label-info">{{ mb_substr($tech->name, 0, 1) }}</span>
                @endif
            </div>
            <div>
                <div class="fw-bold">{{ $tech->name }}</div>
                @if($tech->avg_rating > 0)
                    <div class="small">
                        <i class="ti tabler-star-filled text-warning" style="font-size:0.7rem"></i>
                        {{ number_format($tech->avg_rating, 1) }}
                    </div>
                @endif
            </div>
        </div>
    </td>
    <td>
        <span class="badge bg-label-warning">{{ $tech->center?->name ?? '—' }}</span>
    </td>
    <td>
        <a href="tel:{{ $tech->phone }}" class="text-body d-flex align-items-center gap-1">
            <i class="ti tabler-phone text-muted small"></i> {{ $tech->phone }}
        </a>
    </td>
    <td>
        @if($tech->specialties)
            <div class="d-flex flex-wrap gap-1">
                @foreach(array_slice($tech->specialties ?? [], 0, 2) as $sp)
                    <span class="badge bg-label-primary small">{{ $sp }}</span>
                @endforeach
                @if(count($tech->specialties ?? []) > 2)
                    <span class="badge bg-label-secondary small">+{{ count($tech->specialties) - 2 }}</span>
                @endif
            </div>
        @else
            <span class="text-muted small">—</span>
        @endif
    </td>
    <td class="text-center">
        <div class="d-flex align-items-center justify-content-center gap-1">
            <i class="ti tabler-star-filled text-warning small"></i>
            <span class="fw-bold">{{ number_format($tech->avg_rating, 1) }}</span>
        </div>
    </td>
    <td>
        <span class="badge bg-label-secondary">{{ $tech->total_orders }}</span>
    </td>
    <td>
        <span class="badge {{ $tech->is_active ? 'bg-label-success' : 'bg-label-danger' }} status-badge">
            <i class="ti {{ $tech->is_active ? 'tabler-wifi' : 'tabler-wifi-off' }} me-1"></i>
            {{ $tech->is_active ? 'نشط' : 'موقوف' }}
        </span>
    </td>
    <td class="text-center">
        <div class="d-inline-flex gap-1">
            <button class="btn btn-sm btn-icon btn-label-secondary btn-edit-tech"
                    data-id="{{ $tech->id }}" title="تعديل">
                <i class="ti tabler-edit"></i>
            </button>
            <a href="{{ route('dashboard.technicians.show', $tech->id) }}"
               class="btn btn-sm btn-icon btn-label-primary" title="الملف الشخصي">
                <i class="ti tabler-eye"></i>
            </a>
            <button class="btn btn-sm btn-icon btn-label-{{ $tech->is_active ? 'warning' : 'success' }} btn-toggle-tech"
                    data-id="{{ $tech->id }}" title="{{ $tech->is_active ? 'إيقاف' : 'تفعيل' }}">
                <i class="ti {{ $tech->is_active ? 'tabler-player-pause' : 'tabler-player-play' }}"></i>
            </button>
            <button class="btn btn-sm btn-icon btn-label-danger btn-delete-tech"
                    data-id="{{ $tech->id }}"
                    data-name="{{ $tech->name }}" title="حذف">
                <i class="ti tabler-trash"></i>
            </button>
        </div>
    </td>
</tr>
