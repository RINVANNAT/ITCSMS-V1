<htlml>
    <head>
        <title>Print Internship Certificate</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="{{ asset('css/backend/prints/prints.css') }}"/>
        <style type="text/css">
            @font-face {
                font-family: khmer_m1;
                src: url("{{ asset('assets/fonts/Bayon.ttf') }}");
            }

            @font-face {
                font-family: times_new_roman_normal;
                src: url("{{ asset('fonts/TIMES.TTF') }}");
            }

            @font-face {
                font-family: times_new_roman_normal_bold;
                src: url("{{ asset('fonts/Times_New_Roman_Bold.ttf') }}");
            }

            .page {
                width: 210mm;
                height: 297mm;
                margin: 0 auto;
                border: 0 !important;
                padding: 4.4cm 1.2cm 1.9cm 1.4cm;
                student-listborder-radius: 0px !important;
                background: white;
                box-shadow: none !important;
                position: relative;
                font-family: "Times New Roman", serif !important;
            }
            .page {
                overflow: hidden;
                page-break-after: always;
            }
            
            .student-label, .student-list {
                display: inline-block;
                vertical-align: top;
                padding-left: 15px;
            }
            .student-list {
                margin-top: 4px;
            }
            .contact_detail p {
                line-height: 0.8em;
            }
            p, span{
                font-family: times_new_roman_normal !important;
                font-size: 18px !important;
                line-height: 1.2;
            }
            p.list-student-item {
                line-height: 0.8;
            }
            .break-line {
                margin-top: 4px;
            }
        </style>
    </head>

    <body>
    @foreach($internships as $internship)
        @if(count($internship->internship_student_annuals)>1)
            @include('backend.internship.includes.multiple_transcript')
        @else
            @include('backend.internship.includes.single_transcript')
        @endif
    @endforeach
    </body>
</htlml>