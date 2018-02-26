<htlml>
    <head>
        <title>Print Internship Certificate</title>
        <link rel="stylesheet" href="{{ asset('css/backend/prints/prints.css') }}"/>
        <style type="text/css">
            @font-face {
                font-family: crimson_roman;
                src: url("{{ asset('fonts/Crimson-Roman.ttf') }}");
            }

            /*.half-width {*/
                /*width: 50%;*/
            /*}*/

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
                padding: 4.5cm 2.3cm 3cm 2.5cm;
                border-radius: 0px !important;
                background: white;
                box-shadow: none !important;
                position: relative;
                font-family: "Times New Roman", serif !important;
            }

            .tran-header {
                padding: 0px !important;
            }

            .student-label, .student-list {
                display: inline-block;
                vertical-align: top;
                padding-left: 15px;
            }
            .contact_detail p {
                line-height: 0.8em;
            }
            p {
                font-size: 14px;
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