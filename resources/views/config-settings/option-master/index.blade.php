@extends('layouts.app')

@section('title', 'Option Master')

@section('content')
<div class="card">
    <div class="card-body">

        {{-- SELECT OPTION MASTER --}}
        <div class="row mb-3 align-items-center">
            <label class="col-md-3 text-end fw-bold">Select Option</label>

            <div class="col-md-4">
                <select class="form-control" id="option_master_module_id">
                    <option value="">Select</option>

                    @foreach ($optionmasters as $val)
                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                    @endforeach

                    {{-- <option value="new">+ Add New Option</option> --}}
                </select>

                <input type="text" id="new_option_master" class="form-control mt-2 d-none" placeholder="Enter new option name">
            </div>
        </div>


        {{-- ADD / EDIT FORM --}}
        <div class="border rounded p-2 mb-3 bg-light">
            <div class="row g-2 align-items-center">

                <input type="hidden" id="id">

                <div class="col-md-2">
                    <input class="form-control form-control-sm" id="display_name" placeholder="Display Name">
                </div>

                <div class="col-md-2">
                    <input class="form-control form-control-sm" id="display_value" placeholder="Value">
                </div>

                <div class="col-md-2">
                    <input type="file" class="form-control form-control-sm" id="display_image">
                </div>

                <div class="col-md-1 text-center">
                    <input type="checkbox" id="is_default" value="1" style="width:18px;height:18px;">
                </div>

                <div class="col-md-2">
                    {{-- ✅ STATUS MUST BE INTEGER --}}
                    <select class="form-control form-control-sm" id="status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <div class="col-md-3 text-center">
                    <div class="btn-group">
                        <button type="button" id="add" class="btn btn-sm btn-success">
                            <i class="feather-plus"></i>
                        </button>
                        <button type="button" id="edit" class="btn btn-sm btn-primary" style="display:none;">
                            <i class="feather-edit"></i>
                        </button>
                        <button type="button" id="cancel" class="btn btn-sm btn-warning">
                            <i class="feather-x"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table id="table" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Value</th>
                        <th>Image</th>
                        <th>Default</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    console.log('JS LOADED');

    let table = null;

    /* LOAD DATATABLE */
    function loadTable(optionMasterId) {
        if (table) {
            table.destroy();
        }

        table = $('#table').DataTable({
            processing: true
            , serverSide: true
            , ajax: {
                url: "{{ route('option-master.index') }}"
                , type: "POST"
                , data: function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.id = $('#option_master_module_id').val();
                }
            }
            , columns: [{
                    data: 'DT_RowIndex'
                    , orderable: false
                }
                , {
                    data: 'name'
                }
                , {
                    data: 'value'
                }
                , {
                    data: 'display_image'
                    , orderable: false
                }
                , {
                    data: 'default'
                    , orderable: false
                }
                , {
                    data: 'status'
                    , orderable: false
                }
                , {
                    data: 'action'
                    , orderable: false
                }
            ]
        });

    }

    /* OPTION MASTER CHANGE */
    $('#option_master_module_id').on('change', function() {
        let id = $(this).val();
        if (id) {
            loadTable(id);
        }
    });

    /* ADD */
    $('#add').on('click', function() {

        let fd = new FormData();
        fd.append('_token', "{{ csrf_token() }}");
        fd.append('option_master_module_id', $('#option_master_module_id').val());
        fd.append('display_name', $('#display_name').val());
        fd.append('display_value', $('#display_value').val());
        fd.append('status', $('#status').val()); // ✅ int
        fd.append('is_default', $('#is_default').is(':checked') ? 1 : 0);

        if ($('#display_image')[0].files.length > 0) {
            fd.append('display_image', $('#display_image')[0].files[0]);
        }

        $.ajax({
            url: "{{ route('option-master.store') }}"
            , type: "POST"
            , data: fd
            , processData: false
            , contentType: false
            , success: function() {
                resetForm();
                table.ajax.reload();
            }
        });
    });

    /* EDIT */
    window.editOptions = function(id, name, value, status, def) {
        $('#id').val(id);
        $('#display_name').val(name);
        $('#display_value').val(value);
        $('#status').val(status);
        $('#is_default').prop('checked', def == 1);

        $('#add').hide();
        $('#edit').show();
    };

    /* UPDATE */
    $('#edit').on('click', function() {

        let fd = new FormData();
        fd.append('_token', "{{ csrf_token() }}");
        fd.append('id', $('#id').val());
        fd.append('option_master_module_id', $('#option_master_module_id').val());
        fd.append('display_name', $('#display_name').val());
        fd.append('display_value', $('#display_value').val());
        fd.append('status', $('#status').val());
        fd.append('is_default', $('#is_default').is(':checked') ? 1 : 0);

        if ($('#display_image')[0].files.length > 0) {
            fd.append('display_image', $('#display_image')[0].files[0]);
        }

        $.ajax({
            url: "{{ route('option-master.update') }}"
            , type: "POST"
            , data: fd
            , processData: false
            , contentType: false
            , success: function() {
                resetForm();
                table.ajax.reload();
            }
        });
    });

    /* CANCEL */
    $('#cancel').on('click', function() {
        resetForm();
    });

    function resetForm() {
        $('#id').val('');
        $('#display_name').val('');
        $('#display_value').val('');
        $('#display_image').val('');
        $('#is_default').prop('checked', false);
        $('#status').val(1);
        $('#add').show();
        $('#edit').hide();
    }

    /* INLINE ADD OPTION MASTER */

    $('#option_master_module_id').on('change', function() {

        if ($(this).val() === 'new') {
            $('#new_option_master').removeClass('d-none').focus();
        } else {
            $('#new_option_master').addClass('d-none').val('');
        }
    });

    /* SAVE NEW OPTION MASTER ON BLUR OR ENTER */
    $('#new_option_master').on('blur keypress', function(e) {

        if (e.type === 'keypress' && e.which !== 13) return;

        let name = $(this).val().trim();
        if (!name) return;

        $.ajax({
            url: "{{ route('option-master.inline-store') }}"
            , type: "POST"
            , data: {
                _token: "{{ csrf_token() }}"
                , name: name
            }
            , success: function(res) {

                let option = new Option(res.name, res.id, true, true);
                $('#option_master_module_id option:last').before(option);

                $('#new_option_master').addClass('d-none').val('');
                loadTable(res.id); // auto load table for new option
            }
        });
    });

</script>
@endsection
