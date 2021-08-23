<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="{{ asset('assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

@if ($page_name != 'coming_soon' && $page_name != 'contact_us' && $page_name != 'error404' && $page_name != 'error500' && $page_name != 'error503' && $page_name != 'faq' && $page_name != 'helpdesk' && $page_name != 'maintenence' && $page_name != 'privacy' && $page_name != 'auth_boxed' && $page_name != 'auth_default')
    <script src="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="{{ asset('assets/js/scrollspyNav.js') }}"></script>
    <script src="{{ asset('plugins/highlight/highlight.pack.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('plugins/sweetalerts/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalerts/custom-sweetalert.js') }}"></script>
@endif
<!-- END GLOBAL MANDATORY SCRIPTS -->

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
@switch($page_name)
    @case($page_name == 'file-entry')
        {{-- Table Datatable HTML5 --}}
        <script src="{{ asset('plugins/table/datatable/datatables.js') }}"></script>
        <script src="{{ asset('plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
        <script src="{{ asset('plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script>
        {{-- <script src="{{ asset('plugins/sweetalerts/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('plugins/sweetalerts/custom-sweetalert.js') }}"></script> --}}
        <script>
            // MODAL ACTION
            $(document).on('click', '.openModalSares', function(event) {
                event.preventDefault();
                let route = $(this).attr('data-routeName');
                let target = $(this).attr('data-attr');
                let href = route ? route.replace('___dataID___', target) : target;
                let title = $(this).attr('data-title');
                $.ajax({
                    url: href,
                    beforeSend: function() {
                        $('#modal-loader').show();
                        $('#content-body').hide()
                    },
                    // return the result
                    success: function(result) {
                        $('#modal-title').html(title);
                        $('#content-body').html(result);
                    },
                    complete: function() {
                        setTimeout(() => {
                            $('#modal-loader').hide();
                            $('#content-body').show();
                        }, 500)
                    },
                    error: function(jqXHR, testStatus, error) {
                        $('#modal-title').html(title);
                        $('#content-body').html("Page " + href + " cannot open. Error:" + error);
                        $('#modal-loader').hide();
                    },
                    timeout: 8000
                })
            });

            // TOOLBAR ACTION

            $(document).on('click', '#btn-export', function(event) {
                event.preventDefault();
                let type = $(this).attr('data-trigger');
                if (type == "excel") {
                    $(".buttons-excel").click()
                } else if (type == "csv") {
                    $(".buttons-csv").click()
                } else {
                    $(".buttons-print").click()
                }
            })

            // ALERT
            $(document).on('click', '#btn-alert', function(e) {
                e.preventDefault();

                let _token = $('meta[name="csrf-token"]').attr('content');
                let _id = $(this).attr('data-id') ? $(this).attr('data-id') : false;
                let action = $(this).attr('data-action') ? $(this).attr('data-action') : false;
                let title = $(this).attr('data-title') ? $(this).attr('data-title') : "Success";
                let text = $(this).attr('data-text') ? $(this).attr('data-text') : "";
                let type = $(this).attr('data-type') ? $(this).attr('data-type') : "info";
                let showCancel = $(this).attr('data-showCancel') ? $(this).attr('data-showCancel') : false;
                let confirmText = $(this).attr('data-txtButton') ? $(this).attr('data-txtButton') : "OK";
                swal({
                    title: title,
                    text: text,
                    type: type,
                    showCancelButton: showCancel,
                    confirmButtonText: confirmText,
                    padding: '2em',
                    allowOutsideClick: false
                }, () => {
                    $.ajax({
                        url: _id ? action + _id : action,
                        type: "DELETE",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },

                        success: function(res, stat) {
                            swal(
                                'Deleted!',
                                'Your record has been deleted.',
                                'success'
                            ).then(res => {
                                $('#dataTableSares').DataTable().ajax.reload()
                            })
                        },
                        error: function(jqXHR, testStatus, error) {
                            console.log(jqXHR, testStatus, error);
                            swal(
                                'Uppsss!',
                                'Your record can not be deleted.',
                                'error'
                            )
                        },
                    });
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: _id ? action + _id : action,
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },

                            success: function(res, stat) {
                                swal(
                                    'Deleted!',
                                    'Your record has been deleted.',
                                    'success'
                                ).then(res => {
                                    $('#dataTableSares').DataTable().ajax.reload()
                                })
                            },
                            error: function(jqXHR, testStatus, error) {
                                console.log(jqXHR, testStatus, error);
                                swal(
                                    'Uppsss!',
                                    'Your record can not be deleted.',
                                    'error'
                                )
                            },
                        });
                    } else {
                        // swal(
                        //   'Success'
                        // )
                    }
                })
            })
        </script>
    @break

    @default
        <script>
            console.log('No custom script available.')
        </script>
