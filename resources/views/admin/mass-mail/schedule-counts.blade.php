<div class="card my-2 bg-transparent shadow-none">
    <div class="card-body px-0">
        <div class="row">
            @if (isset($scheduleCounts[\App\Helpers\EmailScheduleStatuses::SENT]) && $scheduleCounts[\App\Helpers\EmailScheduleStatuses::SENT] > 0)
                <div class="col-4">
                    <div class="floor-status rounded bg-success">
                        Wysłane<b class="float-end">{{ $scheduleCounts[\App\Helpers\EmailScheduleStatuses::SENT] }}</b>
                    </div>
                </div>
            @endif
            @if (isset($scheduleCounts[\App\Helpers\EmailScheduleStatuses::PENDING]) && $scheduleCounts[\App\Helpers\EmailScheduleStatuses::PENDING] > 0)
                <div class="col-4">
                    <div class="floor-status rounded bg-info">
                        Oczekuje<b class="float-end">{{ $scheduleCounts[\App\Helpers\EmailScheduleStatuses::PENDING] }}</b>
                    </div>
                </div>
            @endif
            @if (isset($scheduleCounts[\App\Helpers\EmailScheduleStatuses::FAILED]) && $scheduleCounts[\App\Helpers\EmailScheduleStatuses::FAILED] > 0)
                <div class="col-4">
                    <div class="floor-status rounded bg-danger">
                        Błąd<b class="float-end">{{ $scheduleCounts[\App\Helpers\EmailScheduleStatuses::FAILED] }}</b>
                    </div>
                </div>
            @endif
            @if (isset($scheduleCounts[\App\Helpers\EmailScheduleStatuses::NO_CONSENT]) && $scheduleCounts[\App\Helpers\EmailScheduleStatuses::NO_CONSENT] > 0)
                <div class="col-4">
                    <div class="floor-status rounded bg-danger opacity-75">
                        Brak zgody<b class="float-end">{{ $scheduleCounts[\App\Helpers\EmailScheduleStatuses::NO_CONSENT] }}</b>
                    </div>
                </div>
        @endif
        </div>
    </div>
</div>
