<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ImportProductcontroller extends Controller
{
    public function index()
    {
        $Item = Item::all();
        return view('import-product.index')->with(compact('Item'));
    }

    public function importProducts()
    {
        return view('import-product.import');
    }

    public function processProductRecord(Request $request)
    {

        if ($request->hasFile('product_excel')) {
            $productExcel = $request->file('product_excel');
            $productExcelFileName = Storage::disk('public_uploads')->put('product_excel', $productExcel);
            $inputFileName = public_path('uploads/' . $productExcelFileName);
            $inputFileType = 'Xlsx';
            /**  Create a new Reader of the type defined in $inputFileType  **/
            $reader = IOFactory::createReader($inputFileType);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $spreadsheet = $reader->load($inputFileName);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            unset($sheetData[1]);
            print_r($sheetData);
            exit();

            foreach ($sheetData as $item) {
                $getProductGroup = $item['A'];

                if (!empty($getProductGroup)) {

                    $items = Item::updateOrCreate(
                        ['name' => $getProductGroup]
                    );
                    $items = $items->item_id;


                }
            }
        }
        $request->session()->flash('create-status', 'Products Create/Update successfully.');
        return redirect('/');

    }

    public function downloadProductsUploadFormat()
    {
        $item = Item::all()->toArray();
        $productGroupNames = implode(",", array_column($item, "name"));
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $columnCounter = 1;
        $rowCounter = 1;
        $columns = ['taxrate', 'ratedindex', 'name', 'unit', 'hsn', 'sku', 'sale_rate', 'descripation', 'type'];
        foreach ($columns as $column) {
            $sheet->setCellValueByColumnAndRow($columnCounter, $rowCounter, $column);
            $columnCounter++;
        }
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="item.xlsx"');
        $writer->save("php://output");
    }

}
