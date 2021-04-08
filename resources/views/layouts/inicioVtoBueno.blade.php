{{-- @extends('layouts.app') --}}
@extends('adminlte::page')

@section('title', 'Visto Bueno Actividades')

@section('css')
    <style>
        .colorTop { 
            background-color: #541533;
        }
    </style>
@endsection

@section('content')

    <div class="container-fluid">
        {{-- @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif --}}

        @if(!empty($success))
            <div class="alert alert-success"> {{ $success }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                VALIDACIÓN DE ACTIVIDADES POR ÓRGANO ADMINISTRATIVO
            </div>
            <div class="card-body">
                <form action="{{ route('vtoBueno.inicio') }}" method="get">
                    @csrf

                    <div class="row">
                        <div class="col-3">Órgano Validador</div>
                        <div class="col-4">
                            <select name="organo" class="form-control" id="organo">
                                <option value="{{ $organo[0]->id }}">{{ $organo[0]->descripcion }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-3">Semana</div>
                        <div class="col-4">
                            <input class="form-control" type="number" name="semana" id="semana" placeholder="Semana"
                                value="{{ $semana }}">
                        </div>
                        <div class="col-2">
                            <select name="actividad2" class="form-control" id="actividad2">
                                <option value="">--SELECCIONAR--</option>
                                <option {{$actividad2 == 'ACTIVIDAD' ? 'selected' : ''}}  value="ACTIVIDAD">ACTIVIDAD</option>
                                <option {{$actividad2 == 'PERMISO' ? 'selected' : ''}} value="PERMISO">PERMISO</option>
                            </select>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-outline-primary">FILTRAR</button>
                        </div>
                    </div>
                </form>

                <div class="row mt-4">
                    <div class="col">
                        @if (!$actividades->isEmpty())
                            <form action="{{route('vtoBueno.enviar', ['semana'=>$actividades[0]->semana])}}" method="post">
                                @csrf

                                <input class="d-none" id="tipo_act" name="tipo_act" type="text" value="{{$actividad2}}">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Asunto</th>
                                            <th scope="col">Actividad</th>
                                            <th scope="col">Estatus</th>
                                            <th scope="col">Observaciones</th>
                                            <th scope="col">Tipo</th>
                                            <th scope="col">Semana</th>
                                            <th scope="col">Enviado</th>
                                            <th scope="col">Modificar</th>
                                            <th scope="col">Selección</th>
                                            <th scope="col">Indicaciones DG</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($areas as $subArea)
                                            <tr>
                                                <td class="text-center" colspan="11">
                                                    <strong>{{ $subArea->descripcion }}</strong>
                                                </td>
                                            </tr>
                                            @foreach ($actividades as $actividad)
                                                @if ($actividad->area_responsable == $subArea->id)
                                                    <tr>
                                                        <td width="100px">{{ $actividad->fecha }}</td>
                                                        <td>{{ $actividad->asunto }}</td>
                                                        <td>{{ $actividad->actividad }}</td>
                                                        <td>{{ $actividad->status }}</td>
                                                        <td>{{ $actividad->observaciones }}</td>
                                                        <td width="100px" >{{ $actividad->tipo_actividad }}</td>
                                                        <td width="40px">{{ $actividad->semana }}</td>
                                                        <td width="40px">
                                                            @if ($actividad->fecha_vToBueno == null)
                                                                NO
                                                            @else
                                                                SI
                                                            @endif
                                                        </td>
                                                        <td width="50px">
                                                            @if ($actividad->fecha_vToBueno == null)
                                                                {{-- <button onclick="showModal({{$actividad}})" type="button"
                                                                class="btn btn-outline-info">Modificar</button> --}}
                                                                
                                                                <button onclick="showModal({{$actividad}})" type="button" class="btn btn-primary btn-sm" 
                                                                data-toggle="modal" data-target="#modalModify">Modificar</button>
                                                            @else
                                                                No Disponible
                                                            @endif
                                                        </td>
                                                        <td width="30px">
                                                            @if ($actividad->fecha_vToBueno == null)
                                                                <div class="custom-control custom-checkbox d-flex justify-content-center">
                                                                    <input type="checkbox" value="{{ $actividad->id }}"
                                                                        class="custom-control-input settings" name="actividades[]"
                                                                        id="check + {{ $actividad->id }}">
                                                                    <label class="custom-control-label"
                                                                        for="check + {{ $actividad->id }}"></label>
                                                                </div>
                                                            @else
                                                                No Disponible
                                                            @endif
                                                        </td>
                                                        <td width="140px">
                                                            @if ($actividad->ind_direccion != null)
                                                                {{ $actividad->ind_direccion }}
                                                            @endif
                                                            {{-- {{ $actividad->ind_direccion }} --}}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>

                                @if (!$actividades->isEmpty())
                                    <div class="row">
                                        <div class="col d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Enviar a Validación</button>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        @else
                            <div class="row mt-5">
                                <div class="col text-center">
                                    <h5><strong>Sin Actividades Registradas</strong></h5>
                                </div>
                            </div>
                        @endif

                        
                    </div>
                </div>
            </div>
        </div>

        {{-- modal modificar --}}
        <div id="modalModify" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    
                    <form action="{{route('vtoBueno.editar')}}" method="post">
                        @csrf

                        <input class="d-none" id="tipo_activi" name="tipo_activi" type="text" value="{{$actividad2}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Modificación de actividad</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input class="d-none" type="text" id="id" name="id">
                            <div class="form-group col">
                                <label for="asunto" class="control-label">Asunto</label>
                                <textarea name="asunto" id="asunto" class="form-control" placeholder="Asunto" cols="30" rows="2"></textarea>
                            </div>
                            <div class="form-group col">
                                <label for="actividad" class="control-label">Actividad</label>
                                <textarea name="actividad" id="actividad" class="form-control" placeholder="Actividad" cols="30" rows="2"></textarea>
                            </div>
                            <div class="form-group col">
                                <label for="observaciones" class="control-label">Observaciones</label>
                                <textarea class="form-control" name="observaciones" id="observaciones" cols="30" rows="2" placeholder="Observaciones"></textarea>
                            </div>
                            <div class="form-group col">
                                <label for="semanaF" class="control-label">Semana</label>
                                <input type="number" class="form-control" id="semanaF" name="semanaF" placeholder="Semana">
                            </div>
                            <div class="form-group col">
                                <label for="status" class="control-label">Estado</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="INICIADO">INICIADO</option>
                                    <option value="EN PROCESO">EN PROCESO</option>
                                    <option value="TERMINADO">TERMINADO</option>
                                </select>
                            </div>

                            <div class="form-group col">
                                <label for="tipo_actividad" class="control-label">Tipo de actividad</label>
                                <select name="tipo_actividad" class="form-control" id="tipo_actividad">
                                    <option value="ACTIVIDAD">ACTIVIDAD</option>
                                    <option value="PERMISO">PERMISO</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Modificar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>

        function showModal(actividad) {
            $('#id').val(actividad['id']);
            $('#asunto').val(actividad['asunto']);
            $('#actividad').val(actividad['actividad']);
            $('#observaciones').val(actividad['observaciones']);
            $('#semanaF').val(actividad['semana']);
            $('#status').val(actividad['status']);
            $('#tipo_actividad').val(actividad['tipo_actividad']);
        }

    </script>
@endsection
