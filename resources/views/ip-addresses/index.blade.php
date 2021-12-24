@extends('layouts.main')

@section('content')
    <h2>Все товары</h2>
    <hr>
    <br>
    <div class="row" style="clear: both;">
        <div class="col-12 text-right">
            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" onclick="add()"><i class="fas fa-plus-square"></i> Добавить пользователя</a>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class=" table table-bordered table-striped" id="table-model" width="100%">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="25%">Имя</th>
                <th width="20%">ID</th>
                <th width="20%">Имя</th>
                <th width="15%"></th>
                <th width="15%"></th>
            </tr>
            </thead>
        </table>
    </div>
    <br>
    <hr>
    <div class="modal fade" id="post-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Новый IP адрес</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="Form" class="form-horizontal">
                        <input type="hidden" name="model_id" id="model_id">
                        <div class="form-group">
                            <label for="country">IP</label>
                            <input type="text"
                                   class="form-control"
                                   pattern="[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}"
                                   title="Формат: ***.***.***.***"
                                   id="ip"
                                   name="ip">
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="country">Страна</label>
                                    <input type="text"
                                           class="form-control"
                                           id="country"
                                           name="country">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="city">Город</label>
                                    <input type="text"
                                           class="form-control"
                                           id="city"
                                           name="city">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="form-errors">
                            <div class="alert alert-danger">
                                <ul>

                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group mr-1" role="group" aria-label="Second group">
                            <div class="collapse" id="delete-button">
                                <button type="button" class="btn btn-danger" onclick="deleteModel()">Удалить</button>
                            </div>
                        </div>
                        <div class="btn-group" role="group" aria-label="Third group">
                            <button type="button" class="btn btn-primary" onclick="saveModel()">Сохранить</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function add() {
            $('#model_id').val('')
            $('#ip').val('');
            $('#city').val('');
            $('#country').val('');
            $('#form-errors').html('');
            $('#collapseExample').hide();
            $('#post-modal').modal('show');
        }

        function saveModel() {
            $.ajax({
                url: "{{ route('ip-addresses.store') }}",
                type: "POST",
                data: {
                    id: $('#model_id').val(),
                    ip: $('#ip').val(),
                    country: $('#country').val(),
                    city: $('#city').val(),
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if(response.code == 200) {
                        $('#model_id').val('')
                        $('#ip').val('');
                        $('#city').val('');
                        $('#country').val('');
                        $('#form-errors').html('');
                        $('#table-model').DataTable().ajax.reload();
                        $('#post-modal').modal('hide');
                    }
                    else {
                        var errors = response.errors;
                        errorsHtml = '<div class="alert alert-danger"><ul>';
                        $.each( errors, function( key, value ) {
                            errorsHtml += '<li>'+ value + '</li>';
                        });
                        errorsHtml += '</ul></div>';
                        $( '#form-errors' ).html( errorsHtml );
                    }
                },
                error: function(response) {
                    $('#nameError').text(response.responseJSON.errors.name);
                }
            });
        }

        $(document).ready(function() {
            $('#table-model').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('ip-addresses.index') }}",
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'ip',
                        name: 'ip'
                    },
                    {
                        data: 'country',
                        name: 'country'
                    },
                    {
                        data: 'city',
                        name: 'city'
                    },
                    {
                        data: 'edit',
                        name: 'edit'
                    },
                    {
                        data: 'more',
                        name: 'more'
                    },
                ]
            });
        });

    </script>
@endsection
