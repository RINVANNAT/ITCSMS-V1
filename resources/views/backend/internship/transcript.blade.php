@extends('backend.layouts.printing_portrait_a4_transcript')
@section('title', 'Print Internship Certificate')
@section('after-styles-end')
    <link rel="stylesheet" href="{{ asset('css/backend/prints/prints.css') }}"/>
    <style>
        .tran-header {
            padding: 50px;
        }

        .align-justify {
            text-align: justify;
        }

        .page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            margin-top: 10mm;
            border: 0 !important;
            padding: 10mm 10mm 10mm 10mm;
            border-radius: 0px !important;
            background: white;
            box-shadow: none !important;
            position: relative;
            font-family: "Times New Roman" !important;
        }

        .contact_detail p {
            line-height: 12px;
        }

        body {
            background-color: #3A3A3A;
        }

        @media print {
            .page {
                page-break-after: auto;
            }
            p {page-break-inside: avoid;}
        }
    </style>
@stop

@section('page-header')
    <h1>
        {!! app_name() !!}
        <small>Print Internship</small>
    </h1>
@endsection

@section('content')
    @foreach($internships as $internship)
        @if(count($internship->internship_student_annuals)>1)
            @include('backend.internship.includes.multiple_transcript')
        @else
            @include('backend.internship.includes.single_transcript')
        @endif
    @endforeach
@endsection

@section('after-scripts-end')

@stop