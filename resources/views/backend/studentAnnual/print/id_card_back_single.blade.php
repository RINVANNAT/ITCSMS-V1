<div class="page2">

    <div class="background1">
        <img width="100%" src="{{url('img/id_card/back_id_card.png')}}">
    </div>
    <div class="detail1">
                                <span class="address_title">
                                    អាសយដ្ឋាន ៖
                                </span>
                                <span class="address">
                                    ប្រអប់សំបុត្រលេខ៨៦​ មហាវិថីសហព័ន្ធរុស្សុី<br/>
                                    រាជធានីភ្នំពេញ ប្រទេសកម្ពុជា <br/>
                                    ទូរស័ព្ទ: (៨៥៥) ២៣ ៨៨០ ៣៧០/៨៨២ ៤០៤ <br/>
                                    ទូរសារ: (៨៥៥) ២៣ ៨៨០ ៣៦៩ <br/>
                                    សារអេឡិចត្រូនិច: info@itc.edu.kh <br/>
                                    គេហទំព័រ: www.itc.edu.kh

                                </span>
        <?php
        $date = null;
        $count = 0;
        if($back['degree_id'] == 1){
            if($back['grade_id'] < 3){
                $count = 2 - $back['grade_id'];
            } else {
                $count = 5 - $back['grade_id'];
            }
        } else if ($back['degree_id'] == 2){
            $count = 2 - $back['grade_id'];
        } else if ($back['degree_id'] == 3) {
            $count = 2;
        }
        ?>
        <span class="expired_date">ថ្ងៃផុតកំណត់/Expiry date: 30 September {{$back['academic_year_id'] + $count}}</span>
        <div class="barcode">
            <img src="data:image/png;base64,{{\Milon\Barcode\Facades\DNS1DFacade::getBarcodePNG(substr($back['id_card'], 1), 'C39')}}" alt="barcode" />
        </div>
        <span class="barcode_value">{{$back['id_card']}}</span>
                                <span class="message">
                                    ប្រសិនបើរើសបាន សូមជួយយកមកប្រគល់ឱ្យ <br/>
                                    ការិយាល័យសិក្សា នៃវិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា
                                </span>
    </div>
</div>