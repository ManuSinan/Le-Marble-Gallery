<?php

namespace App\Http\Controllers;

use App\Models\Poster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PosterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        return view('backend/poster/index', compact('search'));
    }

    public function list(Poster $poster)
    {
        $query = $poster->select(
            'posters.id',
            'posters.name',
            'posters.image',
            'posters.priority'
        );
        $data = $this->datatable(
            $query,
            function ($query) {
                $search = request('search.value') ?? '';
                if (!empty($search)) {
                    $query->orWhere('posters.name', 'LIKE', "%{$search}%");
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
                        'name' => view('backend/poster/list-image', compact('row'))->render(),
                        'actions' => view('backend/poster/actions', compact('row'))->render(),
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
            'name' => ['required', 'max:100'],
            'image' => ['required', 'file', 'image', 'max:10240'],
            'priority' => ['required', 'integer'],
        ]);

        if (!$validator->passes()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();
        try {
            $input = $request->only(['name', 'priority']);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $directory = Str::slug($request->name, '-');
                $fileName = fileName($file->extension());

                $baseDir = 'poster/' . $directory;
                Storage::disk('public')->makeDirectory($baseDir . '/original');
                Storage::disk('public')->putFileAs($baseDir . '/original', $file, $fileName);

                $large = $this->imageResizeAndSave($file, $baseDir . '/large/' . $fileName, 560, 224);
                $base = $this->imageResizeAndSave($file, $baseDir . '/base/' . $fileName, 280, 112);

                if ($large && $base) {
                    $input['image'] = $baseDir . '/base/' . $fileName;
                } else {
                    return response()->json([
                        'errors' => ['image' => ['Unable to process image.']],
                    ]);
                }
            }

            Poster::create($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Promotional Banners',
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
                'title' => 'Promotional Banners',
                'text' => 'Created successfully.',
            ],
            'datatable' => ['reload' => true],
        ]);
    }

    public function edit(Poster $poster)
    {
        return response()->json([
            'jquery' => [
                [
                    'element' => '#edit-form .modal-content',
                    'method' => 'html',
                    'value' => view('backend/poster/edit', compact('poster'))->render(),
                ],
            ],
            'init' => ['#edit-form .modal-content'],
            'modal' => ['show' => '#edit-form'],
        ]);
    }

    public function update(Request $request, Poster $poster)
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required', 'max:100'],
            'image' => ['nullable', 'file', 'image', 'max:10240'],
            'priority' => ['required', 'integer'],
        ]);

        if (!$validator->passes()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();
        try {
            $input = $request->only(['name', 'priority']);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $directory = Str::slug($request->name, '-');
                $fileName = fileName($file->extension());

                $baseDir = 'poster/' . $directory;
                Storage::disk('public')->makeDirectory($baseDir . '/original');
                Storage::disk('public')->putFileAs($baseDir . '/original', $file, $fileName);

                $large = $this->imageResizeAndSave($file, $baseDir . '/large/' . $fileName, 560, 224);
                $base = $this->imageResizeAndSave($file, $baseDir . '/base/' . $fileName, 280, 112);

                if ($large && $base) {
                    $input['image'] = $baseDir . '/base/' . $fileName;
                } else {
                    return response()->json([
                        'errors' => ['image' => ['Unable to process image.']],
                    ]);
                }
            }

            $poster->update($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Promotional Banners',
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
                'title' => 'Promotional Banners',
                'text' => 'Updated successfully.',
            ],
            'datatable' => ['reload' => true],
        ]);
    }

    public function destroy(Poster $poster)
    {
        try {
            $poster->delete();
        } catch (\Exception $e) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Promotional Banners',
                    'text' => 'Banner can\'t be deleted.',
                ],
            ]);
        }
        return response()->json([
            'datatable' => ['reload' => true],
            'alert' => [
                'icon' => 'success',
                'title' => 'Promotional Banners',
                'text' => 'Deleted successfully.',
            ],
        ]);
    }
}
