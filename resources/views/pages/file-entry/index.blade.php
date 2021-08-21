@extends('layouts.app')

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="header-page d-flex align-items-center">
                    {{-- title page --}}
                    {{-- <h5>Kelola File Entry</h5> --}}
                </div>
                <div class="widget widget-content widget-content-area br-6">
                    <div class="widget-header ">
                        <h4>Proses Tata Letak</h4>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form id="formImportFile" action="{{ route('file-import') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                            <div class="custom-file text-left">
                                <input type="file" name="file" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" id="labelFile" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center" style="max-width: 500px; margin: 0 auto;">
                            <button id="btn-import" class="btn btn-primary">Import data</button>
                            <a class="btn btn-success" href="{{ route('file-export') }}">Export data</a>
                        </div>
                    </form>
                    <div class="table-responsive mb-4 mt-4">
                        <table id="dataTableSares" class="table cell-border compact stripe" style="width:100%"></table>
                    </div>
                    <div id="paginate">
                        @include('inc.pagination')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('inc.component', ['as' => 'modal','type' => 'loader-modal', 'count' => 2])
@endsection

@section('script')
    <script>
        $('form#formImportFile').on('change', '#customFile', function() {
            var getLabel = $('#customFile')[0] && $('#customFile')[0].files && $('#customFile')[0].files && $(
                '#customFile')[0].files.length > 0 ? $('#customFile')[0].files[0].name : "";
            if ($('#labelFile')[0] && $('#labelFile')[0].innerText) {
                $('#labelFile')[0].innerText = getLabel || "File Selected"
            }
        });
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                swal(
                'Uppsss!',
                "{{ $error }}",
                'error'
                )
            @endforeach
        @endif
    </script>
@endsection
