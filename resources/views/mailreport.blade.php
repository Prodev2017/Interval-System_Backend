<style type="text/css">
    table{
        min-width: 1150px;
        max-width: 1150px;
        font-size: 12px;
        border: 2px dotted #D3D3D3;
        border-collapse: separate;
    }
    td{
        border: 2px dotted #D3D3D3;
        border-collapse: separate;
    }
    button{
        background-color: #2e3436;
        color: #fff;
        border: 1px solid #2e3436;
        border-radius: 5px;
        height: 30px;
    }
    button:hover{
        background-color: #636b6f;
        border-color: #636b6f;
    }
    .user_data{
        margin: 20px auto;
        width: 1150px;
        padding: 0 auto;
    }
    .user_name{
        width: 100%;
        margin: 15px auto;
    }
    .position{
        display: inline-block;
        right: 40px;
        position: absolute;
    }
    .td_1{
        min-width: 35px;
        max-width: 70px;
    }
    .td_2{
        min-width: 150px;
        max-width: 300px;
    }
    .td_3{
        min-width: 100px;
        max-width: 200px;
    }
    .td_4{
        min-width: 75px;
        max-width: 150px;
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
    .approve_all{
        margin-left: 20px;
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
    .bloc{
        width: 200px;
        display: inline-block;
    }
</style>
<div class="container">
    <div class="head_bloc">
        <h1>
            <b>FRG Time Sheet Reporting</b>
        </h1>
        <div>
            <h2><b>Attached are summaries of your timesheets</b></h2>
            <a href="{{ route('pdfview',['download'=>'pdf']) }}" class="position"><button>Download PDF</button></a>
        </div>
        <div>
            Please review the submitted timesheets
        </div>
        <br>
        <div>
            Click the <button disabled>Approve</button> button to approve a timesheet or the <button disabled>Approve All</button> to approve everyone
        </div>
        <h2>TimeSheets:</h2>
    </div>

        @foreach($items['data'] as $item)
            <div class="user_data">
                <div class="user_name">
                       <b class="bloc">{{ucwords($item['username'])}}</b>
                    <a><button>Approve</button></a>
                </div>
                <table>
                    <tr class="head">
                        <td class="td_1">Client</td>
                        <td class="td_1 ">Project</td>
                        <td class="td_2">Module</td>
                        <td class="td_3">Work type</td>
                        <td class="td_4">Task</td>
                        <td class="td_2">Description</td>
                        <td class="td_1">Date</td>
                        <td class="td_1">Time</td>
                        <td class="td_1">Billable</td>
                    </tr>
                <?php $total_user =0;?>
                @foreach($item['data'] as $data)
                    <tr class="body_data">
                        <td colspan= '2'>
                            <b>{{$data['client']}}</b><br>
                            #{{$data['clientlocalid']}} {{$data['project']}}
                        </td>
                        <td>
                            {{$data['module']}}
                        </td>
                        <td>
                            {{$data['worktype']}}
                        </td>
                        <td>
                            @if(isset($data['task']))
                                #{{$data['tasklocalid']}}: {{$data['task']}}
                            @endif
                        </td>
                        <td>
                            {{$data['description']}}
                        </td>
                        <td class="center">
                            {{$data['date']}}
                        </td>
                        <td class="center">
                            {{number_format($data['time'],3)}}
                            <?php $total_user += $data['time'] ?>
                        </td>
                        <td class="center">
                            {{$data['billable']?'Yes':'No'}}
                        </td>
                    </tr>
                @endforeach
                    <tr class="total">
                        <td colspan= '7'>
                            <b>Total for {{$item['username']}}</b>
                        </td>
                        <td class="center" colspan= '2'>
                            <b>{{number_format($total_user,3)}}</b>
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach
    <div class="approve_all">
        <a><button>Approve All</button></a>
    </div>
</div>