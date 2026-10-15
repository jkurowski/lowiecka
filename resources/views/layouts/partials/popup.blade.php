{{-- Baner „na start" - @param \App\Services\Front\PopupBanner|null $banner --}}
@if($banner && $banner->shouldOpen)
    <style>
        #popModal-{{ $banner->key }} .modal-body { padding: 0; }
        #popModal-{{ $banner->key }} .modal-body img { display: block; max-width: 100%; height: auto; margin: 0 auto; }
        #popModal-{{ $banner->key }} .modal-body p { margin: 0; }
        #popModal-{{ $banner->key }} .btn-close { opacity: 1; width: 1.25em; height: 1.25em; }
    </style>
    <div class="modal fade" tabindex="-1" id="popModal-{{ $banner->key }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close btn-close-white"data-bs-dismiss="modal" aria-label="Zamknij"></button>
                </div>
                <div class="modal-body">
                    {!! $banner->text !!}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var element = document.getElementById('popModal-{{ $banner->key }}');

            if (!element || typeof bootstrap === 'undefined') {
                return;
            }

            @if($banner->closesItself())
                var popModal = new bootstrap.Modal(element, {keyboard: false});
                popModal.show();
                setTimeout(function () {
                    popModal.hide();
                }, {{ $banner->timeout }});
            @else
                {{-- Zamyka tylko krzyżyk - kliknięcie obok ani Esc nie zamykają banera. --}}
                var popModal = new bootstrap.Modal(element, {backdrop: 'static', keyboard: false});
                popModal.show();
            @endif
        });
    </script>
@endif
