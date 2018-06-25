<table id="table_age">
    <tr>
        <th colspan="25">
            <center><span class="font-head">ព្រះរាជាណាចក្រកម្ពុជា</span></center>
            <br/>
            <center><span class="font-muol">ជាតិ សាសនា ព្រះមហាក្សត្រ</span></center>
        </th>
    </tr>
    <tr>
        <td colspan="25">
            <span class="font-muol">ក្រសួងអប់រំ យុវជន ​និងកីឡា</span>
        </td>
    </tr>
    <tr>
        <td colspan="25">
            វិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា
        </td>
    </tr>
    <tr>
        <td colspan="25" align="center" class="blue">
            <span>ស្ថិតិនិស្សិត តាមអាយុ ថ្នាក់</span>
            <span>{{$degree_name}}</span>
            <span>និងតាមឆ្នាំ ឆ្នាំសិក្សា<span id="academicYear">{{$academic_year_name}} ឆមាសទី {{$semester_id}}</span></span>
        </td>
    </tr>
    <tr align="center" class="insertBorder">
        <td rowspan="3">
            <span>អាយុ</span>
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
    <tr align="center" class="insertBorder" id="row_header">
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
    @foreach($data as $age)
        <tr align='center' class='insertBorder'>
            <td>{{$age['name']}}</td>
            @foreach($age['data'] as $grade)
                <td>{{$grade['st']}}</td>
                <td>{{$grade['sf']}}</td>
                <td>{{$grade['pt']}}</td>
                <td>{{$grade['pf']}}</td>
            @endforeach
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td colspan="24">
            <span>សំគាល់ៈចំពោះគ្រឹះស្ថានឧត្តមសិក្សាណា ដែលបណ្តុះបណ្តាលលើសពី៤ ឬ៥ឆ្នាំ អាចបន្តទំព័របាន</span>
        </td>
    </tr>
    <tr align="center">
        <td colspan="16"></td>
        <td colspan="9">
            <span>ធ្វើនៅ<span id="place">.............</span>ថ្ងៃទី<span id="day">.............</span>ខែ<span id="month">............</span>ឆ្នាំ<span id="year">២០១......</span></span>
            <br/>
            <span>សាកលវិទ្យាធិការ/នាយក</span>
        </td>
    </tr>
</table>