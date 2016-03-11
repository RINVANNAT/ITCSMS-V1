@extends('backend.layouts.printing')

@section('after-styles-end')
<style>
    table{
        border: 1px;
        border-collapse: collapse;
        margin: 0px auto;
        width: 100%;
    }
    *{
        font-family: "Khmer OS";
    }
    .font-muol, .font-head{
        font-family: "Khmer OS Muol Light" !important;
    }
    .font-head{
        font-size: 20px;
    }
    table tr.insertBorder td{
        border: 1px solid black;
    }
    .blue{
        color: blue;
    }
</style>
@stop

@section('content')

@include('backend.studentAnnual.reporting.template_report_student_redouble')

@stop