<?php

namespace App\Http\Controllers;

use App\Models\DailyOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class DailyOfferController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        return view('backend/daily-offer/index', compact('search'));
    }

    public function list(DailyOffer $dailyOffer)
    {
        $query = $dailyOffer->select(
            'daily_offers.id',
            'daily_offers.title',
            'daily_offers.image',
            'daily_offers.link',
            'daily_offers.status',
            'daily_offers.created_at'
        );

        $data = $this->datatable(
            $query,
            function ($query) {
                $search = request('search.value') ?? '';
                if (!empty($search)) {
                    $query->where(function ($q) use ($search) {
                        $q->orWhere('daily_offers.title', 'LIKE', "%{$search}%")
                          ->orWhere('daily_offers.link', 'LIKE', "%{$search}%");
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
                        'title' => view('backend/daily-offer/list-title', compact('row'))->render(),
                        'status' => view('backend/daily-offer/list-status', compact('row'))->render(),
                        'actions' => view('backend/daily-offer/actions', compact('row'))->render(),
                    ];
                }
                return $data;
            }
        );

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'title' => ['nullable', 'max:150'],
            'image' => ['required', 'file', 'image', 'max:10240'],
            'link'  => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'boolean'],
        ]);

        if (!$validator->passes()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();
        try {
            $input = $request->only(['title', 'link', 'status']);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $directory = Str::slug($request->title ?? 'daily-offer', '-');
                $fileName = fileName($file->extension());

                $baseDir = 'daily-offers/' . $directory;
                Storage::disk('public')->makeDirectory($baseDir . '/original');
                Storage::disk('public')->putFileAs($baseDir . '/original', $file, $fileName);

                $large = $this->imageResizeAndSave($file, $baseDir . '/large/' . $fileName, 1400, 0);
                $base = $this->imageResizeAndSave($file, $baseDir . '/base/' . $fileName, 700, 0);

                if ($large && $base) {
                    $input['image'] = $baseDir . '/large/' . $fileName;
                } else {
                    return response()->json([
                        'errors' => ['image' => ['Unable to process image.']],
                    ]);
                }
            }

            DailyOffer::create($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Daily Offer',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();

        return response()->json([
            'reset' => true,
            'modal' => ['hide' => '#create-form'],
            'alert' => [
                'icon' => 'success',
                'title' => 'Daily Offer',
                'text' => 'Created successfully.',
            ],
            'datatable' => ['reload' => true],
        ]);
    }

    public function edit(DailyOffer $dailyOffer)
    {
        return response()->json([
            'jquery' => [
                [
                    'element' => '#edit-form .modal-content',
                    'method' => 'html',
                    'value' => view('backend/daily-offer/edit', compact('dailyOffer'))->render(),
                ],
            ],
            'init' => ['#edit-form .modal-content'],
            'modal' => ['show' => '#edit-form'],
        ]);
    }

    public function update(Request $request, DailyOffer $dailyOffer)
    {
        $validator = Validator::make(request()->all(), [
            'title' => ['nullable', 'max:150'],
            'image' => ['nullable', 'file', 'image', 'max:10240'],
            'link'  => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'boolean'],
        ]);

        if (!$validator->passes()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();
        try {
            $input = $request->only(['title', 'link', 'status']);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $directory = Str::slug($request->title ?? 'daily-offer', '-');
                $fileName = fileName($file->extension());

                $baseDir = 'daily-offers/' . $directory;
                Storage::disk('public')->makeDirectory($baseDir . '/original');
                Storage::disk('public')->putFileAs($baseDir . '/original', $file, $fileName);

                $large = $this->imageResizeAndSave($file, $baseDir . '/large/' . $fileName, 1400, 0);
                $base = $this->imageResizeAndSave($file, $baseDir . '/base/' . $fileName, 700, 0);

                if ($large && $base) {
                    $input['image'] = $baseDir . '/large/' . $fileName;
                } else {
                    return response()->json([
                        'errors' => ['image' => ['Unable to process image.']],
                    ]);
                }
            }

            $dailyOffer->update($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Daily Offer',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();

        return response()->json([
            'reset' => true,
            'modal' => ['hide' => '#edit-form'],
            'alert' => [
                'icon' => 'success',
                'title' => 'Daily Offer',
                'text' => 'Updated successfully.',
            ],
            'datatable' => ['reload' => true],
        ]);
    }

    public function destroy(DailyOffer $dailyOffer)
    {
        try {
            $dailyOffer->delete();
        } catch (\Exception $e) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Daily Offer',
                    'text' => 'Daily Offer can\'t be deleted.',
                ],
            ]);
        }

        return response()->json([
            'datatable' => ['reload' => true],
            'alert' => [
                'icon' => 'success',
                'title' => 'Daily Offer',
                'text' => 'Deleted successfully.',
            ],
        ]);
    }
}

