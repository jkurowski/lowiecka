@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-inbox"></i>RODO: ustawienia</h4>
                </div>
            </div>
        </div>

        @include('admin.settings.menu')

        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    @if (session('success'))
                        <div class="alert alert-success border-0 mb-0">
                            {{ session('success') }}
                            <script>setTimeout(function(){$(".alert").slideUp(500,function(){$(this).remove()})},3000)</script>
                        </div>
                    @endif
                    <form method="POST" action="{{route('admin.rodo.settings.update', 1)}}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                        <div class="container-fluid p-0">
                            <div class="card p-4">
                                <div class="card-body p-3 control-col12">
                                    <div class="row w-100 form-group">
                                        <label for="form_status" class="col-12 col-form-label control-label">
                                            <div class="text-start w-100">
                                                Pokaż obowiązek
                                            </div>
                                        </label>
                                        <div class="col-12 control-input d-flex align-items-center">
                                            <select class="form-select" name="status" id="form_status">
                                                <option value="1"<?php if($entry->status == 1){?> selected<?php } ?>>Tak</option>
                                                <option value="0" <?php if($entry->status == 0){?> selected<?php } ?>>Nie</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row w-100 form-group">
                                        @include('form-elements.textarea', ['label' => 'Treść obowiązku informacyjnego', 'name' => 'obligation', 'value' => $entry->obligation, 'rows' => 11])
                                    </div>

                                </div>
                            </div>
                        </div>
                        @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
