<?php 
namespace App\Http\Controllers;  
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;
use App\MediaType;
 
class MediaTypeController extends Controller 
{     
    public function showmass(){         
        return view("media-types.insert-mass");     
    } 
 
    public function storemass(Request $request){
        $repetidos=[];
        //reglas de validacion
        $reglas = [
            'media-types' => 'required|mimes:csv,txt'
        ];
        //crear validador
        $validador = Validator::make($request->all(),$reglas);
        //validar
        if ($validador->fails()){
            //return $validador->errors()->first('media-types');
            //enviar mensaje de error de validacion a la vista
            return redirect('media-types/insert')->withErrors($validador);
        } else {
            // trasladaar el archivo a las seccion storage del proyecto
            $request->file("media-types")->storeAs('media-types', $request->file("media-types")->getClientOriginalName());
            $ruta = base_path(). '\storage\app\media-types\\'. $request->file('media-types')->getClientOriginalName();
            //return "tipo valido";
            if(($puntero = fopen ($ruta,'r'))!==false){
                $contadora = 0;
                //recorro cada linea del csv utilizando el puntero que representa el archivo
                while (($linea = fgetcsv($puntero))!==false){
                    //Buscar el medio type en $linea [0]:
                    $conteo = MediaType::where('Name','=',$linea[0])->get()->count();
                    //si no hay reistros en mediatype que se llamen igual
                    if ($conteo==0){
                    //crear objeto mediatype
                    $m = new MediaType();
                    //asigno el nombre de mediatype 
                    $m->Name = $linea[0];
                    //grabo en sqlite el nuevo media type
                    $m->save();
                    //aumentar en 1 el contador
                    $contadora++;
                }else{//hay registros del mediatype
                    //agregar una casilla al arreglo repetido
                    $repetidos[] = $linea[0];
                }
            }
                //TODO: poner mensaje de grabacion de carga masiva
                //en la vista
                //si hubo repetidos
                if (count ($repetidos)==0){
                    return redirect ('media-types/insert')
                    ->with('exito',"Carga Masiva de medios realizada, Registros ingresados: $contadora");    
                }else {
                    return redirect ('media-types/insert')
                    ->with("exito","Carga Masiva con las siguientes excepciones: ") 
                    ->with ("repetidos",$repetidos);

                }
               
            }
        }
    
       
        
      }
}