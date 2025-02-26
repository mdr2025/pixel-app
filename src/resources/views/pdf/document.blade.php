<!DOCTYPE html>
<html >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link
        rel="stylesheet"
        href={{public_path('pdf/Styles/baseStyles.css')}}
    />
    <style>

        .wrapper-page {
            page-break-after: always;
        }
        .wrapper-page:last-child {
            page-break-after: avoid;
        }


    </style>
</head>
<body>

        @include('pdf.sharedComponents.footer', ['header'=> $header])
        @include('pdf.sharedComponents.table',['columns' => $columns])

</body>
</html>
