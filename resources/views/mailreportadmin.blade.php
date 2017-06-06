<style type="text/css">
    table{
        min-width: 270px;
        max-width: 270px;
        font-size: 12px;
        border: 2px dotted #D3D3D3;
        border-collapse: separate;
        margin-top: 20px;
    }
    td{
        border: 2px dotted #D3D3D3;
        border-collapse: separate;
    }
    .td_1{
        min-width: 200px;
        max-width: 200px;
    }
    .td_2{
        min-width: 70px;
        max-width: 70px;
    }
    .head_bloc{
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
    h1{
        color: #7da8c3;
    }
    h2{
        color: #2a88bd;
    }

    table tr{
        height: 20px;
    }
    .head{
        background-color: #D3D3D3;
    }
    .center{
        text-align: center;
    }

</style>
<div class="container">
    <div class="head_bloc">
        <h1>
            <b>FRG Time Sheet Reporting</b>
        </h1>
        <div>
            <h2><b>Attached are summaries of your timesheets</b></h2>
        </div>
        <div>
            Please review the submitted timesheets
        </div>
        <br>
        <h2>TimeSheets of Manager: {{$items->manager}}</h2>
        From {{$items->datafrom}} through {{$items->through}}
    </div>

    <table>
        <tr class="head">
            <td class="td_1">User</td>
            <td class="td_2">Status</td>
        </tr>
        @foreach($items->users as $user)
            <tr>
                <td class="td_1">
                    <b>{{ucwords($user)}}</b>
                </td>
                <td class="td_2 center">
                    Confirmed
                </td>
            </tr>
        @endforeach
    </table>
</div>