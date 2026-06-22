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
 
        foreach(range('A','R') as $column) {
            $spreadsheet->getActiveSheet()->getColumnDimension($column)->setWidth(18);
        }

        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle('A1:R1')->applyFromArray([
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

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Model Name');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', 'Category');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', 'Sub-Category');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D1', 'Brand');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', 'Unit');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F1', 'Product Code');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G1', 'Stock Status');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H1', 'Stock Available');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I1', 'Minimum Quantity');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J1', 'MRP (₹)');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K1', 'Finish/Colour');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L1', 'Product Type');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M1', 'Installation Type');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N1', 'Compatibility/Notes');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('O1', 'Description');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('P1', 'Keywords');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q1', 'Priority');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('R1', 'Status');
 
        // Pre-populate dummy example data row 2
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', 'ModernLife Edge');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', 'Toilets');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C2', 'Wall Hung Toilets');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D2', 'Kohler');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E2', 'Items::Items');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F2', 'K-27791IN-WA');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G2', 'Unlimited');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H2', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I2', 1);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J2', 90000);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K2', 'French Gold');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L2', 'Complete Set');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M2', 'Wall Hung');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N2', 'Must order: Plastic outlet connector (K-1527926)');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('O2', 'True rimless design toilets complete set in French Gold.');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('P2', 'toilet, kohler, french gold');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q2', 'High');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('R2', 'Published');

        // Pre-populate dummy example data row 3
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', 'ModernLife Edge');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B3', 'Toilets');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C3', 'Wall Hung Toilets');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D3', 'Kohler');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E3', 'Items::Items');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F3', 'K-27791IN-HB');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G3', 'Unlimited');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H3', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I3', 1);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J3', 67000);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K3', 'Honed Black');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L3', 'Complete Set');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M3', 'Wall Hung');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N3', 'Must order: Plastic outlet connector (K-1527926)');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('O3', 'True rimless design toilets complete set in Honed Black.');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('P3', 'toilet, kohler, honed black');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q3', 'High');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('R3', 'Published');

        $brands = Brand::pluck('name')->join(',');
        $categories = Category::pluck('name')->join(',');
        $units = Unit::select(DB::raw('CONCAT(type, "::", name) as unit'))->pluck('unit')->join(',');

        for($range=2; $range<=1001; $range++) {

            $validation = $spreadsheet->getActiveSheet()->getCell('D' .$range)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('"' . $brands . '"');

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
            $validation->setFormula1('"' . $categories . '"');
     
            $validation = $spreadsheet->getActiveSheet()->getCell('E' .$range)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('"' . $units . '"');

            $validation = $spreadsheet->getActiveSheet()->getCell('G' .$range)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('"Unlimited,Limited"');
     
            $validation = $spreadsheet->getActiveSheet()->getCell('Q' .$range)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('"Low,Medium,High,Featured"');

            $validation = $spreadsheet->getActiveSheet()->getCell('R' .$range)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(true);
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

            // Read header row (row 1) to build a dynamic mapping of columns
            $headerRow = $worksheet->getRowIterator(1)->current();
            $cellIterator = $headerRow->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            $headerMap = [];
            foreach ($cellIterator as $cell) {
                $val = trim((string)$cell->getValue());
                if ($val !== '') {
                    $headerMap[$cell->getColumn()] = strtolower($val);
                }
            }

            unset($data[1]); // Remove header row from data

            // Define mapping search terms for dynamic header identification
            $colMap = [
                'model_name' => ['model name', 'name', 'model_name'],
                'category' => ['category'],
                'sub_category' => ['sub-category', 'sub category', 'sub_category'],
                'brand' => ['brand'],
                'unit' => ['unit'],
                'product_code' => ['product code', 'product_code', 'code'],
                'stock_status' => ['stock status', 'stock_status'],
                'stock_available' => ['stock available', 'stock_available'],
                'minimum_quantity' => ['minimum quantity', 'minimum_quantity'],
                'price' => ['mrp', 'mrp (₹)', 'mrp(₹)', 'price'],
                'selling_price' => ['selling price', 'selling_price'],
                'finish_colour' => ['finish/colour', 'finish', 'colour', 'color', 'finish_colour'],
                'product_type' => ['product type', 'product_type'],
                'installation_type' => ['installation type', 'installation_type'],
                'compatibility_notes' => ['compatibility/notes', 'compatibility', 'notes', 'compatibility_notes'],
                'description' => ['description'],
                'keywords' => ['keywords'],
                'priority' => ['priority'],
                'status' => ['status']
            ];

            // Map each field to its column letter
            $mappedCols = [];
            foreach ($colMap as $field => $searches) {
                $matchedCol = null;
                foreach ($headerMap as $colLetter => $headerName) {
                    if (in_array($headerName, $searches)) {
                        $matchedCol = $colLetter;
                        break;
                    }
                }
                $mappedCols[$field] = $matchedCol;
            }

            // Check for critical missing columns
            if (empty($mappedCols['model_name'])) {
                return response()->json([
                    'reset' => true,
                    'jquery' => [
                        [
                            'element' => '#validator',
                            'method' => 'html',
                            'value' => '<div class="alert alert-danger">Missing required column: "Model Name" or "Name"</div>'
                        ],
                    ],
                ]);
            }
            if (empty($mappedCols['category'])) {
                return response()->json([
                    'reset' => true,
                    'jquery' => [
                        [
                            'element' => '#validator',
                            'method' => 'html',
                            'value' => '<div class="alert alert-danger">Missing required column: "Category"</div>'
                        ],
                    ],
                ]);
            }
            if (empty($mappedCols['price'])) {
                return response()->json([
                    'reset' => true,
                    'jquery' => [
                        [
                            'element' => '#validator',
                            'method' => 'html',
                            'value' => '<div class="alert alert-danger">Missing required price/MRP column: "MRP (₹)" or "Price"</div>'
                        ],
                    ],
                ]);
            }

            $brandsList = Brand::pluck('name')->toArray();
            $categoriesList = Category::pluck('name')->toArray();
            $unitsList = Unit::select(DB::raw('CONCAT(type, "::", name) as unit'))->pluck('unit')->toArray();
            $priorityList = priority();
            $statusList = ['Draft', 'Published', 'Expired'];

            // Clean & Normalize input rows in original data array to preserve column-based keys for validator
            foreach ($data as $key => $row) {
                // Trim columns
                foreach ($row as $colKey => $val) {
                    $data[$key][$colKey] = trim((string) $val);
                }

                // Normalize Brand
                $brandCol = $mappedCols['brand'];
                if ($brandCol && !empty($data[$key][$brandCol])) {
                    foreach ($brandsList as $b) {
                        if (strcasecmp($data[$key][$brandCol], $b) === 0) {
                            $data[$key][$brandCol] = $b;
                            break;
                        }
                    }
                }

                // Normalize Category
                $categoryCol = $mappedCols['category'];
                if ($categoryCol && !empty($data[$key][$categoryCol])) {
                    foreach ($categoriesList as $c) {
                        if (strcasecmp($data[$key][$categoryCol], $c) === 0) {
                            $data[$key][$categoryCol] = $c;
                            break;
                        }
                    }
                }

                // Normalize Unit
                $unitCol = $mappedCols['unit'];
                if ($unitCol && !empty($data[$key][$unitCol])) {
                    foreach ($unitsList as $u) {
                        if (strcasecmp($data[$key][$unitCol], $u) === 0) {
                            $data[$key][$unitCol] = $u;
                            break;
                        }
                    }
                }

                // Normalize Stock Status
                $stockStatusCol = $mappedCols['stock_status'];
                if ($stockStatusCol && !empty($data[$key][$stockStatusCol])) {
                    $rawStock = strtolower($data[$key][$stockStatusCol]);
                    if (str_contains($rawStock, 'limited') || str_contains($rawStock, 'ltd') || $rawStock === 'limit') {
                        $data[$key][$stockStatusCol] = 'Limited';
                    } else {
                        $data[$key][$stockStatusCol] = 'Unlimited';
                    }
                } else if ($stockStatusCol) {
                    $data[$key][$stockStatusCol] = 'Unlimited';
                }

                // Normalize Priority
                $priorityCol = $mappedCols['priority'];
                if ($priorityCol && !empty($data[$key][$priorityCol])) {
                    foreach ($priorityList as $p) {
                        if (strcasecmp($data[$key][$priorityCol], $p) === 0) {
                            $data[$key][$priorityCol] = $p;
                            break;
                        }
                    }
                }

                // Normalize Status
                $statusCol = $mappedCols['status'];
                if ($statusCol && !empty($data[$key][$statusCol])) {
                    foreach ($statusList as $s) {
                        if (strcasecmp($data[$key][$statusCol], $s) === 0) {
                            $data[$key][$statusCol] = $s;
                            break;
                        }
                    }
                } else if ($statusCol) {
                    $data[$key][$statusCol] = 'Draft';
                }
            }

            // Build dynamic validation rules based on mapped column letters
            $rules = [];
            $attributes = [];

            $addRule = function($field, $colLetter, $fieldRules, $fieldLabel) use (&$rules, &$attributes) {
                if ($colLetter) {
                    $rules['*.' . $colLetter] = $fieldRules;
                    $attributes['*.' . $colLetter] = $fieldLabel;
                }
            };

            $addRule('model_name', $mappedCols['model_name'], [ 'required', 'max:100' ], 'Model Name');
            $addRule('category', $mappedCols['category'], [ 'required' ], 'Category');
            $addRule('sub_category', $mappedCols['sub_category'], [ 'nullable' ], 'Sub-Category');
            $addRule('brand', $mappedCols['brand'], [ 'nullable', 'max:255' ], 'Brand');
            $addRule('unit', $mappedCols['unit'], [ 'nullable', 'max:255' ], 'Unit');
            $addRule('product_code', $mappedCols['product_code'], [ 'nullable', 'unique:products,product_code', 'max:255' ], 'Product Code');
            $addRule('stock_status', $mappedCols['stock_status'], [ 'nullable', 'in:Limited,Unlimited', 'max:10' ], 'Stock Status');
            
            if ($mappedCols['stock_available'] && $mappedCols['stock_status']) {
                $rules['*.' . $mappedCols['stock_available']] = [ 'nullable', 'required_if:*.' . $mappedCols['stock_status'] . ',==,Limited', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99' ];
                $attributes['*.' . $mappedCols['stock_available']] = 'Stock Available';
            } elseif ($mappedCols['stock_available']) {
                $rules['*.' . $mappedCols['stock_available']] = [ 'nullable', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99' ];
                $attributes['*.' . $mappedCols['stock_available']] = 'Stock Available';
            }

            $addRule('minimum_quantity', $mappedCols['minimum_quantity'], [ 'nullable', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99' ], 'Minimum Quantity');
            $addRule('price', $mappedCols['price'], [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' ], 'MRP (Price)');
            
            if ($mappedCols['selling_price']) {
                if ($mappedCols['price']) {
                    $rules['*.' . $mappedCols['selling_price']] = [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999', 'lte:*.' . $mappedCols['price'] ];
                } else {
                    $rules['*.' . $mappedCols['selling_price']] = [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' ];
                }
                $attributes['*.' . $mappedCols['selling_price']] = 'Selling Price';
            }

            $addRule('finish_colour', $mappedCols['finish_colour'], [ 'nullable', 'max:255' ], 'Finish/Colour');
            $addRule('product_type', $mappedCols['product_type'], [ 'nullable', 'max:255' ], 'Product Type');
            $addRule('installation_type', $mappedCols['installation_type'], [ 'nullable', 'max:255' ], 'Installation Type');
            $addRule('compatibility_notes', $mappedCols['compatibility_notes'], [ 'nullable' ], 'Compatibility/Notes');
            $addRule('description', $mappedCols['description'], [ 'nullable' ], 'Description');
            $addRule('keywords', $mappedCols['keywords'], [ 'nullable' ], 'Keywords');
            $addRule('priority', $mappedCols['priority'], [ 'nullable', 'in:' . implode(',', $priorityList), 'max:10' ], 'Priority');
            $addRule('status', $mappedCols['status'], [ 'nullable', 'in:' . implode(',', $statusList), 'max:10' ], 'Status');

            $validator = Validator::make($data, $rules);
            $validator->setAttributeNames($attributes);

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
 
                // 1. Map category hierarchy
                $categoryName = trim($row[$mappedCols['category']] ?? '');
                $subCategoryName = !empty($mappedCols['sub_category']) ? trim($row[$mappedCols['sub_category']] ?? '') : '';

                $category = Category::where('name', $categoryName)->whereNull('parent_id')->first();
                if (!$category && !empty($categoryName)) {
                    $category = Category::create([
                        'name' => $categoryName,
                        'slug' => Str::slug($categoryName, '-'),
                        'priority' => 0
                    ]);
                }

                $finalCategory = $category;
                if ($category && !empty($subCategoryName)) {
                    $subCategory = Category::where('name', $subCategoryName)->where('parent_id', $category->id)->first();
                    if (!$subCategory) {
                        $subCategory = Category::create([
                            'name' => $subCategoryName,
                            'slug' => Str::slug($subCategoryName, '-'),
                            'parent_id' => $category->id,
                            'priority' => 0
                        ]);
                    }
                    $finalCategory = $subCategory;
                }

                if (!$finalCategory) {
                    continue; // Skip if category is missing
                }

                // 2. Map Brand
                $brand = null;
                $brandName = !empty($mappedCols['brand']) ? trim($row[$mappedCols['brand']] ?? '') : '';
                if (!empty($brandName)) {
                    $brand = Brand::where('name', $brandName)->first();
                    if (!$brand) {
                        $brand = Brand::create([
                            'name' => $brandName,
                            'slug' => Str::slug($brandName, '-'),
                            'priority' => 0
                        ]);
                    }
                }

                // 3. Map Unit with default fallback
                $unit = null;
                $unitValue = !empty($mappedCols['unit']) ? trim($row[$mappedCols['unit']] ?? '') : '';
                if (!empty($unitValue)) {
                    $units = explode('::', $unitValue);
                    if (count($units) === 2) {
                        $unitType = trim($units[0]);
                        $unitName = trim($units[1]);
                        $unit = Unit::where('type', $unitType)->where('name', $unitName)->first();
                    } else {
                        $unitName = trim($unitValue);
                        $unit = Unit::where('name', $unitName)->first();
                    }
                }
                
                if (!$unit && !empty($unitValue)) {
                    $units = explode('::', $unitValue);
                    if (count($units) === 2) {
                        $unitType = trim($units[0]);
                        $unitName = trim($units[1]);
                    } else {
                        $lowerVal = strtolower($unitValue);
                        if (str_contains($lowerVal, 'sq') || str_contains($lowerVal, 'area') || str_contains($lowerVal, 'ft') || str_contains($lowerVal, 'meter')) {
                            $unitType = 'Area';
                        } else {
                            $unitType = 'Items';
                        }
                        $unitName = trim($unitValue);
                    }
                    $unit = Unit::create([
                        'type' => $unitType,
                        'local_type' => $unitType,
                        'name' => $unitName,
                        'local_name' => $unitName,
                        'stepper' => 1
                    ]);
                }

                if (!$unit) {
                    $unit = Unit::find(2) ?? Unit::first(); // Default to Items::Items (ID 2)
                }

                // 4. Map Stock Status and Available
                $stockStatus = 'unlimited';
                if (!empty($mappedCols['stock_status']) && !empty($row[$mappedCols['stock_status']])) {
                    $stockStatus = strtolower(trim($row[$mappedCols['stock_status']]));
                }
                $stockAvailable = null;
                if ($stockStatus == 'limited' && !empty($mappedCols['stock_available'])) {
                    $stockAvailable = $row[$mappedCols['stock_available']];
                }

                // 5. Map Minimum Quantity
                $minQty = 1;
                if (!empty($mappedCols['minimum_quantity']) && !empty($row[$mappedCols['minimum_quantity']])) {
                    $minQty = $row[$mappedCols['minimum_quantity']];
                }

                // 6. Map Prices
                $price = $row[$mappedCols['price']];
                $sellingPrice = !empty($mappedCols['selling_price']) && !empty($row[$mappedCols['selling_price']])
                    ? $row[$mappedCols['selling_price']]
                    : $price;

                // 7. Map Priority
                $priority = 1; // Medium
                if (!empty($mappedCols['priority']) && !empty($row[$mappedCols['priority']])) {
                    $priority = array_search($row[$mappedCols['priority']], $priorityList);
                    if ($priority === false) {
                        $priority = 1;
                    }
                }

                // 8. Map Status
                $status = 'published';
                if (!empty($mappedCols['status']) && !empty($row[$mappedCols['status']])) {
                    $status = strtolower(trim($row[$mappedCols['status']]));
                }

                // 9. Model Name, Finish, and constructed Name
                $modelName = trim($row[$mappedCols['model_name']] ?? '');
                $finishColour = !empty($mappedCols['finish_colour']) ? trim($row[$mappedCols['finish_colour']] ?? '') : '';
                
                $productName = $modelName;

                $productSlug = Str::slug($productName, '-') . '-' . $insertKey;
                
                $product = Product::create([
                    'name' => $productName, 
                    'slug' => $productSlug, 
                    'model_name' => $modelName,
                    'category_id' => $finalCategory->id, 
                    'brand_id' => $brand ? $brand->id : null, 
                    'unit_id' => $unit->id, 
                    
                    'product_code' => !empty($mappedCols['product_code']) ? (empty($row[$mappedCols['product_code']]) ? null : $row[$mappedCols['product_code']]) : null, 
                    'stock_status' => $stockStatus, 
                    'stock_available' => $stockAvailable, 
                    'minimum_quantity' => $minQty, 
                    'price' => $price, 
                    'selling_price' => $sellingPrice, 
                    
                    'finish_colour' => $finishColour,
                    'product_type' => !empty($mappedCols['product_type']) ? $row[$mappedCols['product_type']] : null,
                    'installation_type' => !empty($mappedCols['installation_type']) ? $row[$mappedCols['installation_type']] : null,
                    'compatibility_notes' => !empty($mappedCols['compatibility_notes']) ? $row[$mappedCols['compatibility_notes']] : null,
                    
                    'description' => !empty($mappedCols['description']) ? $row[$mappedCols['description']] : null, 
                    'keywords' => !empty($mappedCols['keywords']) ? $row[$mappedCols['keywords']] : null, 
                    'priority' => $priority, 
                    'status' => $status, 
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
