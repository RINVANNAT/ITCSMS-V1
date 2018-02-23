<table>
    <tr align='center' >
        <th colspan="28">
            <span class="font-head">ព្រះរាជាណាចក្រកម្ពុជា</span>
            <br/>
            <span class="font-muol">ជាតិ សាសនា ព្រះមហាក្សត្រ</span>
        </th>
    </tr>
    <tr align='center' >
        <td colspan="28">
            <span class="font-muol">ក្រសួងអប់រំ យុវជន ​និងកីឡា</span>
        </td>
    </tr>
    <tr>
        <td colspan="28">
            <span>ឈ្មោះគ្រឹះស្ថានសិក្សាៈ</span>
        </td>
    </tr>
    <tr>
        <td colspan="28" align="center" class="blue">
            <span>ស្ថិតិនិស្សិតកំពុងសិក្សា </span>
            <span class="font-muol">ថ្នាក់ {{$degree_name}}</span>
            <span>ឆ្នាំសិក្សា<span id="academicYear">{{$academic_year_name}}</span></span>
        </td>
    </tr>
    <tr align="center" class="insertBorder">
        <td rowspan="3">
            <span>ល.រ</span>
        </td>
        <td rowspan="3"><span>មហាវិទ្យាល័យ</span></td>
        <td rowspan="3"><span>ឯកទេស / ជំនាញ</span></td>
        <td rowspan="3">
            <span>រយៈ</span>
            <br/>
            <span>ពេល</span>
            <br/>
            <span>បប</span>
        </td>
        <td colspan="4"><span>ឆ្នាំទី១</span></td>
        <td colspan="4"><span>ឆ្នាំទី២</span></td>
        <td colspan="4"><span>ឆ្នាំទី៣</span></td>
        <td colspan="4"><span>ឆ្នាំទី៤</span></td>
        <td colspan="4"><span>ឆ្នាំទី៥</span></td>
        <td colspan="4"><span>សរុប</span></td>
    </tr>
    <tr align="center" class="insertBorder">
        <td colspan="2"><span>អាហា.</span></td>
        <td colspan="2"><span>បង់ថ្លៃ</span></td>
        <td colspan="2"><span>អាហា.</span></td>
        <td colspan="2"><span>បង់ថ្លៃ</span></td>
        <td colspan="2"><span>អាហា.</span></td>
        <td colspan="2"><span>បង់ថ្លៃ</span></td>
        <td colspan="2"><span>អាហា.</span></td>
        <td colspan="2"><span>បង់ថ្លៃ</span></td>
        <td colspan="2"><span>អាហា.</span></td>
        <td colspan="2"><span>បង់ថ្លៃ</span></td>
        <td colspan="2"><span>អាហា.</span></td>
        <td colspan="2"><span>បង់ថ្លៃ</span></td>
    </tr>
    <tr align="center" class="insertBorder">
        <td><span>សរុប</span></td>
        <td><span>ស្រី</span></td>
        <td><span>សរុប</span></td>
        <td><span>ស្រី</span></td>
        <td><span>សរុប</span></td>
        <td><span>ស្រី</span></td>
        <td><span>សរុប</span></td>
        <td><span>ស្រី</span></td>
        <td><span>សរុប</span></td>
        <td><span>ស្រី</span></td>
        <td><span>សរុប</span></td>
        <td><span>ស្រី</span></td>
        <td><span>សរុប</span></td>
        <td><span>ស្រី</span></td>
        <td><span>សរុប</span></td>
        <td><span>ស្រី</span></td>
        <td><span>សរុប</span></td>
        <td><span>ស្រី</span></td>
        <td><span>សរុប</span></td>
        <td><span>ស្រី</span></td>
        <td><span>សរុប</span></td>
        <td><span>ស្រី</span></td>
        <td><span>សរុប</span></td>
        <td><span>ស្រី</span></td>
    </tr>

    <?php
        $count_dept = 1;
        $count_row = 1;
    ?>

    @foreach($data as $department)
        @if($count_dept < sizeof($data))
            @foreach($department['department_options'] as $key => $option)
                <tr class="insertBorder" align='center' >
                    <td>{{$count_row}}</td>
                    <td>{{$department['name_kh']}}</td>
                    <td>{{$option['code']}}</td>
                    <td>៣⁣ ឆ្នាំ</td>
                    @foreach($option['data'] as $grade)
                        <td>{{$grade['st']}}</td>
                        <td>{{$grade['sf']}}</td>
                        <td>{{$grade['pt']}}</td>
                        <td>{{$grade['pf']}}</td>
                    @endforeach

                </tr>
                <?php $count_row++; ?>
            @endforeach
        @endif
        <?php $count_dept++; ?>
    @endforeach
    <tr align="center" class="insertBorder">
        <td colspan="3"><span><b>សរុប</b></span></td>
        <td><span id="totalTime"></span></td>
        @foreach(end($data) as $value)
            <td>{{$value['st']}}</td>
            <td>{{$value['sf']}}</td>
            <td>{{$value['pt']}}</td>
            <td>{{$value['pf']}}</td>
        @endforeach

    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="26">
            <span>សំគាល់ៈចំពោះគ្រឹះស្ថានឧត្តមសិក្សាណា ដែលបណ្តុះបណ្តាលលើសពី៤ ឬ៥ឆ្នាំ អាចបន្តទំព័របាន</span>
        </td>
    </tr>
    <tr align="center">
        <td colspan="17"></td>
        <td colspan="11">
            <span>ធ្វើនៅ<span id="place">.............</span>ថ្ងៃទី<span id="day">.............</span>ខែ<span id="month">............</span>ឆ្នាំ<span id="year">២០១......</span></span>
            <br/>
            <span>សាកលវិទ្យាធិការ/នាយក</span>
        </td>
    </tr>
</table>