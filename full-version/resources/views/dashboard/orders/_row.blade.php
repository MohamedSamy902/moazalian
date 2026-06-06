@php
    $statusColors = [
        'pending'    => 'warning',
        'dispatched' => 'info',
        'assigned'   => 'primary',
        'in_progress'=> 'info',
        'completed'  => 'success',
        'cancelled'  => 'danger',
        'reopened'   => 'secondary',
    ];
    $col = $statusColors[$order->status?->value ?? 'pending'] ?? 'secondary';
@endphp
<tr data-id="{{ $order->id }}">
    <td>
        <span class="fw-bold text-primary">{{ $order->reference_number }}</span>
    </td>
    <td>
        <div class="d-flex align-items-center gap-2">
            <div class="avatar avatar-sm">
                <span class="avatar-initial rounded-circle bg-label-primary">{{ mb_substr($order->customer_name, 0, 1) }}</span>
            </div>
            <div>
                <div class="fw-semibold">{{ $order->customer_name }}</div>
                <div class="small text-muted">{{ $order->customer_phone }}</div>
            </div>
        </div>
    </td>
    <td>
        <div class="d-flex align-items-center gap-1">
            <i class="ti tabler-device-laptop text-muted small"></i>
            <span>{{ $order->device_type_name }}</span>
            @if($order->brand_name)
                <small class="text-muted">/ {{ $order->brand_name }}</small>
            @endif
        </div>
    </td>
    <td>
        @if($order->center_name)
            <span class="badge bg-label-secondary">{{ $order->center_name }}</span>
        @else
            <span class="text-muted small">—</span>
        @endif
    </td>
    <td>
        <span class="fw-bold">{{ number_format($order->amount) }}</span>
        <small class="text-muted">ر.س</small>
    </td>
    <td>
        <span class="badge bg-label-{{ $col }} status-badge">{{ $order->status?->label() ?? '—' }}</span>
        @if($order->is_visit_only)
            <span class="badge bg-label-warning ms-1"><i class="ti tabler-eye me-1"></i>زيارة فقط</span>
        @endif
        @if($order->is_financially_closed)
            <span class="badge bg-label-success ms-1"><i class="ti tabler-lock"></i></span>
        @endif
    </td>
    <td>
        <div class="text-muted small">
            <i class="ti tabler-calendar me-1"></i>
            {{ $order->created_at->format('Y-m-d') }}
        </div>
    </td>
    <td class="text-center">
        <div class="d-inline-flex gap-1">
            <button class="btn btn-sm btn-icon btn-label-secondary btn-edit-order"
                    data-id="{{ $order->id }}" title="تعديل">
                <i class="ti tabler-edit"></i>
            </button>
            <a href="{{ route('dashboard.orders.show', $order->id) }}"
               class="btn btn-sm btn-icon btn-label-primary" title="عرض التفاصيل">
                <i class="ti tabler-eye"></i>
            </a>
            <button class="btn btn-sm btn-icon btn-label-danger btn-delete-order"
                    data-id="{{ $order->id }}"
                    data-ref="{{ $order->reference_number }}" title="حذف">
                <i class="ti tabler-trash"></i>
            </button>
        </div>
    </td>
</tr>