@endswitch

@switch($page_name)
    @case('file-entry')
        
        <script>
            $(document).ready(function() {
                // Action Button
                $(document).on('click', '.buttonSaveRole', function(event) {
                    event.preventDefault();
                    let dataUrl = $(this).attr('data-action');
                    $.ajax({
                        data: $('#roleForm').serialize(),
                        url: dataUrl,
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            console.log("Here", data)
                            if (data.code == 201) {
                                swal(
                                    'Success!',
                                    data.success,
                                    'success'
                                ).then(result => {
                                    $("#modalSares").modal("hide");
                                    table.ajax.reload();
                                })
                            } else {
                                let errData = data.response.map(res => '<span class="lst_err">' +
                                    res + '</span><br>')
                                toast({
                                    type: 'warning',
                                    html: "<div class='wrap_err'>" + errData + "</div>",
                                })
                            }
                        },
                        error: function(data) {

                        }
                    });
                });

                // Pagination
                let page = 1;
                $(document).on('click', '.pagination a', function(event) {
                    event.preventDefault();
                    page = $(this).attr('href').split('page=')[1];
                    let url = `/api/file-entry?page=${page}`
                    table.ajax.reload();
                })

                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

                function clearData(data) {
                    return data ?? '-';
                }

                $(document).on('click', '.buttonSaveRole', function(event) {
                    
                })
                const table = $('#dataTableSares').DataTable({
                    buttons: dtButtons,
                    scrollX: true,
                    columnDefs: [{
                            targets: 0,
                            render: clearData
                        }, {
                            targets: 1,
                            render: function(data) {
                                return data;
                            }
                        }, {
                            targets: 2,
                            render: clearData
                        },
                        {
                            targets: -1,
                            data: null,
                            defaultContent: "<button>Edit</button>",
                        }
                    ],
                    columns: [{
                            data: 'rowNum',
                            title: 'No'
                        },
                        {
                            data: 'date_transactions',
                            title: 'Tanggal Transaksi'
                        },
                        {
                            data: 'item_transactions',
                            title: 'Item Transaksi'
                        },
                        {
                            data: function(data) {
                                return (`
                        <span class="${ data.status == '0' ? 'badge badge-warning' : 'badge badge-info'}">
                          ${data.status == '0' ? 'In Progress' : 'Done' } 
                        </span>
                      `)
                            },
                            title: 'Status'
                        },
                        {
                            title: 'Action',
                            width: "100px",
                            render: function(data) {
                                let routeName = ["file-entry/[__dataId__]", "file-entry.edit"]
                                return (
                                    `
                          <button class="btn btn-dark btn-sm icon icon-view p-1 m-1 openModalSares" data-toggle="modal"
                            id="btn-show" data-target="#modalSares" 
                            data-routeName="{{ route('file-entry.show', '___dataID___') }}"
                            data-attr=${data.id}
                            data-title="View Data" data-backdrop="static" data-keyboard="false"></button>
                          <button id="btn-alert" data-type="warning" {{-- can custom to info etc --}}
                            data-title="Are you sure?" data-text="You won't be able to revert this!" data-txtButton="Delete"
                            data-showCancel=true data-action="file-entry/" 
                            data-id=${data.id}
                            class="btn btn-dark btn-sm icon icon-delete p-1 m-1">
                          </button>
                        `
                                )
                            }
                        }
                    ],
                    paging: false,
                    info: false,
                    ordering: false,
                    serverSide: false,
                    processing: true,
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 10, 10, 'All']
                    ],
                    ajax: {
                        url: '/api/file-entry',
                        type: 'get',
                        dataSrc: function(res) {
                            $('#paginate').html(res.pagination.original)
                            let result = res.data.data.map((elem, index) => {
                                elem.rowNum = res.data.from + index
                                return elem
                            });
                            return result;
                        },
                        data: function(d) {
                            d.page = page
                        },
                        beforeSend: function(req) {
                            req.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr(
                                'content'))
                        }
                    },
                    initComplete: function() {
                        // Apply the search
                        this.api().columns().every(function(colIdx) {
                            var that = this;

                            $('input', this.header()).on('keyup change clear', function() {
                                if (that.search() !== this.value) {
                                    that.column(colIdx)
                                        .search(this.value)
                                        .draw();
                                }
                            });
                        });
                    },
                    "oLanguage": {
                        "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                        "sSearchPlaceholder": "Search...",
                    }
                })
            });
        </script>
    @break
    @default
        <script>
            console.log('No custom script available.')
        </script>
@endswitch
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
