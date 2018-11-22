@extends( 'layouts.app' )
@section( 'title', 'Income' )
@section( 'content' )
@auth

    <h4 class="text-center">{{ date( 'F' ) }}</h4>
    <div class="flash-message"></div>
        <table id="incomedt" class="table table-striped" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">Name</th>
                    <th class="th-sm">Category</th>
                    <th class="th-sm">Monthly</th>
                    <th class="th-sm">Amount</th>
                    <th class="th-sm">Settings</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $incomes as $income)
                    <tr id="incomeRow{{ $income->id }}">
                        <td>{{ $income->name }}</td>
                        <td>{{ $income->category->name }}</td>
                        <td>
                            @if( $income->monthly == 1 )
                                {{ 'Yes' }}
                            @else
                                {{ 'No' }}
                            @endif
                        </td>
                        <td class="green-text">{{ $income->amount }}kr</td>
                        <td class="">
                            <a href="{{ route( 'income.edit.{id}', [ 'id' => $income->id ] ) }}" class="blue-text">Edit </a>
                             /
                            <form class="income-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $income->id }}">
                                <button type="submit" name="submit" class="no-btn p-0">
                                    <a class="red-text">Remove</a>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <div class="text-center">
            <p>Missing an income? Click the button below to add more</p>
            <form class="addIncome">
                @csrf
                <button type="submit" name="addIncome" class="btn btn-primary">Add income</button>
            </form>
        </div>
        {{-- <div class="text-center">
            <p>No incomes found, click the button below to start adding one.</p>
            <form id="addIncome" method="GET" action="{{ route( 'income.create' ) }}">
                @csrf
                <button type="submit" name="addIncome" class="btn btn-primary">Add income</button>
            </form>
        </div> --}}

@endauth
@endsection
