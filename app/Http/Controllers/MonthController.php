<?php

namespace App\Http\Controllers;

use Session;
use View;

use Auth;
use App\User;
use App\Income;
use App\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentYear = date( 'Y' );
        $currentMonth = date( 'm' );
        $user = Auth::user();
        $incomes = Income::where( 'user_id', $user->id )
            ->whereYear( 'created_at', $currentYear )
            ->whereMonth( 'created_at', $currentMonth )
            ->orderBy( 'amount', 'desc' )
            ->limit( 3 )
            ->get();

        $expenses = Expense::where( 'user_id', $user->id )
            ->whereYear( 'created_at', $currentYear )
            ->whereMonth( 'created_at', $currentMonth )
            ->orderBy( 'amount', 'desc' )
            ->limit( 3 )
            ->get();

        return view( 'dashboard' )
            ->with( 'incomes', $incomes )
            ->with( 'expenses', $expenses )
            ->with( 'currentYear', $currentYear )
            ->with( 'currentMonth', $currentMonth );
    }

    public function previous()
    {
        $user = User::with( 'incomes', 'expenses' )->find( Auth::user()->id );
        $incomes = $user->incomes;
        $expenses = $user->expenses;

        $transactions = collect( $incomes );
        $mergedTransactions = $transactions->merge( $expenses );
        $mergedTransactions->all();

        $transactionsByYearMonth = $mergedTransactions->groupBy([
            function( $date )
                {
                    return Carbon::parse( $date->created_at )->format( 'Y' );
                },
                function( $date )
                {
                    return Carbon::parse( $date->created_at )->format( 'F' );
                }
        ]);


        return view( 'previous' )
            ->with( 'transactionsByYearMonth', $transactionsByYearMonth )
            ->with( 'incomes', $incomes )
            ->with( 'expenses', $expenses );
    }
    public function detailedPrevious()
    {
        $user = User::with( 'incomes', 'expenses' )->find( Auth::user()->id );
        $incomes = $user->incomes;
        $expenses = $user->expenses;

        $transactions = collect( $incomes );
        $mergedTransactions = $transactions->merge( $expenses );
        $mergedTransactions->all();

        $transactionsByYearMonth = $mergedTransactions->groupBy([
            function( $date )
                {
                    return Carbon::parse( $date->created_at )->format( 'Y' );
                },
                function( $date )
                {
                    return Carbon::parse( $date->created_at )->format( 'F' );
                }
        ]);

        return view( 'detailed-prev' )
            ->with( 'transactionsByYearMonth', $transactionsByYearMonth )
            ->with( 'incomes', $incomes )
            ->with( 'expenses', $expenses );
    }
}
