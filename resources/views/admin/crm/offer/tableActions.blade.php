<div class="btn-group">
    <button onclick="copyToClipboard('{{ route('front.show', ['offer' => $row->id]) }}')"
        class="btn action-button action-button me-1" data-bs-toggle="tooltip" data-placement="top"
        data-bs-title="Skopiuj link do oferty">
        <i class="fe-link"></i>
    </button>
    <a href="{{ route('admin.crm.offer.create', ['id' => $row->id]) }}" class="btn action-edit-button action-button me-1"
        data-bs-toggle="tooltip" data-placement="top" data-bs-title="Edytuj zgłoszenie">
        <i class="fe-edit"></i>
    </a>

    <form method="POST" action="{{ route('admin.crm.offer.destroy', ['id' => $row->id]) }}" id="deleteForm{{$row->id}}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button type="submit" class="btn action-button confirmForm" data-bs-toggle="tooltip" data-placement="top"
            data-bs-title="Usuń zgłoszenie" data-id="{{ $row->id }}">
            <i class="fe-trash-2"></i>
        </button>
    </form>
</div>
<script>
    $(".confirmForm").click(function (d) {
        d.preventDefault();
        const c = $(this).closest("form");
        $.confirm({
            title: "Potwierdzenie usunięcia",
            message: "Czy na pewno chcesz usunąć?",
            buttons: {
                Tak: {
                    "class": "btn btn-primary",
                    action: function () {
                        c.submit();
                    }
                },
                Nie: {
                    "class": "btn btn-secondary",
                    action: function () {
                    }
                }
            }
        })
    });
</script>
