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
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        

        foreach(range('B','N') as $column) {
            $spreadsheet->getActiveSheet()->getColumnDimension($column)->setWidth(15);
        }

        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle('A1:N1')->applyFromArray([
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
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', 'Brand');
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
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N1', 'Status');
 
        // Pre-populate dummy example data row 2
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', 'Carrara White Marble Slab');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', 'Carrara Quarries');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C2', 'Bath Accessories');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D2', 'Area::Sq.Ft');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E2', 'CM-WHITE-001');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F2', 'Unlimited');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G2', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H2', 10);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I2', 150);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J2', 135);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K2', 'Premium Carrara white marble slab with elegant grey veining.');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L2', 'marble, slab, white, carrara');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M2', 'High');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N2', 'Published');

        // Pre-populate dummy example data row 3
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', 'Kohler Faucet Model X');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B3', 'Kohler');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C3', 'Taps');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D3', 'Items::Items');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E3', 'KF-MODX-99');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F3', 'Limited');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G3', 50);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H3', 1);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I3', 85.5);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J3', 79.99);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K3', 'Modern chrome finish Kohler faucet.');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L3', 'faucet, kohler, tap, bathroom');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M3', 'Medium');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N3', 'Draft');

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

            $validation = $spreadsheet->getActiveSheet()->getCell('N' .$range)->getDataValidation();
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
            $validation->setFormula1('"Draft,Published,Expired"');
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

            $brandsList = Brand::pluck('name')->toArray();
            $categoriesList = Category::pluck('name')->toArray();
            $unitsList = Unit::select(DB::raw('CONCAT(type, "::", name) as unit'))->pluck('unit')->toArray();
            $priorityList = priority();
            $statusList = ['Draft', 'Published', 'Expired'];

            // Clean & Normalize input rows
            foreach ($data as $key => $row) {
                // Trim columns
                foreach ($row as $colKey => $val) {
                    $data[$key][$colKey] = trim((string) $val);
                }

                // Normalize Brand
                if (!empty($data[$key]['B'])) {
                    foreach ($brandsList as $b) {
                        if (strcasecmp($data[$key]['B'], $b) === 0) {
                            $data[$key]['B'] = $b;
                            break;
                        }
                    }
                }

                // Normalize Category
                if (!empty($data[$key]['C'])) {
                    foreach ($categoriesList as $c) {
                        if (strcasecmp($data[$key]['C'], $c) === 0) {
                            $data[$key]['C'] = $c;
                            break;
                        }
                    }
                }

                // Normalize Unit
                if (!empty($data[$key]['D'])) {
                    foreach ($unitsList as $u) {
                        if (strcasecmp($data[$key]['D'], $u) === 0) {
                            $data[$key]['D'] = $u;
                            break;
                        }
                    }
                }

                // Normalize Stock Status
                if (!empty($data[$key]['F'])) {
                    if (strcasecmp($data[$key]['F'], 'Unlimited') === 0) {
                        $data[$key]['F'] = 'Unlimited';
                    } elseif (strcasecmp($data[$key]['F'], 'Limited') === 0) {
                        $data[$key]['F'] = 'Limited';
                    }
                }

                // Normalize Priority
                if (!empty($data[$key]['M'])) {
                    foreach ($priorityList as $p) {
                        if (strcasecmp($data[$key]['M'], $p) === 0) {
                            $data[$key]['M'] = $p;
                            break;
                        }
                    }
                }

                // Normalize Status
                if (!empty($data[$key]['N'])) {
                    foreach ($statusList as $s) {
                        if (strcasecmp($data[$key]['N'], $s) === 0) {
                            $data[$key]['N'] = $s;
                            break;
                        }
                    }
                } else {
                    $data[$key]['N'] = 'Draft';
                }
            }
    
            $validator = Validator::make($data, [
                '*.A' => [ 'required', 'max:100' ],
                '*.B' => [ 'nullable', 'in:' . implode(',', $brandsList)],
                '*.C' => [ 'required', 'in:' . implode(',', $categoriesList)],
                '*.D' => [ 'required', 'in:' . implode(',', $unitsList)],
                '*.E' => [ 'nullable', 'unique:products,product_code', 'max:255' ],
                '*.F' => [ 'required', 'in:Limited,Unlimited', 'max:10' ],
                '*.G' => [ 'nullable', 'required_if:*.F,==,Limited', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99' ],
                '*.H' => [ 'required', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99' ],
                '*.I' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' ],
                '*.J' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' , 'lte:*.I'],
                '*.K' => [ 'nullable' ],
                '*.L' => [ 'nullable' ],
                '*.M' => [ 'required', 'in:' . implode(',', $priorityList), 'max:10' ],
                '*.N' => [ 'required', 'in:' . implode(',', $statusList), 'max:10' ]
            ]);

            $validator->setAttributeNames([
                '*.A' => 'Name',
                '*.B' => 'Brand',
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
                '*.M' => 'Priority',
                '*.N' => 'Status'
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
                $priority = array_search($row['M'], $priorityList);
 
                if($category && $unit){
                    $product = Product::create([
                        'name' => $row['A'], 
                        'slug' => Str::slug($row['A'], '-') . '-' . $insertKey, 
                        
                        'brand_id' => $brand->id ?? null, 
                        'category_id' => $category->id, 
                        'unit_id' => $unit->id, 
                        
                        'product_code' => empty($row['E']) ? null : $row['E'], 
                        'stock_status' =>  strtolower( $row['F'] ), 
                        'stock_available' => strtolower($row['F']) == 'unlimited' ? null : $row['G'], 
                        'minimum_quantity' => $row['H'], 
                        'price' => $row['I'], 
                        'selling_price' => $row['J'], 
                        'description' => $row['K'], 
                        'keywords' => $row['L'], 
                        'priority' => $priority, 
                        'status' => strtolower($row['N']), 
                    ]);

                    // Generate magic search keyword combinations
                    $keyword = $product->name;
                    if($product->category && $product->category->name){
                        $keyword .= ' '.  Str::singular($product->category->name);
                    }
                    if($product->brand && $product->brand->name){
                        $keyword .= ' '. $product->brand->name;
                    }
                    $combinations = new \Combinations( array_unique ( explode(' ', $keyword) ) );
                    $product->update([
                        'magic_search' => $combinations->generate()
                    ]);
                }
            }

            \Illuminate\Support\Facades\Cache::forget('prodata');

        } catch (\Exception $e) {
            DB::rollback();
            report($e);
 
            return response()->json([
                'reset' => true,
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Product Bulk Update',
                    'text' => config('app.debug') ? ('Something went wrong: ' . $e->getMessage()) : 'Something went wrong.',
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
