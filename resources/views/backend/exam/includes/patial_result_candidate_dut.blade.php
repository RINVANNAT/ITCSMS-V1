
    <table class="table">
        <thead>
        <tr>
            <th>Order</th>
            <th>Register ID</th>
            <th>Name Khmer</th>
            <th>Name Latin</th>
            <th>Sexe</th>
            <th>Birth Date</th>
            <th>Study Ressident</th>
            <th>Result</th>
            <th>Department </th>
        </tr>
        </thead>
        <tbody>
        <?php $i =0;?>
        @foreach($candidateDUTs as $result)
            <?php $i++;?>
            <tr>
                <td><?php echo str_pad($i, 4, '0', STR_PAD_LEFT);?></td>
                <td>{{$result->register_id}}</td>
                <td>{{$result->name_kh}}</td>
                <td>{{$result->name_latin}}</td>
                <td>{{$result->gender}}</td>
                <td> <?php $date = explode(' ', $result->birth_date); echo $date[0];?></td>
                <td>{{$result->home_town}}</td>
                <td>{{$result->is_success}}</td>
                <td>{{$result->department_name}}</td>

            </tr>
        @endforeach
        </tbody>
    </table>