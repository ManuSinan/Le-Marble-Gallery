<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function datatable( $query, $condition, $fn)
    {   
        $validator = Validator::make(request()->all(), [
            'length' => ['integer'],
            'start' => ['integer'],
            'search'  => ['max:100']
        ]);
 
        if ( !$validator->passes() ) {
            return [
                "draw"            => 0,  
                "recordsTotal"    => 0,  
                "recordsFiltered" => 0, 
                "data"            => [],
            ];
        }

        try{

            $input = request(['columns', 'length', 'start', 'order', 'search', 'draw']);

            $totalData = $query->count();

            $totalFiltered = $totalData; 

            $order = $input['columns'][ $input['order'][0]['column'] ][ 'name' ] ?? 'id';

            $search = $input['search']['value'] ?? '' ;

            $where = $query->where($condition);

            $totalFiltered = $where->count();

            $rows =  $where->offset(  $input['start'] )
                                ->limit( $input['length'] )
                                ->orderBy($order, $input['order'][0]['dir'] ?? 'desc' )
                                ->get();
                                

            $data = $fn( $rows, $totalFiltered,  $totalData );

            return [
                "draw"            => intval( $input['draw'] ),  
                "recordsTotal"    => intval( $totalData ),  
                "recordsFiltered" => intval( $totalFiltered ), 
                "data"            => $data,
            ];

        } catch(\Exception $e){


            return [
                "draw"            => 0,  
                "recordsTotal"    => 0,  
                "recordsFiltered" => 0, 
                "data"            => [],
            ];
        }
    }

    public function downloadExcel( $spreadsheet, $filename = 'template.xls')
    {   
        $writer = IOFactory::createWriter($spreadsheet, 'Xls');

        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename .'"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;
    }

    public function getExcel($path)
    {   
        return IOFactory::load($path);
    }

    public function imageResizeAndSave($file, $path, $width, $height)
    {
        try {
            $ext = strtolower($file->extension());
            $source = method_exists($file, 'getRealPath') ? $file->getRealPath() : $file;

            // Preserve original aspect ratio without cropping; limit by target width
            $image = Image::make($source)->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            if (in_array($ext, ['jpg', 'jpeg'])) {
                $image->encode('jpg', 92);
            } else {
                $image->encode($ext);
            }

            return Storage::disk('public')->put($path, (string) $image);
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }

}
