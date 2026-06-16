<?php

namespace App\Http\Controllers;

use App\Models\Pincode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PincodeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        return view('backend/pincode/index', compact('search'));
    }

    public function list(Pincode $pincode)
    {
        $query = $pincode->newQuery();
        $query = $query->select('pincodes.id', 'pincodes.pincode', 'pincodes.area', 'pincodes.minimum_cart_amount', 'pincodes.delivery_charge');
        $data = $this->datatable(
            $query,
            function ($query) {
                $search = request('search.value') ?? '';
                if (!empty($search)) {
                    $query->where(function ($q) use ($search) {
                        $q->orWhere('pincodes.pincode', 'LIKE', "%{$search}%")
                            ->orWhere('pincodes.area', 'LIKE', "%{$search}%")
                            ->orWhere('pincodes.minimum_cart_amount', 'LIKE', "%{$search}%")
                            ->orWhere('pincodes.delivery_charge', 'LIKE', "%{$search}%");
                    });
                }
            },
            function ($rows, $totalFiltered, $totalData) {
                $data = [];
                $start = request('start') ?? 0;
                $order = request('order.0.dir') ?? 'desc';
                $count = $totalFiltered - $start;
                $start = $start + 1;
                foreach ($rows as $row) {
                    $data[] = [
                        'id' => $order == 'desc' ? $start++ : $count--,
                        'pincode' => $row->pincode,
                        'area' => $row->area ?? '—',
                        'minimum_cart_amount' => $row->minimum_cart_amount,
                        'delivery_charge' => $row->delivery_charge,
                        'actions' => view('backend/pincode/actions', compact('row'))->render(),
                    ];
                }
                return $data;
            }
        );
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pincode' => ['required', 'string', 'max:20', 'unique:pincodes,pincode'],
            'area' => ['nullable', 'string', 'max:100'],
            'minimum_cart_amount' => ['required', 'numeric', 'min:0'],
            'delivery_charge' => ['required', 'numeric', 'min:0'],
            'delivery_cart_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        if (!$validator->passes()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();
        try {
            Pincode::create($request->only(['pincode', 'area', 'minimum_cart_amount', 'delivery_charge', 'delivery_cart_amount']));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'alert' => ['icon' => 'error', 'title' => 'Pincode', 'text' => 'Something went wrong.'],
            ]);
        }
        DB::commit();
        return response()->json([
            'reset' => true,
            'modal' => ['hide' => '#create-form'],
            'alert' => ['icon' => 'success', 'title' => 'Pincode', 'text' => 'Created successfully.'],
            'datatable' => ['reload' => true],
        ]);
    }

    public function edit(Pincode $pincode)
    {
        return response()->json([
            'jquery' => [
                [
                    'element' => '#edit-form .modal-content',
                    'method' => 'html',
                    'value' => view('backend/pincode/edit', compact('pincode'))->render(),
                ],
            ],
            'init' => ['#edit-form .modal-content'],
            'modal' => ['show' => '#edit-form'],
        ]);
    }

    public function update(Request $request, Pincode $pincode)
    {
        $validator = Validator::make($request->all(), [
            'pincode' => ['required', 'string', 'max:20', 'unique:pincodes,pincode,' . $pincode->id],
            'area' => ['nullable', 'string', 'max:100'],
            'minimum_cart_amount' => ['required', 'numeric', 'min:0'],
            'delivery_charge' => ['required', 'numeric', 'min:0'],
            'delivery_cart_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        if (!$validator->passes()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();
        try {
            $pincode->update($request->only(['pincode', 'area', 'minimum_cart_amount', 'delivery_charge', 'delivery_cart_amount']));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'alert' => ['icon' => 'error', 'title' => 'Pincode', 'text' => 'Something went wrong.'],
            ]);
        }
        DB::commit();
        return response()->json([
            'reset' => true,
            'modal' => ['hide' => '#edit-form'],
            'alert' => ['icon' => 'success', 'title' => 'Pincode', 'text' => 'Updated successfully.'],
            'datatable' => ['reload' => true],
        ]);
    }

    public function destroy(Pincode $pincode)
    {
        try {
            $pincode->delete();
        } catch (\Exception $e) {
            return response()->json([
                'alert' => ['icon' => 'error', 'title' => 'Pincode', 'text' => 'Pincode can\'t be deleted as it is in use.'],
            ]);
        }
        return response()->json([
            'datatable' => ['reload' => true],
            'alert' => ['icon' => 'success', 'title' => 'Pincode', 'text' => 'Deleted successfully.'],
        ]);
    }
}
