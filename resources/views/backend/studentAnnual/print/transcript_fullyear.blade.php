<link rel="stylesheet" href="{{ asset('css/transcript.css') }}">
<div class="transcript">
    {{--transcript header--}}
    <div class="transcript-header">
        <table class="head">
            <tr>
                <td colspan="2"><h4>Département: <span class="no-bold"> Génie Chimique et Alimentaire</span></h4></td>
            </tr>
            <tr>
                <td width="50%"><h4>Cycle: <span class="no-bold">Ingénieur</span> </h4></td>
                <td class="col-right" width="50%"><h4>Classe: <span class="no-bold">Cinquième année</span></h4></td>
            </tr>

            <tr>
                <td colspan="2" class="break-line"><h4>ID: <span class="no-bold">e20110258</span></h4></td>
            </tr>
            <tr>
                <td><h4>Nom et Prénom: SANN SOTHEARY</h4></td>
                <td class="col-right"><h4>Sexe: <span class="no-bold">Féminin</span></h4></td>
            </tr>
        </table>
        <div class="transcript-title">
            <h2>BULLETIN DE NOTES</h2>
            <h4>ANNEE SCOLAIRE : 2014-2015</h4>
        </div>
    </div>
    {{--transcript body--}}
    <div class="transcript-body">
        <div class="subject-credits-grades">
            <table class="subject">
                <tr>
                    <th align="left">Matières</th>
                    <th>Crédits</th>
                    <th>Mention</th>
                </tr>

                @for($i=1; $i<=10; $i++)

                    <tr>
                        <td>{{ $i }} - Agro-Industry management</td>
                        <td>{{ $i }}</td>
                        <td>A+</td>
                    </tr>

                @endfor
            </table>
        </div>
        <div class="gpa">
            <h4>Moyenne annuelle : 4.0</h4>
        </div>
        <div class="director-signature">
            <p>Phnom Penh, le 30 juillet 2015</p>
            <h4>Directeur Général Adjoint</h4>
        </div>
        <div class="clearfix"></div>
    </div>
    {{--transcript footer--}}
    <div class="transcript-footer">
        <div class="grading-system">
            <h4>SYSTÈME D&#39;ÉVALUATION:</h4>
            <table class="">
                <tr>
                    <td>A = 85% -100% = 4.00 = Excellent</td>
                    <td>C = 50% - 64% = 2.00 = Passable</td>
                </tr>
                <tr>
                    <td>B<sup>+</sup> = 80% - 84% = 3.50 = Très bien</td>
                    <td>D = 45% - 49% = 1.50 = Faible</td>
                </tr>
                <tr>
                    <td>B = 70% - 79% = 3.00 = Bien</td>
                    <td>E = 40% - 44% = 1.00 = Très Faible</td>
                </tr>
                <tr>
                    <td>C<sup>+</sup> = 65% - 69% = 2.5 = Assez bien</td>
                    <td>F = < 40% = 0.00 = Insuffisant</td>
                </tr>
            </table>
        </div>
        <div class="remark">
            <h4>Remarque :</h4>
            <ul class="list-remark" type="square">
                <li>La moyenne annuelle minimum pour passer à la classe supérieure est 2.0.</li>
                <li>Ce bulletin ne peut pas être donné une seconde fois.</li>
            </ul>
        </div>
    </div>
</div>