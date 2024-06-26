<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Alertas;
use Illuminate\Support\Facades\Auth;

class ListaAlertas extends Component
{

    public $alertas;

    public function mount()
    {
        $this->alertas = Alertas::where('estado_id',0)->get();

    }

    public function render()
    {
        return view('livewire.lista-alertas');
    }

    public function accion($tipo_id, $alertaId, $referenciaId)
    {
        $alerta = Alertas::findOrFail($alertaId);
        $alerta->estado_id = 1;
        $alerta->save();

        switch ($tipo_id) {
            case 1:
                return redirect()->to('admin/facturas-edit/'.$referenciaId);
            case 2:
                return redirect()->to('admin/pedidos-edit/'.$referenciaId);
            case 3:
                $this->alertas = Alertas::where('user_id', Auth::id())
                                            ->whereNull('leida') // Opcional: Cargar solo notificaciones no leídas
                                            ->get();
                break;
            case 4:
                $this->alertas = Alertas::where('user_id', Auth::id())
                                            ->whereNull('leida') // Opcional: Cargar solo notificaciones no leídas
                                            ->get();
                break;
            case 5:
                $this->alertas = Alertas::where('user_id', Auth::id())
                                            ->whereNull('leida') // Opcional: Cargar solo notificaciones no leídas
                                            ->get();
                break;
            case 6:
                $this->alertas = Alertas::where('user_id', Auth::id())
                                            ->whereNull('leida') // Opcional: Cargar solo notificaciones no leídas
                                            ->get();
                break;
            case 7:
                return redirect()->to('admin/produccion-create');
            default:
                $this->alertas = Alertas::where('user_id', Auth::id())
                                            ->whereNull('leida') // Opcional: Cargar solo notificaciones no leídas
                                            ->get();
            break;
        }
    }
}



