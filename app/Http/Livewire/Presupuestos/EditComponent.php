<?php

namespace App\Http\Livewire\Presupuestos;

use App\Models\Almacen;
use App\Models\Presupuesto;
use Carbon\Carbon;
use App\Models\Clients;
use App\Models\Vehiculo;
use App\Models\ListaAlmacen;
use App\Models\Trabajador;
use App\Models\Reserva;
use Barryvdh\DomPDF\Facade\Pdf;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use App\Models\Productos;
use App\Mail\PresupuestoMail;



class EditComponent extends Component
{
    use LivewireAlert;

    public $identificador;

    public $numero_presupuesto;
    public $vehiculoSeleccionado;
    public $vehiculosCliente = [];
    public $fecha_emision;
    public $cliente_id = 0; // 0 por defecto por si no se selecciona ninguna
    public $matricula;
    public $kilometros;
    public $trabajador_id = 0; // 0 por defecto por si no se selecciona ninguna
    public $precio = 0;
    public $origen;
    public $marca;
    public $modelo;

    public $observaciones = "";
    public $clientes;
    public $trabajadores;

    public $lista = []; // Se usa para generar factura de cliente o particular
    public $listaArticulos; // Para mostrar los inputs del alumno o empresa

    public $producto_seleccionado;
    public $servicio;

    public $producto;
    public $productos;

    public $almacenes;

    public $cantidad;
    public $existencias_productos;

    public $vehiculo_renting;
    public $estado_pago;

    public function mount()
    {
        $presupuestos = Presupuesto::find($this->identificador);
        $this->clientes = Clients::all(); // datos que se envian al select2
        $this->trabajadores = Trabajador::all(); // datos que se envian al select2
        $this->productos = Productos::all(); // datos que se envian al select2
        $this->almacenes = ListaAlmacen::all();
        $this->existencias_productos = Almacen::all();

        $this->estado_pago = $presupuestos->estado_pago;
        $this->numero_presupuesto = $presupuestos->numero_presupuesto;
        $this->fecha_emision = $presupuestos->fecha_emision;
        $this->cliente_id = $presupuestos->cliente_id;
        $this->trabajador_id = $presupuestos->trabajador_id;
        $this->lista = (array) json_decode($presupuestos->listaArticulos);
        $this->kilometros = $presupuestos->kilometros;
        $this->matricula = $presupuestos->matricula;
        $this->precio = $presupuestos->precio;
        $this->origen = $presupuestos->origen;
        $this->marca = $presupuestos->marca;
        $this->modelo = $presupuestos->modelo;
        $this->observaciones = $presupuestos->observaciones;
        $this->vehiculosCliente = Clients::find($presupuestos->cliente_id)->vehiculos ?? [];

    }

    public function render()
    {
        return view('livewire.presupuestos.edit-component');
    }

    public function updatedClienteId($value)
    {
        $this->vehiculosCliente = Clients::find($value)->vehiculos ?? [];
    }

    public function updatedMatricula($value)
    {
        $this->vehiculoSeleccionado = null; // Resetear el vehículo seleccionado
        if ($value) {
            $this->vehiculoSeleccionado = Vehiculo::where('matricula', $value)->first();
            if ($this->vehiculoSeleccionado) {
                // Aquí actualizas las propiedades del formulario con los datos del vehículo
                $this->marca = $this->vehiculoSeleccionado->marca;
                $this->modelo = $this->vehiculoSeleccionado->modelo;
                $this->kilometros = $this->vehiculoSeleccionado->kilometros;
                $this->vehiculo_renting = $this->vehiculoSeleccionado->vehiculo_renting;
            }
        }
    }


