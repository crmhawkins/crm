<?php

namespace App\Http\Livewire\Informes;

use App\Models\Fabricante;
use App\Models\Presupuesto;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use App\Models\Productos;
use App\Models\Neumatico;
use App\Models\Almacen;
use App\Models\CategoriaInforme;
use App\Models\Clients;
use App\Models\Ecotasa;
use App\Models\GrupoInformes;
use App\Models\ListaAlmacen;
use App\Models\TipoProducto;
use App\Models\ProductosCategories;
use App\Models\Proveedores;
use App\Models\Reserva;
use App\Models\TipoInforme;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use PDF;

class Index extends Component
{
    use LivewireAlert;
    public $tiposSeleccionados = [];
    public $categoriasSeleccionadas = [];
    public $tipos_producto;
    public $presupuestos;
    public $categorias;
    public $fabricantes;
    public $fabricantesSeleccionados;
    public $catTodos = 0;
    public $tipoTodos = 0;
    public $tipo_producto = "";

    public $art_codigo = "";
    public $art_desc = "";


    public $busqueda_res_rod = "";

    public $busqueda_ag_moj = "";

    public $busqueda_em_ruido = "";
    public $busqueda_ancho = "";

    public $busqueda_serie = "";

    public $busqueda_llanta = "";
    public $busqueda_ic = "";

    public $busqueda_cv = "";
    public $busqueda_categoria = "";

