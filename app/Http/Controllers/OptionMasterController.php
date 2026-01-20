<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\OptionMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class OptionMasterController extends Controller
{
    /**
     * List Option Masters + Options (DataTable)
     */
    public function index(Request $request)
    {
        
        $optionmasters = OptionMaster::orderBy('name')->get();

        if ($request->ajax()) {

            $query = Option::query();

            if ($request->filled('id')) {
                $query->where('option_master_id', $request->id);
            }

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('display_image', function ($row) {
                    if ($row->display_image) {
                        return '<img src="' . asset('storage/' . $row->display_image) . '" width="40" height="40" class="rounded">';
                    }
                    return '-';
                })

                ->addColumn('default', function ($row) {
                    return $row->default == 1
                        ? '<span class="badge bg-success">Yes</span>'
                        : '<span class="badge bg-secondary">No</span>';
                })

                ->addColumn('status', function ($row) {
                    $class = $row->status == 1 ? 'badge bg-success' : 'badge bg-danger';
                    $label = $row->status == 1 ? 'Active' : 'Inactive';

                    return '<span style="cursor:pointer"
                        onclick="changeStatus(' . $row->id . ')"
                        class="' . $class . '">' . $label . '</span>';
                })

                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary"
                            onclick="editOptions(
                                ' . $row->id . ',
                                \'' . e($row->name) . '\',
                                \'' . e($row->value) . '\',
                                ' . $row->status . ',
                                ' . $row->default . '
                            )">
                            <i class="feather-edit"></i>
                        </button>
                    ';
                })

                ->rawColumns(['display_image', 'status', 'default', 'action'])
                ->make(true);
        }

        return view('config-settings.option-master.index', compact('optionmasters'));
    }

    public function inlineStore(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:option_masters,name',
    ]);

    $option = OptionMaster::create([
        'name' => $request->name,
    ]);

    return response()->json([
        'id'   => $option->id,
        'name' => $option->name,
    ]);
}


    /**
     * Store new option
     */
    public function store(Request $request)
    {
        $request->validate([
            'option_master_module_id' => 'required|exists:option_masters,id',
            'display_name'            => 'required|string|max:255',
            'display_value'           => 'nullable|string|max:255',
            'status'                  => 'required|in:0,1',
            'is_default'              => 'required|in:0,1',
            'display_image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $option = new Option();
        $option->option_master_id = $request->option_master_module_id;
        $option->name             = $request->display_name;
        $option->value            = $request->display_value;
        $option->status           = $request->status;
        $option->default          = $request->is_default;

        if ($request->hasFile('display_image')) {
            $option->display_image = $request->file('display_image')
                ->store('options', 'public');
        }

        $option->save();

        return response()->json(['success' => true]);
    }

    /**
     * Update option
     */
    public function update(Request $request)
    {
        $request->validate([
            'id'                      => 'required|exists:options,id',
            'option_master_module_id' => 'required|exists:option_masters,id',
            'display_name'            => 'required|string|max:255',
            'display_value'           => 'nullable|string|max:255',
            'status'                  => 'required|in:0,1',
            'is_default'              => 'required|in:0,1',
            'display_image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $option = Option::findOrFail($request->id);

        $option->option_master_id = $request->option_master_module_id;
        $option->name             = $request->display_name;
        $option->value            = $request->display_value;
        $option->status           = $request->status;
        $option->default          = $request->is_default;

        if ($request->hasFile('display_image')) {

            if ($option->display_image && Storage::disk('public')->exists($option->display_image)) {
                Storage::disk('public')->delete($option->display_image);
            }

            $option->display_image = $request->file('display_image')
                ->store('options', 'public');
        }

        $option->save();

        return response()->json(['success' => true]);
    }

    /**
     * Change option status
     */
    public function changeStatus($id)
    {
        $option = Option::findOrFail($id);
        $option->status = $option->status == 1 ? 0 : 1;
        $option->save();

        return response()->json(['success' => true]);
    }
}