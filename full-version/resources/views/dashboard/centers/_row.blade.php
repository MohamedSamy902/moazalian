<tr data-id="{{ $center->id }}">
    <td>
        <div class="d-flex align-items-center gap-2">
            <div class="avatar avatar-sm">
                @if($center->logo)
                    <img src="{{ asset('storage/' . $center->logo) }}" alt="{{ $center->name }}" class="rounded-circle" style="width:32px;height:32px;object-fit:cover">
                @else
                    <span class="avatar-initial rounded-circle bg-label-warning">
                        <i class="ti tabler-building-store" style="font-size:0.85rem"></i>
                    </span>
                @endif
            </div>
            <div>
                <div class="fw-bold">{{ $center->name }}</div>
                @if($center->city)
                    <div class="small text-muted">{{ $center->city }}</div>
                @endif
            </div>
        </div>
    </td>
    <td>{{ $center->phone ?? '—' }}</td>
    <td>
        <span class="badge bg-label-info">{{ $center->commission_rate ?? 0 }}%</span>
    </td>
    <td>
        <span class="fw-semibold text-success">{{ number_format($center->total_collected ?? 0) }}</span>
        <small class="text-muted ms-1">ر.س</small>
    </td>
    <td>
        <span class="fw-semibold {{ ($center->platform_due ?? 0) > 0 ? 'text-danger' : 'text-muted' }}">
            {{ number_format($center->platform_due ?? 0) }}
        </span>
        <small class="text-muted ms-1">ر.س</small>
    </td>
    <td>
        @php
            $statusColor = ['active' => 'success', 'suspended' => 'danger', 'pending' => 'warning'][$center->status?->value ?? 'pending'] ?? 'secondary';
            $statusLabel = $center->status?->label() ?? 'غير محدد';
        @endphp
        <span class="badge bg-label-{{ $statusColor }} status-badge">{{ $statusLabel }}</span>
        @if($center->auto_suspended)
            <span class="badge bg-label-danger ms-1" title="موقوف تلقائياً"><i class="ti tabler-robot"></i></span>
        @endif
    </td>
    <td>
        <div class="d-inline-flex gap-1">
            <button class="btn btn-sm btn-icon btn-label-secondary btn-edit-center"
                    data-id="{{ $center->id }}" title="تعديل">
                <i class="ti tabler-edit"></i>
            </button>
            <a href="{{ route('dashboard.centers.show', $center->id) }}"
               class="btn btn-sm btn-icon btn-label-primary" title="عرض التفاصيل">
                <i class="ti tabler-eye"></i>
            </a>
            <button class="btn btn-sm btn-icon btn-label-{{ $statusColor === 'success' ? 'warning' : 'success' }} btn-toggle-center"
                    data-id="{{ $center->id }}"
                    data-name="{{ $center->name }}" title="تغيير الحالة">
                <i class="ti tabler-{{ $statusColor === 'success' ? 'player-pause' : 'player-play' }}"></i>
            </button>
            <button class="btn btn-sm btn-icon btn-label-danger btn-delete-center"
                    data-id="{{ $center->id }}"
                    data-name="{{ $center->name }}" title="حذف">
                <i class="ti tabler-trash"></i>
            </button>
        </div>
    </td>
</tr>
