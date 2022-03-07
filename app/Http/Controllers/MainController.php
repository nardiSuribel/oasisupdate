<?php
namespace app\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\eventos_model;
use Carbon\Carbon;
class MainController extends Controller
{
    public function get_eventos(){ //Obtener si hay eventos proximos o eventos pasados
        $dateNow=Carbon::now(); //Fecha Actual
        //Join para juntar tabla eventos y eventos categoria
        $eventos = eventos_model::join('eventos_categoria', 'eventos.idcategoria', '=', 'eventos_categoria.ideventos_categoria')
        ->where('ideventos_categoria', '=', '11') //Filtrar eventos por categoria = "Oasis Dance U"
        ->where('id_estado', '=', '2') // ->where("a.id_estado = 2")// dos solo es para testear
        ->where('fechacompleja', '>=', $dateNow) //Donde sean despues de la fecha actual
        ->orderBy('fechacompleja')
        ->get();
        if(count($eventos) > 0){ //Si hay proximos 
            $langArray = array('es' => 'es_ES.utf8', 'en' => 'en_US.utf8');
            // REVISAR setlocale(LC_TIME, $langArray[$this->config->item('language_abbr')]);
            //Formato para fecha
            foreach ($eventos as $key => $evento) {
                $dt = Carbon::parse($evento->fechacompleja);
                $evento->mes = utf8_encode($dt->formatLocalized('%B'));
                $evento->fecha_booking_tickets= $dt->format('Y-m-d');
                $evento->fecha_booking_in= $dt->subDays(2)->format('d/m/Y');
                $evento->fecha_booking_out= $dt->addDays(4)->format('d/m/Y');
            }
            return $eventos;
        }else{ //Si sòlo hay eventos pasados
            //Join para tabla eventos y tabla eventos categoria
            $eventos = eventos_model::join('eventos_categoria', 'eventos.idcategoria', '=', 'eventos_categoria.ideventos_categoria')
            ->join('eventos_pasados', 'eventos.ideventos', '=', 'eventos_pasados.id_eventos', 'inner') //Join de tabla eventos pasados y eventos donde coincida el id del evento
            ->join('eventos_pasados_video', 'eventos_pasados.id_eventos_pasados', '=', 'eventos_pasados_video.eventos_pasados_video_id', 'left') //Join de tabla eventos pasados videos y eventos pasados donde coincidan en el id del evento
            ->join('eventos_pasados_galeria', 'eventos_pasados.id_eventos_pasados', '=', 'eventos_pasados_galeria.eventos_pasados_id')
            ->where('ideventos_categoria', '=', '11')
            ->where('fechacompleja', '<=', $dateNow)
            // ->groupBy('anno')
            ->orderBy('fechacompleja', 'desc')
            ->get();
            $langArray = array('es' => 'es_ES.utf8', 'en' => 'en_US.utf8');
            // REVISAR setlocale(LC_TIME, $langArray[$this->config->item('language_abbr')]);
            foreach ($eventos as $key => $result) { //Formato para fecha
                $dt = Carbon::parse($result['fechacompleja']);
                $eventos[$key]['mes'] = utf8_encode($dt->formatLocalized('%B'));
                if(preg_match('#<iframe[^>]+src=([\'"])(.*)\1#isU', $eventos[$key]['video'], $matches)){
                    $src = $matches[2];
                    preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $src, $videoId);
                    $eventos[$key]['video_id']=$videoId[1];
                }
            }
            return $eventos;
        }
    }
    public function index(){
        $event = $this->get_eventos();
        dd($event); 
        
        $lang = config('app.locale');
		// $data["evento"]= 
		// $data["annos"]= $this->eventos_model->get_menu_annos();
		// $data["menu_artistas"]= $this->eventos_model->get_current_artists();
		// $this->load->view('/layouts/header',$data);
        // if ($this->eventos_model->checkeventos_upcoming()==TRUE){
        //     $this->load->view('home/home');
		// 	// $this->load->view('/forms/daypass');
		// 	// $this->load->view('/layouts/end');
        // }else{
        //     $this->load->view('/home/home_no_events');
		// 	// $this->load->view('/layouts/footer');
        // }
    }
}
