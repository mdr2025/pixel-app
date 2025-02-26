<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    html {
        margin: 13px;
    }

    body {
        margin: 0;
        padding: 0;
        /*  font-family: DejaVu Sans; */
    }

    .page {
        border: 1px solid #606060;
        position: relative;
        height: 99.5%;
    }

    .pdfBackgroundContainer {
        position: absolute;
        bottom: 0%;
        width: 100%;
        z-index: -1;
    }

    .word {
        font-size: 29px;
        font-weight: bold;
        position: absolute !important;
        bottom: 800px;
        right: 0%;
        transform: translate(22.5%);
        z-index: 999;
        width: 100%;
        letter-spacing: 1px;
    }

    .blueLetter {
        color: #1b2955;
        font-weight: bold;
        margin: 0px;
    }

    .redLetter {
        color: #d41d1d;
        font-weight: bold;
        margin: 0px;
    }

    .logo {
        width: 80;
        height: 60;
        position: absolute;
        bottom: 365px;
        left: 2%;
    }

    .cover_header {
        color: #606060;
        font-weight: bold;
        margin-top: 150px;
        text-align: center;
        font-size: 36px;
    }

    .rtl {
        direction: rtl
    }

    .uppercase {
        text-transform: uppercase;
    }
    </style>
</head>

<body>
    <div class="page2">
        <hr />
        <div style="width:100%">
            <x-text-container text="{{$arText}}" tag="p" />
        </div>
        <hr />
        <div style="width:100%">
            <x-text-container text="{{$enText}}" tag="p" />
        </div>
        <hr />
        <div style="width:100%">
            <x-text-container text="{{$mixedArText}}" tag="p" />
        </div>
        <hr />
        <div style="width:100%">
            <x-text-container text="{{$mixedEnText}}" tag="p" />
        </div>
    </div>
</body>

</html>