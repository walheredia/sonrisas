<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Thematics;

class ThematicsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $thematics = Thematics::orderBy('position', 'ASC')->paginate();

        return view('thematics/index', compact('thematics'));
    }

    public function create() {
        return view('thematics/create');
    }

    public function created() {
        $this->validate(request(), [
            'title' => ['required', 'max:255'],
        ]);

        $data = request()->all();

        $thematic = new Thematics($data);
        $thematic->checkPosition();
        $thematic->save();

        return redirect()->to('thematics/update/'.$thematic->id);
    }

    public function update($id) {
        $thematic = Thematics::findOrFail($id);

        return view('thematics/update', compact('thematic'));
    }

    public function updated($id) {
        $this->validate(request(), [
            'title' => ['required', 'max:255'],
        ]);

        $data = request()->all();

        $thematic = Thematics::findOrFail($id);
        $thematic->fill($data);
        $thematic->save();

        return redirect()->to('thematics/update/'.$thematic->id);
    }

    public function delete($id) {
        $thematic = Thematics::findOrFail($id);
        $thematic->delete();

        $all = Thematics::orderBy('position', 'ASC')->get();
        
        $positionAux = 1;
        foreach ($all as $t) {
            if ($t->position != $positionAux) {
                $t->position = $positionAux;
                $t->save();
            }

            $positionAux++;
        }


        return redirect()->to('thematics');
    }

    public function order() {
        $data = request()->all();

        $new = Thematics::find($data['new']);
        $old = Thematics::find($data['old']);

        $aux = $new->position;
        $new->position = $old->position;
        $new->save();

        $old->position = $aux;
        $old->save();

        return redirect()->to('thematics');
    }
}
