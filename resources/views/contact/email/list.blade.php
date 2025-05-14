{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 15/05/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<table class="table table-responsive col-md-12">
    <thead>
    <tr>
        <th>E-Mail</th>
    </tr>
    </thead>
    <tbody id="tableEmails">
    @foreach($person->emails as $email)
        <tr>
            <td>{{$email->email}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

