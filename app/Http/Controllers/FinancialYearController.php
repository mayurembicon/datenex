<?php

namespace App\Http\Controllers;


use App\FinancialYear;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinancialYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $financial_year = FinancialYear::all();

        return view('FinancialYear.index')->with('financial_year', $financial_year);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('FinancialYear.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'start_date' => 'required|date_format:d-m-Y',
            'end_date' => 'required|date_format:d-m-Y|after:start_date',
            'is_default' => 'required|in:Y,N',
        ]);


        FinancialYear::create([

            'start_date' => date('Y-m-d ', strtotime($request->post('start_date'))),
            'end_date' => date('Y-m-d', strtotime($request->post('end_date'))),
            'current_year' => date('Y ', strtotime($request->post('start_date'))) . '-' . date('Y', strtotime($request->post('end_date'))),
            'is_default' => $request->post('is_default'),
        ]);
        $request->session()->flash('success', 'Financial year created successfully');
        return redirect()->route('financial-year.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\FinancialYear $financialYear
     * @return \Illuminate\Http\Response
     */
    public function show(FinancialYear $financialYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\FinancialYear $financialYear
     * @return \Illuminate\Http\Response
     */
    public function edit($ID)
    {
        $financial_Year = FinancialYear::find($ID);
        return view('FinancialYear.create')->with('financial_year', $financial_Year);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\FinancialYear $financialYear
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $ID)
    {
        $validatedData = $request->validate([
                'start_date' => 'required|date_format:d-m-Y',
            'end_date' => 'required|date_format:d-m-Y|after:start_date',

        ]);


        $financial_Year = FinancialYear::find($ID);
        $financial_Year->start_date = date('Y-m-d', strtotime($request->post('start_date')));
        $financial_Year->end_date = date('Y-m-d', strtotime($request->post('end_date')));
        $financial_Year->current_year = date('Y ', strtotime($request->post('start_date'))) . '-' . date('Y', strtotime($request->post('end_date')));
        $financial_Year->is_default = $request->post('is_default');
        $financial_Year->save();
        $request->session()->flash('warning', 'Financial year updated successfully');
        return redirect()->route('financial-year.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\FinancialYear $financialYear
     * @return \Illuminate\Http\Response
     */
    public function destroy($ID, Request $request)
    {
        $financialYear = FinancialYear::find($ID);
        $status = $message = '';
        if (FinancialYear::destroy($financialYear->financial_year_id)) {
            $status = 'error';
            $message = 'Financial year deleted successfully';
        } else {
            $status = 'info';
            $message = 'Financial year failed to delete';
        }
        $request->session()->flash($status, $message);
        return redirect()->route('financial-year.index');
    }
}