    public $productos;
    public $neumaticos;
    public $almacenes;
    public $listAlmacenes;
    public $tasas;
    public $tiposInforme;
    public $tipo_informe;
    public $fabricante;
    public $proveedores;
    public $proveedor;
    public $categoriasInforme;
    public $fecha_inicio;
    public $fecha_fin;
    public $servicio;
    public $cliente;
    public $clientes;
    public $matricula;
    public $categoria_informe = 1;
    public $art_busc = 0;
    public $pagina;
    public $filtros;
    protected $tabla;
    public $porPagina = 10;

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'provideTipos' => 'getTiposPadre',
        'provideCategorias' => 'getCategoriasPadre',
    ];


    public function mount()
    {
        $this->categoriasInforme = CategoriaInforme::all();
        $this->neumaticos = Neumatico::all();
        $this->almacenes = Almacen::all();
        $this->clientes = Clients::all();
        $this->listAlmacenes = ListaAlmacen::all();
        $this->tasas = Ecotasa::all();
        $this->fabricantes = Fabricante::all();
        $this->proveedores = Proveedores::all();
        $this->presupuestos = Presupuesto::all();
        $this->tiposInforme = TipoInforme::all();
    }


    public function render()
    {
        if (in_array(2, $this->tiposSeleccionados)) {
            $tipoDosProductos = Productos::where('tipo_producto', 2);
            $otrosProductos = Productos::where('tipo_producto', '<>', 2)->whereIn('tipo_producto', $this->tiposSeleccionados)->whereIn('categoria_id', $this->categoriasSeleccionadas);
            $this->productos = $tipoDosProductos->union($otrosProductos)->get();
        } else {
            $this->productos = Productos::whereIn('tipo_producto', $this->tiposSeleccionados)->whereIn('categoria_id', $this->categoriasSeleccionadas)->get();
        }
        $this->tabla = $this->pagination($this->productos);
        return view('livewire.informes.index', [
            'tabla' => $this->tabla
        ]);
    }
    public function seleccionarProducto($id)
    {
        $this->emit("seleccionarProducto", $id);
    }

    public function getTiposPadre($tipos_informe)
    {
        if (empty($this->tiposSeleccionados)) {
            $this->tiposSeleccionados = $tipos_informe;
        }
    }

    public function getCategoriasPadre($categorias_informe)
    {
        if (empty($this->categoriasSeleccionadas)) {
            $this->categoriasSeleccionadas = $categorias_informe;
        }
    }


    public function pagination(Collection $data)
    {
        $items = $data->forPage($this->pagina, $this->porPagina);
        $totalResults = $data->count();

        return new LengthAwarePaginator(
            $items,
            $totalResults,
            $this->porPagina,
            $this->pagina,
            // Esta parte (options) la copie de lo que hace por defecto el paginador de Laravel haciendo un dd()
            [
                'path' => url()->current(),
                'pageName' => 'pagina',
            ]
        );
    }
    public function updatedCategoria_informe()
    {
        $this->emit('refreshTomSelect');
    }
    public function updatedTipo_informe()
    {
        $this->emit('refreshTomSelect');
    }

    public function updatedCliente()
    {
        $this->emit('refreshTomSelect');
    }

    public function generarInforme()
    {
        $datos = [];
        switch ($this->tipo_informe) {
            case '1':
                $grupos = GrupoInformes::all();
                foreach ($grupos as $grupo) {
                    $tipos_grupo = json_decode($grupo->tipos_producto, true);
                    $categorias_grupo = json_decode($grupo->categorias, true);
                    if (in_array(2, $tipos_grupo)) {
                        $productos = Productos::whereIn('categoria_id', $categorias_grupo)->orWhere('tipo_producto', 2)->get();
                        $producto_IDs = [];
                        foreach ($productos as $producto) {
                            if (!in_array($producto->id, $producto_IDs)) {
                                $producto_IDs[] = $producto->id;
                            }
                        }
                    } else {
                        $productos = Productos::whereIn('categoria_id', $categorias_grupo)->get();
                        $producto_IDs = [];
                        foreach ($productos as $producto) {
                            if (!in_array($producto->id, $producto_IDs)) {
                                $producto_IDs[] = $producto->id;
                            }
                        }
                    }
                    $productos_vendidos = Reserva::whereIn('producto_id', $producto_IDs)
                        ->where('estado', 'Aceptado')
                        ->whereBetween('updated_at', [$this->fecha_inicio, $this->fecha_fin])
                        ->get();

                    $ventas = $productos_vendidos->sum('cantidad');
                    $importe = 0;

                    foreach ($productos_vendidos as $prod) {
                        $importe += (Productos::find($prod->producto_id)->precio_venta * $prod->cantidad);
                    }

                    $datos[] = [
                        'grupo_id' => $grupo->id,
                        'ventas' => $ventas,
                        'importe' => $importe,
                    ];
                }
                break;

            case '2':
                $grupos = GrupoInformes::all();
                foreach ($grupos as $grupo) {
                    $tipos_grupo = json_decode($grupo->tipos_producto, true);
                    $categorias_grupo = json_decode($grupo->categorias, true);

                    $producto_IDs = [];
                    $datosProductos = [];

                    if (in_array(2, $tipos_grupo)) {
                        $productos = Productos::whereIn('categoria_id', $categorias_grupo)->orWhere('tipo_producto', 2)->get();
                    } else {
                        $productos = Productos::whereIn('categoria_id', $categorias_grupo)->get();
                    }

                    foreach ($productos as $producto) {
                        if (!in_array($producto->id, $producto_IDs)) {
                            $producto_IDs[] = $producto->id;
                        }
                    }

                    $productos_vendidos = Reserva::whereIn('producto_id', $producto_IDs)
                        ->where('estado', 'Aceptado')
                        ->whereBetween('updated_at', [$this->fecha_inicio, $this->fecha_fin])
                        ->get();

                    $ventas = $productos_vendidos->sum('cantidad');
                    $importe = 0;

                    foreach ($productos_vendidos as $prod) {
                        $producto = Productos::find($prod->producto_id);
                        $importe += ($producto->precio_venta * $prod->cantidad);

                        $datosProductos[] = [
                            'cod_producto' => $producto->cod_producto,
                            'descripcion' => $producto->descripcion,
                            'precio_baremo' => $producto->precio_baremo,
                            'precio_venta' => $producto->precio_venta,
                            'cantidad' => $prod->cantidad,
                        ];
                    }

                    $datos[] = [
                        'grupo_id' => $grupo->id,
                        'ventas' => $ventas,
                        'importe' => $importe,
                        'productos' => $datosProductos,
                    ];
                }
                break;

            case '3':
                $codigo = $this->art_busc == 0 ? $this->art_codigo : $this->art_desc;

                $producto = Productos::where(
                    $this->art_busc == 0 ? 'cod_producto' : 'descripcion',
                    $codigo
                )->first();

                if ($producto) {
                    $articulos = Reserva::where('producto_id', $producto->id)
                        ->pluck('presupuesto_id')
                        ->all();

                    $albaranes = Presupuesto::whereIn('id', $articulos)
                        ->whereBetween('fecha_emision', [$this->fecha_inicio, $this->fecha_fin])
                        ->get();
                } else {
                    $albaranes = Presupuesto::whereBetween('fecha_emision', [$this->fecha_inicio, $this->fecha_fin])
                        ->get();
                }

                foreach ($albaranes as $albaran) {
                    $productos = Reserva::where('presupuesto_id', $albaran->id)->get();
                    $importe = 0;
                    $datosProductos = [];
                    foreach ($productos as $prod) {
                        $producto = Productos::find($prod->producto_id);
                        $importe += ($producto->precio_venta * $prod->cantidad);
                        $datosProductos[] = [
                            'cod_producto' => $producto->cod_producto,
                            'descripcion' => $producto->descripcion,
                            'precio_baremo' => $producto->precio_baremo,
                            'descuento' => $producto->descuento,
                            'ecotasa' => $producto->ecotasa,
                            'precio_venta' => $producto->precio_venta,
                            'cantidad' => $prod->cantidad,
                        ];
                    }
                    $cliente = Clients::where('id', $albaran->cliente_id)->first()->nombre;
                    $iva = ($importe * 0.21);
                    $datos[] = [
                        'codigo' => $codigo,
                        'tipo_codigo' => $this->art_busc,
                        'cliente' => $cliente,
                        'albaran' => $albaran->numero,
                        'fecha' => $albaran->fecha_emision,
                        'matricula' => $albaran->matricula,
                        'servicio' => $albaran->servicio,
                        'iva' => $iva,
                        'total' => $importe,
                        'productos' => $datosProductos,
                    ];
                }

                break;

            case '4':

                $albaranes = Presupuesto::where('matricula', $this->matricula)->whereBetween('fecha_emision', [$this->fecha_inicio, $this->fecha_fin])
                    ->get();

                foreach ($albaranes as $albaran) {
                    $productos = Reserva::where('presupuesto_id', $albaran->id)->get();
                    $importe = 0;
                    $datosProductos = [];
                    foreach ($productos as $prod) {
                        $producto = Productos::find($prod->producto_id);
                        $importe += ($producto->precio_venta * $prod->cantidad);
                        $datosProductos[] = [
                            'cod_producto' => $producto->cod_producto,
                            'descripcion' => $producto->descripcion,
                            'precio_baremo' => $producto->precio_baremo,
                            'descuento' => $producto->descuento,
                            'ecotasa' => $producto->ecotasa,
                            'precio_venta' => $producto->precio_venta,
                            'cantidad' => $prod->cantidad,
                        ];
                    }
                    $cliente = Clients::where('id', $albaran->cliente_id)->first()->nombre;
                    $iva = ($importe * 0.21);
                    $datos[] = [
                        'cliente' => $cliente,
                        'albaran' => $albaran->numero,
                        'fecha' => $albaran->fecha_emision,
                        'matricula' => $albaran->matricula,
                        'servicio' => $albaran->servicio,
                        'iva' => $iva,
                        'total' => $importe,
                        'productos' => $datosProductos,
                    ];
                }

                break;

            case '5':

                $albaranes = Presupuesto::where('cliente_id', $this->cliente)->whereBetween('fecha_emision', [$this->fecha_inicio, $this->fecha_fin])
                    ->get();

                foreach ($albaranes as $albaran) {
                    $productos = Reserva::where('presupuesto_id', $albaran->id)->get();
                    $importe = 0;
                    $datosProductos = [];
                    foreach ($productos as $prod) {
                        $producto = Productos::find($prod->producto_id);
                        $importe += ($producto->precio_venta * $prod->cantidad);
                        $datosProductos[] = [
                            'cod_producto' => $producto->cod_producto,
                            'descripcion' => $producto->descripcion,
                            'precio_baremo' => $producto->precio_baremo,
                            'descuento' => $producto->descuento,
                            'ecotasa' => $producto->ecotasa,
                            'precio_venta' => $producto->precio_venta,
                            'cantidad' => $prod->cantidad,
                        ];
                    }
                    $iva = ($importe * 0.21);
                    $datos[] = [
                        'cliente' => $this->cliente,
                        'albaran' => $albaran->numero,
                        'fecha' => $albaran->fecha_emision,
                        'matricula' => $albaran->matricula,
                        'servicio' => $albaran->servicio,
                        'iva' => $iva,
                        'total' => $importe,
                        'productos' => $datosProductos,
                    ];
                }

                break;
            case '6':
                $year = Carbon::now()->format('Y');
                $data = [];
                $dataMedia = [];


                for ($i = 1; $i <= 12; $i++) {
                    if(Presupuesto::whereBetween('fecha_emision', [$year . "-" . $i . "-1", $year . "-" . $i . "-31"])->sum('precio') != null){
                        $data[$i] = Presupuesto::whereBetween('fecha_emision', [$year . "-" . $i . "-1", $year . "-" . $i . "-31"])->sum('precio');
                    }else{
                        $data[$i] = 0;
                    }

                    if(Presupuesto::whereBetween('fecha_emision', [$year . "-" . $i . "-1", $year . "-" . $i . "-31"])->avg('precio') != null){
                        $dataMedia[$i] = Presupuesto::whereBetween('fecha_emision', [$year . "-" . $i . "-1", $year . "-" . $i . "-31"])->avg('precio');
                    }else{
                        $dataMedia[$i] = 0;
                    }
                }


                $chartData = [
                    'type' => 'line',
                    'data' => [
                        'labels' => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],  // meses, por ejemplo
                        'datasets' => [[
                            'label' => 'Ventas mensuales',
                            'data' => [$data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11], $data[12]], // datos de ventas, por ejemplo
                            'fill' => false,
                            'borderColor' => 'blue'
                        ], [
                            'label' => 'Ventas acumuladas',
                            'data' => [$dataMedia[1], $dataMedia[2], $dataMedia[3], $dataMedia[4], $dataMedia[5], $dataMedia[6], $dataMedia[7], $dataMedia[8], $dataMedia[9], $dataMedia[10], $dataMedia[11], $dataMedia[12]],  // datos de tendencia, por ejemplo
                            'fill' => false,
                            'borderColor' => 'red'
                        ]]
                    ],
                    'options' => [
                        'responsive' => true,
                        'title' => [
                            'display' => true,
                            'text' => 'Gráfico de Ventas y Tendencia'
                        ]
                    ]
                ];

                $chartDataString = json_encode($chartData);
                $chartDataURL = 'https://quickchart.io/chart?c=' . urlencode($chartDataString);

                $datos['chart'] = $chartDataURL;

                break;
            case '7':
                # code...
                break;
            case '8':
                # code...
                break;
            case '9':
                # code...
                break;


            default:
                # code...
                break;
        }
        if (empty($this->servicio)) {
            $this->servicio = "Todos";
        }
        $this->emitUp('seleccionarProducto', $datos, $this->tipo_informe, $this->fecha_inicio, $this->fecha_fin, $this->servicio);  // emite el evento a los componentes superiores
    }
}
