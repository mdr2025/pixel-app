<!DOCTYPE html>
    <html>
    <html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <style>
        html {
            margin: 13px;
        }
        body{
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans;
        }
        .tableContainer{
            margin: 0px 10px;
        }
        table {
            font-weight: bold;
            margin-top: 10px;
            border: 1px solid rgb(223, 223, 223);
            width: 100%;
            border-collapse: collapse;
            max-width: 745px;
        }
        .tableHeader {
            background-color: rgb(243, 242, 247);
        }
        .tableHeaderCell {
            color: rgb(96, 96, 96);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-size: 12px;
            border-bottom: 1px solid rgb(223, 223, 223);
            text-align: center;
            padding: 12px 0px;
        }
        .tableBodyCell {
            color: rgb(96, 96, 96);
            text-transform: capitalize;
            font-size: 12px;
            padding: 2px 0px;
            border-right: 1px solid rgb(223, 223, 223);
            text-align: center;
        }
        .tableRow {
            border-bottom: 1px solid #dfdfdf;
        }

    </style>
</head>
<body>
    <div class="page wrapper-page">
        <div class="tableContainer">
            <table>
                <thead class="tableHeader">
                    <tr class="tableRow">
                        @foreach ($columns as $column)
                        <th class="tableHeaderCell" style="width: {{ $column['width'] }}; border-right: {{ $loop->last ? '' : '1px solid rgb(223, 223, 223)' }}">{{ $column['header'] }}</th>                    @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $index => $row)
                        <tr class="tableRow">
                            @foreach ($columns as $column)
                            <td class="tableBodyCell" style="text-align:center">
                                    <x-text-container tag="span" text="{{
                                        is_callable($column['cell']) ? $column['cell']($row, $index) : $column['cell']
                                }}" />
                            </td>
    
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
