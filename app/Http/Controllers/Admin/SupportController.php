<?php

namespace App\Http\Controllers\Admin;

use App\DTO\{
    CreateSupportDTO,
    UpdateSupportDTO
};
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateSupport;
use App\Models\Support;
use App\Services\SupportService;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\String_;


class SupportController extends Controller
{
    public function __construct(protected SupportService $service)
    {}

    public function index(Request $request)
    {
        $supports = $this->service->getAll($request->filter);
        return view('admin/supports/index', compact('supports'));
    }    
    
    public function show(string $id)
    {
        if (!$support = $this->service->findOne($id)){
            return back();
        }
        return view('admin/supports/show', compact('support'));
    }
    
    public function create()
    {
        return view('admin/supports/create');
    }

    public function store(StoreUpdateSupport $request, Support $support)
    {
        $this->service->new(CreateSupportDTO::makeFromRequest($request));
        return redirect()->route('supports.index');
    }
    
    public function edit(string $id)
    {
        if(!$support = $this->service->findOne($id)){
            return back();
        }
        return view('admin/supports/edit', compact('support'));
    }

    public function update(StoreUpdateSupport $request, Support $support, string $id)
    {
        $support = $this->service->update(UpdateSupportDTO::makeFromRequest($request));
        if(!$support){
            return back();
        }
        return redirect()->route('supports.index');
    }
    
    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('supports.index');
    }
}
