<?php

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use App\Models\Trabajador;
use App\Notifications\AlertaSistema;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $filtro = $request->query('filtro', 'todas');
        $filtrosValidos = ['todas', 'postulacion', 'oferta', 'sistema'];
        if (!in_array($filtro, $filtrosValidos)) $filtro = 'todas';

        $typeClassMap = [
            'oferta'      => 'NuevaOfertaMatch',
            'postulacion' => 'PostulacionActualizada',
            'sistema'     => 'AlertaSistema',
        ];

        $query = $user->notifications();
        if ($filtro !== 'todas' && isset($typeClassMap[$filtro])) {
            $query->where('type', 'like', '%' . $typeClassMap[$filtro]);
        }

        $notificaciones = $query->orderBy('created_at', 'desc')->paginate(15);

        $allForCounts = $user->notifications()->get(['id', 'type', 'read_at']);
        $counts = [
            'todas'       => ['total' => 0, 'no_leidas' => 0],
            'postulacion' => ['total' => 0, 'no_leidas' => 0],
            'oferta'      => ['total' => 0, 'no_leidas' => 0],
            'sistema'     => ['total' => 0, 'no_leidas' => 0],
        ];
        foreach ($allForCounts as $n) {
            $basename = class_basename($n->type);
            if ($basename === 'NuevaOfertaMatch') {
                $counts['oferta']['total']++;
                if (is_null($n->read_at)) $counts['oferta']['no_leidas']++;
            } elseif ($basename === 'PostulacionActualizada') {
                $counts['postulacion']['total']++;
                if (is_null($n->read_at)) $counts['postulacion']['no_leidas']++;
            } elseif ($basename === 'AlertaSistema') {
                $counts['sistema']['total']++;
                if (is_null($n->read_at)) $counts['sistema']['no_leidas']++;
            }
            $counts['todas']['total']++;
            if (is_null($n->read_at)) $counts['todas']['no_leidas']++;
        }

        $this->chequearPerfilIncompleto($user->trabajador);

        return view('trabajador.notificaciones', compact('notificaciones', 'filtro', 'filtrosValidos', 'counts'));
    }

    public function marcarLeida(string $id)
    {
        auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
        return back();
    }

    public function marcarTodasLeidas()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    private function chequearPerfilIncompleto(?Trabajador $trabajador): void
    {
        if (!$trabajador) return;

        $faltantes = [];

        if (empty($trabajador->descripcion)) $faltantes[] = 'descripción profesional';
        if (empty($trabajador->telefono)) $faltantes[] = 'teléfono de contacto';
        if (empty($trabajador->imagen_perfil)) $faltantes[] = 'foto de perfil';
        if (empty($trabajador->curriculum_pdf)) $faltantes[] = 'curriculum vitae';
        if ($trabajador->especialidades()->count() === 0) $faltantes[] = 'especialidades';
        if (empty($trabajador->provincia_preferencia_id)) $faltantes[] = 'provincia de preferencia';

        if (empty($faltantes)) return;

        $titulo = 'Completá tu perfil para recibir mejores ofertas';
        $mensaje = 'Faltan completar: ' . implode(', ', $faltantes) . '.';
        $url = route('trabajador.perfil.edit');

        $alreadyExists = auth()->user()->notifications()
            ->where('type', 'App\Notifications\AlertaSistema')
            ->where('data->titulo', $titulo)
            ->whereNull('read_at')
            ->exists();

        if (!$alreadyExists) {
            auth()->user()->notify(new AlertaSistema($titulo, $mensaje, $url));
        }
    }
}
