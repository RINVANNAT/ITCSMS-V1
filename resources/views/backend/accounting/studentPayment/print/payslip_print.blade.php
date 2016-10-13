@extends('backend.layouts.printing_portrait_a4_invoice')
@section('css')
    <style>
        .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
            background-color: #3c8dbc;
        }

        #table_modal_payment {
            border: 1px solid;
        }

        .table_modal_payment strong {
            padding-left: 15px;
            padding-right: 15px;
        }

        .table_modal_payment div {
            padding-top: 5px;
            padding-bottom: 5px;
        }
    </style>
@endsection
@section('content')
    <!-- Main content -->
    <div class="page">
        <div style="width: 100%;max-height: 45%;">
            @include('backend.accounting.studentPayment.print.single_payslip_template')
        </div>
        <div style="width:100%;height: 10mm;display: table;">
            <div style="display: table-cell;vertical-align: middle;"><hr/></div>
        </div>

        <div style="width: 100%;max-height: 45%;">
            @include('backend.accounting.studentPayment.print.single_payslip_template')
        </div>
    </div>

@endsection

@section('scripts')
    <script>

    </script>
@stop

