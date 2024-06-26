<div id="contenedorPresupuestosEdit">
    <script>
        console.log('hola');

        $('#select2-producto-edit').select2({
            placeholder: "Seleccione un producto"
        });

        $('#select2-producto-edit').on('load', function(e) {
            var data = $('#select2-producto-edit').select2("val");
            @this.set('producto_seleccionado', data);
        });

        $('#select2-producto-edit').on('change', function(e) {
            var data = $('#select2-producto-edit').select2("val");
            @this.set('producto_seleccionado', data);
        });

        $('#select2-servicio-edit').select2({
            placeholder: "Localización del servicio"
        });

        $('#select2-servicio-edit').on('load', function(e) {
            var data = $('#select2-servicio-edit').select2("val");
            @this.set('servicio', data);
        });

        $('#select2-servicio-edit').on('change', function(e) {
            var data = $('#select2-servicio-edit').select2("val");
            @this.set('servicio', data);
        });

        $('#select2-origen-edit').select2({
            placeholder: "Origen del presupuesto"
        });

        $('#select2-origen-edit').on('load', function(e) {
            var data = $('#select2-origen-edit').select2("val");
            @this.set('origen', data);
        });

        $('#select2-origen-edit').on('change', function(e) {
            var data = $('#select2-origen-edit').select2("val");
            @this.set('origen', data);
        });

        $('#select2-cliente-edit').select2({
            placeholder: "Seleccione un cliente"
        });

        $('#select2-cliente-edit').on('load', function(e) {
            var data = $('#select2-cliente-edit').select2("val");
            @this.set('cliente_id', data);
        });

        $('#select2-cliente-edit').on('change', function(e) {
            var data = $('#select2-cliente-edit').select2("val");
            @this.set('cliente_id', data);
        });

        $('#select2-matricula-edit').select2({
            placeholder: "Seleccione un Vehiculo"
        });

        $('#select2-matricula-edit').on('load', function(e) {
            var data = $('#select2-matricula-edit').select2("val");
            @this.set('matricula', data);
        });

        $('#select2-matricula-edit').on('change', function(e) {
            var data = $('#select2-matricula-edit').select2("val");
            @this.set('matricula', data);
        });
    </script>
    <form wire:submit.prevent="update">
        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">

        <input wire:model="estado" name="estado" type="hidden" value="pendiente" />
        <input wire:model="trabajador_id" name="trabajador_id" type="hidden" value="{{ Auth::id() }}" />

        <br>

        <div class="card">
            <h5 class="card-header">Datos básicos</h5>
            <div class="card-body">
                <div class="mb-3 row d-flex align-items-center">
                    <label for="servicio" class="col-sm-2 col-form-label"><strong>Servicio dado en:</strong></label>
                    <div class="col-sm-10" wire:ignore>
                        <select class="form-control" id="select2-servicio-edit">
                            @foreach ($almacenes as $listalmacen)
                                <option value="{{ $listalmacen->id }}">{{ $listalmacen->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3 row d-flex align-items-center">
                    <label for="origen" class="col-sm-2 col-form-label">Presupuesto dado en:</label>
                    <div class="col-sm-10" wire:ignore>
                        <select id="select2-origen-edit" class="form-control seleccion">
                            <option value="Mostrador">Mostrador</option>
                            <option value="Teléfono">Teléfono</option>
                            <option value="Formulario web">Formulario web</option>
                            <option value="Email">Email</option>
                            <option value="Whatsapp">Whatsapp</option>
                        </select>
                        @error('denominacion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row d-flex align-items-center">
                    <label for="fecha_emision" class="col-sm-2 col-form-label">Fecha de emisión</label>
                    <div class="col-sm-10">
                        <input type="datetime-local" wire:model="fecha_emision" wire:change="numeroPresupuesto"
                            class="form-control" name="fecha_emision" id="fecha_emision">
                        @error('fecha_emision')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row d-flex align-items-center">
                    <label for="numero_presupuesto" class="col-sm-2 col-form-label">Número de presupuesto</label>
                    <div class="col-sm-10">
                        <input type="text" wire:model="numero_presupuesto" class="form-control"
                            name="numero_presupuesto" id="numero_presupuesto" disabled>
                        @error('numero_presupuesto')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="mb-3 row d-flex align-items-center">
                    <label for="observaciones" class="col-sm-2 col-form-label">Comentario</label>
                    <div class="col-sm-10">
                        <input type="text" wire:model="observaciones" class="form-control" name="observaciones"
                            id="observaciones">
                        @error('fecha_emision')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row d-flex align-items-center">
                    <label for="cliente_id" class="col-sm-2 col-form-label">Cliente</label>
                    <div class="col-sm-10" wire:ignore>
                        <select  wire:model="cliente_id" id="select2-cliente-edit" class="form-control seleccion"  >
                            @foreach ($clientes as $clienteSel)
                                <option value="{{ $clienteSel->id }}">{{ $clienteSel->id }} - {{ $clienteSel->nombre }}
                                </option>
                            @endforeach

                        </select>
                        @error('denominacion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row d-flex align-items-center">
                    <label for="cliente_id" class="col-sm-3 col-form-label">Pagado</label>
                    <div class="col-sm-9 " wire:ignore>
                        <select class="form-control" wire:model="estado_pago">
                            <option value="">-- Esta pagado el presupuesto --</option>
                            <option value="1">Pagado</option>
                            <option value="0">No pagado</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <h5 class="card-header">Datos del vehículo</h5>
            <div class="card-body">
                <div class="mb-3 row d-flex align-items-center">
                    <label for="matricula" class="col-sm-2 col-form-label">Matrícula</label>
                    <div class="col-sm-10" wire:ignore>
                            <select wire:model="matricula"  id="select2-matricula-edit" class="form-control">

                                @foreach($vehiculosCliente as $vehiculo)
                                    <option value="{{ $vehiculo->matricula }}">{{ $vehiculo->matricula }}</option>
                                @endforeach
                            </select>
                        @error('fecha_emision')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row d-flex align-items-center">
                    <label for="marca" class="col-sm-2 col-form-label">Marca</label>
                    <div class="col-sm-10">
                        <input type="text" wire:model="marca" class="form-control" name="marca" id="marca" disabled>
                        @error('marca')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row d-flex align-items-center">
                    <label for="modelo" class="col-sm-2 col-form-label">Modelo</label>
                    <div class="col-sm-10">
                        <input type="text" wire:model="modelo" class="form-control" name="modelo"
                            id="modelo" disabled>
                        @error('modelo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row d-flex align-items-left">
                    <label for="vehiculo_renting" class="col-sm-2 col-form-label">¿Este vehículo es de
                        renting?</label>
                    <input class="col-sm-2 form-check" type="checkbox" wire:model="vehiculo_renting"
                        name="vehiculo_renting" id="vehiculo_renting" disabled>
                    @error('vehiculo_renting')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3 row d-flex align-items-center">
                    <label for="kilometros" class="col-sm-2 col-form-label">Kilómetros</label>
                    <div class="col-sm-10">
                        <input type="number" wire:model="kilometros" class="form-control" name="kilometros"
                            id="kilometros" disabled>
                        @error('fecha_emision')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <h5 class="card-header">Lista de artículos seleccionados</h5>
            <div class="card-body">
                @if (count($lista) != 0)
                    <div class="mb-3 row d-flex align-items-center">
                        <table class="table" id="tableProductos" wire:change="añadirProducto">
                            <thead>
                                <tr>
                                    <th scope="col">Código</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Precio</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lista as $productoID => $pCantidad)
                                    @if ($pCantidad > 0)
                                        @php
                                            $productoLista = $productos->where('id', $productoID)->first();
                                        @endphp
                                        <tr id="{{ $productoLista->id }}">
                                            <td>{{ $productoLista->cod_producto }}</td>
                                            <td>{{ $productoLista->descripcion }}</td>
                                            <td>{{ $productoLista->precio_venta }}€</td>
                                            <td> <button class="btn btn-sm btn-primary"
                                                    wire:click.prevent="reducir({{ $productoID }})">-</button>
                                                {{ $pCantidad }}
                                                <button class="btn btn-sm btn-primary"
                                                    wire:click.prevent="aumentar({{ $productoID }})">+</button>
                                            </td>
                                            <td>{{ $productoLista->precio_venta * $pCantidad }}€</td>
                                        <tr>
                                    @endif
                                @endforeach
                            <tbody>
                        </table>
                    </div>
                @endif
                <div>
                    <div class="col-sm-12 mb-3 row d-flex align-items-center">
                        <label for="precio" class="col-sm-3 col-form-label">Precio</label>
                        <div class="col-sm-10">
                            <input type="number" wire:model="precio" class="form-control" name="precio"
                                id="precio" disabled>
                            @error('fecha_emision')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>


            <br>
            <div class="card">
                <h5 class="card-header">Buscador de artículos</h5>
                <div class="card-body">
                    <div class="mb-3 row d-flex align-items-center">
                        @if ($producto_seleccionado != null)
                            <label for="prod_sel" class="col-sm-2 col-form-label">Producto seleccionado:</label>
                            <div class="col-sm-10">
                                <input id="prod_sel" class="form-control" type="text" disabled
                                    value="{{ $productos->where('id', $producto_seleccionado)->first()->cod_producto }} - {{ $productos->where('id', $producto_seleccionado)->first()->descripcion }}" />
                            </div>
                            @if ($productos->where('id', $producto_seleccionado)->first()->mueve_existencias != 0)
                                <label for="exis_sel" class="col-sm-2 col-form-label">Existencias disponibles:</label>
                                <div class="col-sm-10">
                                    <input id="exis_sel" class="form-control" type="text" disabled
                                        value="{{ $existencias_productos->where('cod_producto', $productos->where('id', $producto_seleccionado)->first()->cod_producto)->first()->existencias - (int) $cantidad }}" />
                                </div>
                            @endif
                        @endif
                    </div>
                    <br>
                    <h2>Selección de artículos</h2>
                    <div class="mb-3 row d-flex align-items-center">
                        <div class="col-sm-10" wire:ignore>
                            <select class="form-control" id="select2-producto-edit">
                                <option value="">-- Elige un artículo --</option>
                                @foreach ($productos as $producti)
                                    <option value="{{ $producti->id }}">{{ $producti->cod_producto }} -
                                        {{ $producti->descripcion }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if ($producto_seleccionado != null)
                        <div class="mb-3 row d-flex align-items-center">
                            @if ($productos->where('id', $producto_seleccionado)->first()->mueve_existencias != 0)
                                <label for="cantidad" class="col-sm-2 col-form-label">Cantidad</label>
                                <div class="col-sm-10">
                                    <input type="number" wire:model="cantidad" class="form-control" name="cantidad"
                                        id="cantidad" min="1"
                                        max="{{ $existencias_productos->where('cod_producto', $productos->where('id', $producto_seleccionado)->first()->cod_producto)->first()->existencias }}">
                                    @error('cantidad')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                            <button class="btn btn-outline-primary ml-5" wire:click.prevent="añadirProducto"
                                style="margin-top:10px;">Añadir a la lista</button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="clo-sm-12 mb-3 row d-flex justify-content-around">
                <button type="submit" class="btn btn-primary">Actualizar presupuesto</button>
                <button type="button" wire:click="descargaPdf({{$identificador}})" class="btn btn-info text-white clo-sm-3">Dercargar  PDF</button>
                <button type="button" wire:click="mandarMail({{$identificador}})" class="btn btn-secondary text-white clo-sm-3">Enviar Por Email</button>
            </div>
        </div>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            Livewire.on('editCargado', () => {
                console.log('hola');

                $('#select2-producto-edit').select2({
                    placeholder: "Seleccione un producto"
                });

                $('#select2-producto-edit').on('load', function(e) {
                    var data = $('#select2-producto-edit').select2("val");
                    @this.set('producto_seleccionado', data);
                });

                $('#select2-producto-edit').on('change', function(e) {
                    var data = $('#select2-producto-edit').select2("val");
                    @this.set('producto_seleccionado', data);
                });

                $('#select2-servicio-edit').select2({
                    placeholder: "Localización del servicio"
                });

                $('#select2-servicio-edit').on('load', function(e) {
                    var data = $('#select2-servicio-edit').select2("val");
                    @this.set('servicio', data);
                });

                $('#select2-servicio-edit').on('change', function(e) {
                    var data = $('#select2-servicio-edit').select2("val");
                    @this.set('servicio', data);
                });

                $('#select2-origen-edit').select2({
                    placeholder: "Origen del presupuesto"
                });

                $('#select2-origen-edit').on('load', function(e) {
                    var data = $('#select2-origen-edit').select2("val");
                    @this.set('origen', data);
                });

                $('#select2-origen-edit').on('change', function(e) {
                    var data = $('#select2-origen-edit').select2("val");
                    @this.set('origen', data);
                });

                $('#select2-cliente-edit').select2({
                    placeholder: "Seleccione un cliente"
                });

                $('#select2-cliente-edit').on('load', function(e) {
                    var data = $('#select2-cliente-edit').select2("val");
                    @this.set('cliente_id', data);
                });

                $('#select2-cliente-edit').on('change', function(e) {
                    var data = $('#select2-cliente-edit').select2("val");
                    @this.set('cliente_id', data);
                });

                $('#select2-matricula-edit').select2({
                    placeholder: "Seleccione un Vehiculo"
                });

                $('#select2-matricula-edit').on('load', function(e) {
                    var data = $('#select2-matricula-edit').select2("val");
                    @this.set('matricula', data);
                });

                $('#select2-matricula-edit').on('change', function(e) {
                    var data = $('#select2-matricula-edit').select2("val");
                    @this.set('matricula', data);
                });
            });
        });
    </script>
</div>
