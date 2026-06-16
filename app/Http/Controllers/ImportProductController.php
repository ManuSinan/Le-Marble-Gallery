<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill as SpreadsheetFill;
 
class ImportProductController extends Controller
{
    public function index(Request $request)
    {
        return view('backend/import/product/index');
    }

    public function insertTemplate(Request $request)
    {

        $spreadsheet = new Spreadsheet();
  
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle('Products');
 
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);

        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        

        foreach(range('B','M') as $column) {
            $spreadsheet->getActiveSheet()->getColumnDimension($column)->setWidth(15);
        }

        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => false,
                'color' => [
                    'rgb' => 'ffffff',
                ],
            ],
            'fill' => [
                'fillType' => SpreadsheetFill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '387a47',
                ],

            ],
        ]);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Name');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', 'Publisher');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', 'Category');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', 'Unit');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', 'Product Code');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', 'Stock Status');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', 'Stock Available');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', 'Minimum Quantity');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', 'Price');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J1', 'Selling Price');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K1', 'Description');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L1', 'Keywords');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M1', 'Priority');
 

        $brands = Brand::pluck('name')->join(', ');
        $categories = Category::pluck('name')->join(', ');
        $units = Unit::select(DB::raw('CONCAT(type, "::", name) as unit'))->pluck('unit')->join(', ');

        for($range=2; $range<=1001; $range++) {

            $validation = $spreadsheet->getActiveSheet()->getCell('B' .$range)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('"' . $brands . '"');

            $validation = $spreadsheet->getActiveSheet()->getCell('C' .$range)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('"' . $categories . '"');
     
            $validation = $spreadsheet->getActiveSheet()->getCell('D' .$range)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('"' . $units . '"');

            $validation = $spreadsheet->getActiveSheet()->getCell('F' .$range)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('"Unlimited,Limited"');
     
            $validation = $spreadsheet->getActiveSheet()->getCell('M' .$range)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('"Low,Medium,High,Featured"');
        }

        return $this->downloadExcel( $spreadsheet );
    } 

    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'file' => [ 'required', 'mimes:xls,xlsx' ],
        ]);

        $validator->setAttributeNames([
            'file' => 'Excel',
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        DB::beginTransaction();
        try {
        
            $spreadsheet = $this->getExcel($request->file('file'));

            $spreadsheet->setActiveSheetIndex(0);
    
            $worksheet = $spreadsheet->getActiveSheet();
    
            $data = $worksheet->toArray(null,true,true,true); 

            unset($data[1]);

            $brands =  Brand::pluck('name')->join(',');
            $categories = Category::pluck('name')->join(',');
            $units = Unit::select(DB::raw('CONCAT(type, "::", name) as unit'))->pluck('unit')->join(',');
    
            $validator = Validator::make($data, [
                '*.A' => [ 'required', 'max:100' ],
                '*.B' => [ 'nullable', 'in:' . $brands],
                '*.C' => [ 'required', 'in:' . $categories],
                '*.D' => [ 'required', 'in:' . $units],
                '*.E' => [ 'nullable', 'unique:products,product_code', 'max:255' ],
                '*.F' => [ 'required', 'in:Limited,Unlimited', 'max:10' ],
                '*.G' => [ 'nullable', 'required_if:*.F,==,Limited', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99' ],
                '*.H' => [ 'required', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99' ],
                '*.I' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' ],
                '*.J' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' , 'lte:*.I'],
                '*.K' => [ 'nullable' ],
                '*.L' => [ 'nullable' ],
                '*.M' => [ 'required', 'in:' . implode(',', priority()), 'max:10' ]
            ]);

            $validator->setAttributeNames([
                '*.A' => 'Name',
                '*.B' => 'Publisher',
                '*.C' => 'Category',
                '*.D' => 'Unit',
                '*.E' => 'Product Code',
                '*.F' => 'Stock Status',
                '*.G' => 'Stock Available',
                '*.H' => 'Minimum Quantity',
                '*.I' => 'Price',
                '*.J' => 'Selling Price',
                '*.K' => 'Description',
                '*.L' => 'Keywords',
                '*.M' => 'Priority'
            ]);

            if (!$validator->passes()) {
                
                $errors = $validator->errors()->toArray();

                return response()->json([
                    'reset' => true,
                    'jquery' => [
                        [
                            'element' => '#validator',
                            'method' => 'html',
                            'value' => view('backend/import/product/validator', compact('errors'))->render()
                        ],
                    ],
                ]);
            }
 
            $insertKey = rand(99, 999);
            foreach($data as $key => $row){  
 
                $brand = Brand::where('name', $row['B'])->first();

                $category = Category::where('name', $row['C'])->first();
                
                $units = explode('::',$row['D']);

                $unitType = $units[0] ?? '';
                $unitName = $units[1] ?? '';

                $unit = Unit::where('type', $unitType)->where('name', $unitName)->first();
                $priority = array_search($row['M'], priority());
 
                if($category && $unit){
                    Product::create([
                        'name' => $row['A'], 
                        'slug' => Str::slug($row['A'], '-') . '-' . $insertKey, 
                        
                        'brand_id' => $brand->id ?? null, 
                        'category_id' => $category->id, 
                        'unit_id' => $unit->id, 
                        
                        'product_code' => $row['E'], 
                        'stock_status' =>  strtolower( $row['F'] ), 
                        'stock_available' => $row['G'], 
                        'minimum_quantity' => $row['H'], 
                        'price' => $row['I'], 
                        'selling_price' => $row['J'], 
                        'description' => $row['K'], 
                        'keywords' => $row['L'], 
                        'priority' => $priority, 
                        'status' => 'draft', 
                    ]);
                }
            }

        } catch (\Exception $e) {
            DB::rollback();
 
            return response()->json([
                'reset' => true,
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Product Bulk Update',
                    'text' => 'Something went wrong.',
                ],
                'jquery' => [
                    [
                        'element' => '#validator',
                        'method' => 'html',
                        'value' => ' '
                    ],
                ],
            ]);
        }
        DB::commit();

        return response()->json([
            'reset' => true,
            'jquery' => [
                [
                    'element' => '#validator',
                    'method' => 'html',
                    'value' => ' '
                ],
            ],
            'alert' => [
                'icon' => 'success',
                'title' => 'Product Bulk Update',
                'text' => 'Updated successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    } 

    public function editTemplate(Request $request)
    {

        $spreadsheet = new Spreadsheet();
  
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle('Products');

        $products = Product::select('products.id', 'categories.name as category', 'products.name', 'products.product_code', 'products.stock_status', 'products.stock_available', 'products.minimum_quantity', 'products.price', 'products.selling_price')
        ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
        ->whereIn('products.status', ['published', 'active'])
        ->limit(3500)
        ->get();
     
        foreach(range('A','I') as $column) {
            $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        }

        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => false,
                'color' => [
                    'rgb' => 'ffffff',
                ],
            ],
            'fill' => [
                'fillType' => SpreadsheetFill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '387a47',
                ],

            ],
        ]);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Id');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', 'Category');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', 'Name');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', 'Code');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', 'Stock Status');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', 'Stock Available');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', 'Minimum Quantity');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', 'Price');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', 'Selling Price');
 

        if($products){

            $index = 2;
            foreach($products as $product){

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $index, $product->id);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $index, $product->category);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $index, $product->name);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $index, $product->product_code);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $index, $product->stock_status);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $index, $product->stock_available);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $index, $product->minimum_quantity);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $index, $product->price);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $index, $product->selling_price);

                $index++;
            }
        }
 
        return $this->downloadExcel( $spreadsheet );
    } 

    public function edit(Request $request)
    {
        return view('backend/import/product/edit');
    }

    public function update(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'file' => [ 'required', 'mimes:xls,xlsx' ],
        ]);

        $validator->setAttributeNames([
            'file' => 'Excel',
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        DB::beginTransaction();
        try {
        
            $spreadsheet = $this->getExcel($request->file('file'));

            $spreadsheet->setActiveSheetIndex(0);
    
            $worksheet = $spreadsheet->getActiveSheet();
    
            $data = $worksheet->toArray(null,true,true,true); 

            unset($data[1]);

            $validator = Validator::make($data, [
                '*.A' => [ 'required', 'integer' ],
                '*.E' => [ 'required', 'in:limited,unlimited', 'max:10' ],
                '*.F' => [ 'nullable', 'required_if:*.E,==,limited', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99' ],
                '*.G' => [ 'required', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99' ],
                '*.H' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' ],
                '*.I' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' , 'lte:*.H'],
            ]);

            $validator->setAttributeNames([
                '*.E' => 'Stock Status',
                '*.F' => 'Stock Available',
                '*.G' => 'Minimum Quantity',
                '*.H' => 'Price',
                '*.I' => 'Selling Price',
            ]);

            if (!$validator->passes()) {
                
                $errors = $validator->errors()->toArray();

                return response()->json([
                    'reset' => true,
                    'jquery' => [
                        [
                            'element' => '#validator',
                            'method' => 'html',
                            'value' => view('backend/import/product/validator', compact('errors'))->render()
                        ],
                    ],
                ]);
            }
 
            foreach($data as $key => $row){
                $product = Product::where('id', $row['A'])->where('name', $row['C'])->where('product_code', $row['D'])->first();

                if($product){
                    $product->update([
                        'stock_status' => $row['E'],
                        'stock_available' => $row['F'],
                        'minimum_quantity' => $row['G'],
                        'price' => $row['H'],
                        'selling_price' => $row['I']
                    ]);

                    notify($product->id);
                }
            }

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'reset' => true,
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Product Bulk Update',
                    'text' => 'Something went wrong.',
                ],
                'jquery' => [
                    [
                        'element' => '#validator',
                        'method' => 'html',
                        'value' => ' '
                    ],
                ],
            ]);
        }
        DB::commit();

        return response()->json([
            'reset' => true,
            'jquery' => [
                [
                    'element' => '#validator',
                    'method' => 'html',
                    'value' => ' '
                ],
            ],
            'alert' => [
                'icon' => 'success',
                'title' => 'Product Bulk Update',
                'text' => 'Updated successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    } 
}
