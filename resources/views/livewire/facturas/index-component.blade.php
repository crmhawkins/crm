<div class="container mx-auto">
    @if (count($facturas) > 0)
        <div x-data="{}" x-init="$nextTick(() => {
            $('#tableFacturas').DataTable({
                responsive: true,
                fixedHeader: true,
                searching: false,
                paging: false,
            });
        })">
            <table class="table" id="tableFacturas">
                <thead>
                    <tr>
                        <th scope="col">Número</th>
                        <th scope="col">Tipo de documento</th>
                        <th scope="col">Presupuesto/s asociado/s</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Total</th>
                        <th scope="col">Total (IVA)</th>
                        <th scope="col">Método de pago</th>

                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($facturas as $fact)
                        <tr>
                            <td>{{ $fact->numero_factura }}</th>
                            <td>{{ $fact->tipo_documento }}</th>
                            @if ($fact->tipo_documento == 'factura')
                                <td>{{ $presupuestos->where('id', $fact->id_presupuesto)->first()->numero_presupuesto }}</td>
                            @else
                            <td>
                                @foreach($fact->id_presupuesto as $presup)
                                    {{ $presupuestos->where('id', $presup)->first()->numero_presupuesto }} ,
                                @endforeach
                            </td>
                            @endif
                            <td>{{ $fact->descripcion }}</td>
                            <td>{{ $fact->precio }} €</td>
                            <td>{{ $fact->precio_iva }} €</td>
                            <td>{{ $fact->metodo_pago }}</td>

                            <td><button type="button" class="btn btn-primary boton-producto"
                                onclick="Livewire.emit('seleccionarProducto', {{ $fact->id }});">Editar</button>
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <table class="table" id="tableFacturas">
            <thead>
                <tr>
                    <th scope="col">Número</th>
                    <th scope="col">Presupuesto asociado</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Total</th>
                    <th scope="col">Método de pago</th>

                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <h5> No hay facturas.</h5>
        </table>
    @endif

</div>
