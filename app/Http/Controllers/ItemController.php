<?php

namespace App\Http\Controllers;

use App\Category;
use App\CurrentStock;
use App\Financial_Year;
use App\FinancialYear;
use App\Invoice;
use App\Item;
use App\ItemCurrentStock;
use App\ItemOpeningStock;
use App\OpeningStock;
use App\Purchase;
use App\TaxRate;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedHsn='';
        $selectedUnit='';
        $selectedItem='';
        $search_item = $request->session()->get('search_itemmaster');

        $items = Item::select('*');
        if (!empty($search_item['name'])) {
            $items->whereRaw("`name` LIKE '%" . $search_item['name'] . "%'");
            $selectedItem = $search_item['name'];

        }
        if(!empty($search_item['rating'])){
            $items->whereRaw("`ratedIndex` LIKE '%".$search_item['rating']."%'");
        }
        if(!empty($search_item['unit'])){
            $items->whereRaw("`unit` LIKE '%".$search_item['unit']."%'");
            $selectedUnit = $search_item['unit'];

        }
        if(!empty($search_item['hsn'])){
            $items->whereRaw("`hsn` LIKE '%".$search_item['hsn']."%'");
            $selectedHsn = $search_item['hsn'];

        }

        $item =$items->paginate(10);
        return view('itemmaster.index')->with(compact('item','search_item','selectedHsn','selectedUnit','selectedItem'));
    }
    public function searchItem(Request $request)
    {
        $search = array();
        $search['name'] =$request->post('product_name');
        $search['rating'] =$request->post('rating');
        $search['unit'] =$request->post('unit');
        $search['hsn'] =$request->post('hsn');
        $request->session()->put('search_itemmaster',$search);
        return redirect()->route('item-master.index');
    }
    public function clearItem(Request $request)
    {
        $request->session()->forget('search_itemmaster');
        return redirect()->route('item-master.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $opningstock = OpeningStock::all();
        $taxrate = TaxRate::all();
        $unit=Unit::all();
//        echo "<pre>";
//        print_r($taxrate);exit();
        return view('itemmaster.create')->with(compact('taxrate', 'opningstock','unit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $financial = FinancialYear::where('is_default', 'Y')->first();
        $messages = [
            'name.required' => 'Please Enter Item Name',
        ];
        $rules = [
            'type' =>'required|in:good,service',
            'name' => 'required|max:150|unique:itemmaster,name',

        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $item = new Item();
        $item->taxrate = $request->post('taxrate');
        $item->ratedIndex = $request->post('ratedIndex');
        $item->name = $request->post('name');
        $item->type = $request->post('type');
        $item->sale_rate = $request->post('sale_rate');
        $item->purchase_rate = $request->post('purchase_rate');
        $item->discount_amount = $request->post('discount_amount');
        $item->unit = $request->post('unit');
        $item->hsn = $request->post('hsn');
        $item->sku = $request->post('sku');
        $item->descripation = $request->post('descripation');
        $item->save();

        $itemID = $item->item_id;


        $opningstock = new OpeningStock();
        $opningstock->item_id = $itemID;
        $opningstock->financial_year_id = $financial->financial_year_id;
        $opningstock->opening_stock = empty($request->post('opening_stock')) ? 0 : $request->post('opening_stock');
        $opningstock->save();

        $current_stock = new CurrentStock();
        $current_stock->item_id = $itemID;
        $current_stock->current_stock =empty($request->post('opening_stock')) ? 0 : $request->post('opening_stock');
        $current_stock->save();

        if ($request->ajax()) {
            return response()->json(['success' => 'Item created successfully.', 'item' => ['name' => $item->name, 'item_id' => $item->item_id]]);
        } else {
            $request->session()->flash('success', 'Item created successfully');
            return redirect()->route('item-master.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($ID)
    {
        $opningstock = DB::table('opening_stock')->first();
        $item = Item::find($ID);
        $taxrate = TaxRate::all();
        $unit=Unit::all();
        return view('itemmaster.create')->with(compact('item', 'taxrate', 'opningstock','unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ID)
    {
        $financial = FinancialYear::where('is_default', 'Y')->first();

        $messages = [
            'name.required' => 'Please Enter Item Name',
        ];
        $rules = [
            'type' =>'required|in:good,service',
            'name' => 'required|max:150|unique:itemmaster,name',
            'sale_rate'=>'required|numeric',
            'purchase_rate'=>'required|numeric',
            'discount_amount'=>'required|numeric',
            'opening_stock'=>'required|numeric',

        ];
        $item = Item::find($ID);
        $item->taxrate = $request->post('taxrate');
        $item->ratedIndex = $request->post('ratedIndex');
        $item->name = $request->post('name');
        $item->type = $request->post('type');
        $item->sale_rate = $request->post('sale_rate');
        $item->purchase_rate = $request->post('purchase_rate');
        $item->discount_amount = $request->post('discount_amount');
        $item->sku = $request->post('sku');
        $item->unit = $request->post('unit');
        $item->hsn = $request->post('hsn');
        $item->descripation = $request->post('descripation');
        $item->save();


        OpeningStock::where('item_id', $ID)
            ->update(['opening_stock' => $request->post('opening_stock')]);

        CurrentStock::where('item_id', $ID)
            ->update(['current_stock' => $request->post('opening_stock')]);


        $request->session()->flash('warning', 'Item updated successfully');
        return redirect()->route('item-master.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($ID, Request $request)
    {


        $status = $message = '';
        if (Item::destroy($ID)) {
            $status = 'error';
            $message = 'Item deleted successfully.';
        } else {

            $status = 'info';
            $message = 'Item failed to delete.';
        }

        $request->session()->flash($status, $message);

        return redirect()->route('item-master.index');
    }

    public function getItemInformation()
    {
        $ItemID = \request()->input('item_id');
        $item = DB::table('itemmaster')->where('item_id', $ItemID)->first();
        return response()->json(['purchase_rate' => $item->purchase_rate,'sale_rate' => $item->sale_rate, 'taxrate' => $item->taxrate,'unit'=>$item->unit, 'discount' => $item->discount_amount], 200);
    }

    public function Itemrating(Request $request)
    {

        if ($request->ajax()) {
            return response()->json(['success' => 'Rating successfully.', 'ratedIndex' => $request->post('ratedIndex')+1]);
        } else {

            return redirect()->route('item-master.index');
        }
    }
    public function import()
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $spreadsheet = $reader->load("item.xlsx");
        print_r($spreadsheet);exit();
    }




}
