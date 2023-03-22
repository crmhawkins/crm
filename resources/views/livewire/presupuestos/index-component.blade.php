@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.css">
    @vite(['resources/sass/productos.scss'])
@endsection


@section('content')
    <div class="container mx-auto">
        <div class="d-flex justify-content-left align-items-center">
            <h1 class="me-5">Presupuestos</h1>

        </div>
        <h2>Todos los presupuestos</h2>
        <br>
        <a href="{{ route('presupuestos.create') }}" class="btn btn-warning">Añadir Presupuesto</a>
        <br><br><br>

        @if (count($presupuestos) > 0)
            <table class="table" id="tablePresupuestos">
                <thead>
                    <tr>
                        <th scope="col">Número</th>
                        <th scope="col">Fecha emisión</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Tipo presupuesto</th>
                        <th scope="col">Curso</th>
                        <th scope="col">Detalles</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Estado</th>

                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Recorre los presupuestos --}}
                    @foreach ($presupuestos as $presup)
                        <tr>
                            <td>{{ $presup->numero_presupuesto }}</th>

                            <td>{{ $presup->fecha_emision }}</th>
                                @if ($presup->alumno_id == 0)
                            <td>Sin Cliente</td>
                        @else
                            {{-- Si el alumno de presupuesto tiene una empresa asignada --}}
                            @foreach ($alumnos as $alumno)
                                @if ($alumno->id == $presup->alumno_id)
                                    <td>{{ $alumno->nombre }}</td>
                                    @if ($alumno->empresa_id > 0)
                                        <td>Para empresa</td>
                                    @else
                                        <td>Particular</td>
                                    @endif
                                @endif
                            @endforeach
                    @endif
                    @if ($presup->curso_id == 0)
                        <td>Sin Curso</td>
                    @else
                        @foreach ($cursos as $curso)
                            @if ($curso->id == $presup->curso_id)
                                <td>{{ $curso->nombre }}</td>
                            @endif
                        @endforeach
                    @endif
                    <td>{{ $presup->detalles }}</th>
                    <td>{{ $presup->precio }}</td>
                    <td>{{ $presup->estado }}</td>

                    <td> <a href="presupuestos-edit/{{ $presup->id }}" class="btn btn-primary">Ver/Editar</a> </td>
                    </tr>
        @endforeach
        </tbody>
        </table>
        @endif



    </div>

    </tbody>
    </table>

@section('scripts')
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            console.log('entro');
            $('#tablePresupuestos').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                buttons: [{
                    extend: 'collection',
                    text: 'Export',
                    buttons: [{
                            extend: 'pdf',
                            className: 'btn-export'
                        },
                        {
                            extend: 'excel',
                            className: 'btn-export'
                        }
                    ],
                    className: 'btn btn-info text-white'
                }],
                "language": {
                    "lengthMenu": "Mostrando _MENU_ registros por página",
                    "zeroRecords": "Nothing found - sorry",
                    "info": "Mostrando página _PAGE_ of _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ total registros)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "zeroRecords": "No se encontraron registros coincidentes",
                }
            });

            addEventListener("resize", (event) => {
                location.reload();
            })
        });
    </script>
@endsection
@endsection
