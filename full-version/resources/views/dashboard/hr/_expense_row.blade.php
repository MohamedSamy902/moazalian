<tr data-id="{{ $expense->id }}">
    <td>
        @if($expense->category)
            <span class="badge bg-label-secondary">{{ $expense->category->getTranslation('name','ar') }}</span>
        @else
            <span class="badge bg-label-secondary">أخرى</span>
        @endif
    </td>
    <td class="fw-semibold">{{ $expense->description ?? '—' }}</td>
    <td class="fw-bold text-danger">{{ number_format($expense->amount) }} <small class="text-muted">ر.س</small></td>
    <td class="text-muted small">{{ $expense->expense_date?->format('Y-m-d') }}</td>
    <td class="small">{{ $expense->createdBy?->name ?? '—' }}</td>
    <td>
        <button class="btn btn-sm btn-icon btn-label-danger btn-delete-expense"
                data-id="{{ $expense->id }}" title="حذف">
            <i class="ti tabler-trash"></i>
        </button>
    </td>
</tr>