    // Al hacer update en el formulario
    public function update()
    {
        $this->listaArticulos = json_encode($this->lista);
        // Validación de datos
        $this->validate([
            'numero_presupuesto' => 'required',
            'fecha_emision' => 'required',
            'cliente_id' => 'required',
            'trabajador_id' => 'required',
            'matricula' => 'required',
            'listaArticulos' => 'required',
            'precio' => 'required',
            'origen' => 'required',
            'vehiculo_renting' => 'nullable',
            'marca' => 'required',
            'modelo' => 'required',
            'kilometros' => 'nullable',
            'observaciones' => 'nullable',
            'estado_pago' => 'nullable',
        ],
            // Mensajes de error
            [
                'numero_presupuesto.required' => 'El número de presupuesto es obligatorio.',
                'fecha_emision.required' => 'La fecha de emision es obligatoria.',
                'alumno_id.required' => 'El alumno es obligatorio.',
                'curso_id.required' => 'El curso es obligatorio.',
                'detalles.required' => 'Los detalles son obligatorios',
                'precio.required' => 'El precio es obligaorio',
                'estado.required' => 'El estado es obligatorio',
                'observaciones.required' => 'La observación es obligatoria',
            ]);

        // Encuentra el identificador
        $presupuestos = Presupuesto::find($this->identificador);

        // Guardar datos validados
        $presupuestosSave = $presupuestos->update([
            'numero_presupuesto' => $this->numero_presupuesto,
            'fecha_emision' => $this->fecha_emision,
            'cliente_id' => $this->cliente_id,
            'trabajador_id' => $this->trabajador_id,
            'matricula' => $this->matricula,
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'precio' => $this->precio,
            'origen' => $this->origen,
            'listaArticulos' => $this->listaArticulos,
            'vehiculo_renting' => $this->vehiculo_renting,
            'kilometros' => $this->kilometros,
            'observaciones' => $this->observaciones,
            'estado_pago' => $this->estado_pago,

        ]);

        if ($presupuestosSave) {

            foreach ($this->lista as $pro => $cantidad) {
                $reserva = Reserva::where('presupuesto_id',$this->identificador)->where('producto_id',$pro)->first();
                if (Productos::where('id', $pro)->first()->mueve_existencias == 1){
                    if(isset($reserva)){
                        $reserva->cantidad = $cantidad;
                        $reserva->update();
                    }else{
                        $reserva = Reserva::create();
                        $reserva->cantidad = $cantidad;
                        $reserva->estado = "Pendiente";
                        $reserva->presupuesto_id = $this->identificador;
                        $reserva->producto_id = $pro;
                        $reserva->save();
                    }
                }
            }

            $this->alert('success', '¡Presupuesto actualizado correctamente!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
                'showConfirmButton' => true,
                'onConfirmed' => 'confirmed',
                'confirmButtonText' => 'ok',
                'timerProgressBar' => true,
            ]);
        } else {
            $this->alert('error', '¡No se ha podido guardar la información del presupuesto!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
            ]);
        }

        session()->flash('message', 'Presupuesto actualizado correctamente.');

        $this->emit('productUpdated');
    }

      // Eliminación
      public function destroy(){

        $this->alert('warning', '¿Seguro que desea borrar el presupuesto? No hay vuelta atrás', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'confirmDelete',
            'confirmButtonText' => 'Sí',
            'showDenyButton' => true,
            'denyButtonText' => 'No',
            'timerProgressBar' => true,
        ]);

    }

    // Función para cuando se llama a la alerta
    public function getListeners()
    {
        return [
            'confirmed',
            'confirmDelete'
        ];
    }

    // Función para cuando se llama a la alerta
    public function confirmed()
    {
        // Do something
        return redirect()->route('presupuestos.index', ['tab' => 'tab1']);

    }
    // Función para cuando se llama a la alerta
    public function confirmDelete()
    {
        $presupuesto = Presupuesto::find($this->identificador);
        $presupuesto->delete();
        return redirect()->route('presupuestos.index', ['tab' => 'tab1']);

    }

    public function numeroPresupuesto(){
        $fecha = new Carbon($this->fecha_emision);
        $year = $fecha->year;
        $presupuestos = Presupuesto::all();
        $contador = 1;
        foreach($presupuestos as $presupuesto){
            $fecha2 = new Carbon($presupuesto->fecha_emision);
            $year2 = $fecha2->year;
            if($year == $year2){
                if($fecha->gt($fecha2)){
                    $contador++;
                }
            }
        }

        if($contador < 10){
            $this->numero_presupuesto = "0" . $contador . "/" . $year;
        } else{
            $this->numero_presupuesto = $contador . "/" . $year;
        }

    }

    public function añadirProducto()
    {
        if ($this->producto_seleccionado != null) {
            $producto = Productos::where('id', $this->producto_seleccionado)->firstOrFail();

            if ($producto->mueve_existencias == 0) {
                if (!isset($this->lista[$this->producto_seleccionado])) {
                    $this->lista[$this->producto_seleccionado] = 1;
                } else {
                    $this->alert('info', "Ya has añadido este servicio.");
                }
            } else {
                $almacen = Almacen::where('cod_producto', $producto->cod_producto)->firstOrFail();

                if ($almacen->existencias >= 1) {
                    if ($almacen->existencias >= $this->cantidad) {
                        if (isset($this->lista[$this->producto_seleccionado])) {
                            if ($this->lista[$this->producto_seleccionado] + $this->cantidad > $almacen->existencias) {
                                $this->lista[$this->producto_seleccionado] = $almacen->existencias;
                                $this->alert('warning', "¡Estás intentando añadir más allá de las existencias!");
                            } else {
                                $this->lista[$this->producto_seleccionado] += $this->cantidad;
                            }
                        } else {
                            $this->lista[$this->producto_seleccionado] = $this->cantidad;
                        }
                    } else {
                        $this->alert('warning', "¡Estás intentando añadir más allá de las existencias!");
                    }
                } else {
                    $this->alert('warning', "¡Artículo sin existencias!");
                }
            }
            $this->precio = 0;
            foreach ($this->lista as $prod => $valo) {
                $anadir = Productos::where('id', $prod)->firstOrFail()->precio_venta;
                $this->precio += ($anadir * $valo);
            }
        }
    }

    public function reducir($id)
    {

        if (isset($this->lista[$id])) {
            if ($this->lista[$id] - 1 <= 0) {
                $this->precio -= ((Productos::where('id', $id)->first()->precio_venta) * $this->lista[$id]);
                unset($this->lista[$id]);
            } else {
                $this->lista[$id] -= 1;
                $this->precio -= ((Productos::where('id', $id)->first()->precio_venta));
            }
        } else {
            $this->alert('warning', "Este producto no está en la lista");
        }
    }

    public function aumentar($id)
    {
        $producto = Productos::where('id', $id)->first();
        if (isset($this->lista[$id])) {

            if ($producto->mueve_existencias == 0) {
                $this->alert('info', "Ya has añadido este servicio.");
            }elseif (($this->lista[$id] + 1) > Almacen::where('cod_producto', $producto->cod_producto)->first()->existencias) {
                $this->alert('warning', "Existencias máximas alcanzadas.");
            } else {
                $this->lista[$id] += 1;
                $this->precio += ((Productos::where('id', $id)->first()->precio_venta));
            }
        } else {
            $this->alert('warning', "Este producto no está en la lista");
        }
    }

    public function CrearPdf($presupuesto)
    {
        $productos = Productos::all();
        $lista = [];
        $lista = (array) json_decode($presupuesto->listaArticulos, true);
        $cliente = Clients::findOrFail($presupuesto->cliente_id); // Asumiendo que cliente_id es uniforme en todos los presupuestos
        // Cargar la vista adecuada y pasar los datos necesarios
        $pdf = PDF::loadView('livewire.presupuestos.pdf-component', compact( 'presupuesto', 'cliente', 'lista', 'productos'));
        return $pdf;
    }
    public function descargaPdf($id){
        $presupuesto = Presupuesto::findOrFail($id);
        $pdf = $this->CrearPdf($presupuesto);
        // Devolver el PDF para descargar con un nombre de archivo personalizado
        // return $pdf->download('Presupuesto -'.$presupuesto->numero_presupuesto.'.pdf');
        $nombre = str_replace("/", "-", $presupuesto->numero_presupuesto);
        return response()->streamDownload(fn () => print($pdf->output()),'Presupuesto_'.$nombre.'.pdf');
    }

    public function mandarMail($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $cliente = Clients::findOrFail($presupuesto->cliente_id);
        $pdf = $this->CrearPdf($presupuesto); // Asumiendo que existe un método para generar el PDF
        $enviado = Mail::to($cliente->email)->send(new PresupuestoMail($presupuesto,$pdf,$cliente));
        if(isset( $enviado)){
        $this->alert('success', 'Factura enviada correctamente!',[
            'position' => 'center',
            'timer' => 3000,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'confirmed',
            'confirmButtonText' => 'Aceptar',
            'showDenyButton' => false,
        ]);
        } else {
            $this->alert('error', '¡No se ha podido enviadar la factura!',[
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
                'showConfirmButton' => true,
                'onConfirmed' => 'confirmed',
                'confirmButtonText' => 'Aceptar',
                'showDenyButton' => false,
            ]);
        }
    }
}

