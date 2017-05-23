<style type="text/css">
    table{
        width: 750px;
        max-width: 750px;
        font-size: 10px;
        border-collapse: collapse;
    }
    table tr{
        height: 20px;
    }
    .head td{
        background-color: #D3D3D3;
        border: 1px solid #fff;
        border-bottom: none;
        border-collapse: collapse;
    }
    .name_head{
        background-color: #BEBEBE;
    }
    .body_data{
        border-top: 1px solid #d3d3d3;
    }
    .center{
        text-align: center;
    }
    .total td{
        padding-top: 7px;
    }
</style>
<div class="container">

    <div><b>Summary Report</b></div>  <br/>
    <div>Viewing by person; From {{$items['from']}} through {{$items['through']}}</div>
    <br/>
    <a href="{{ route('pdfview',['download'=>'pdf']) }}">Download PDF</a>

    <table>
        <tr class="head">
            <td>Client</td>
            <td>Project</td>
            <td>Module</td>
            <td>Work type</td>
            <td>Task</td>
            <td>Description</td>
            <td>Date</td>
            <td>Time</td>
            <td>Billable</td>
        </tr>
        <?php $total=0;?>
        @foreach($items['data'] as $item)
            <tr>
                <td colspan= '9' class="name_head">
                    {{ucwords($item['username'])}}
                </td>
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
                <td class="center">
                   <b>{{number_format($total_user,3)}}</b>
                </td>
            </tr>
            <?php $total+=$total_user;?>
        @endforeach
                    <tr class="head total">
                        <td colspan= '7'>
                            <b>TOTAL</b>
                        </td>
                        <td colspan= '2'>
                            <b>{{number_format($total,3)}}</b>
                        </td>
                    </tr>
        </tbody>
    </table>
</div>