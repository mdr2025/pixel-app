<!DOCTYPE html>
    <html>
    <html>
    <head>
    <meta charset="UTF-8">
    <style>
            html {
                margin: 15px;
            }
            body{
                font-family: DejaVu Sans, sans;
            }
            .page{
                border: 1px solid #606060;
                position: relative;
                height: 99.5%;
            }
            .header{
                font-size: 26px;
                font-weight: bold;
                text-transform: uppercase;
                color: #606060;
                margin-top: 200px;
            }
            .pdfBackgroundContainer{
                position: absolute;
                width: 100%;
                left: 0px;
                z-index: -1;
                bottom: 0;
            }
            .pdfBackground{
                width: 100%;
                z-index: 1;
            }
            .word-wrapper {
                width:100%;
                text-align:center;
                z-index: 1;
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                top: 230px;
            }
            .word{
                font-size:29px;
                font-weight:bold;
                z-index: 1;
                letter-spacing: 1px;
            }
            .blueLetter{
                color: #1b2955;
                font-weight: bold;
                margin:0px;
            }
            .redLetter{
                color: #d41d1d;
                font-weight: bold;
                margin:0px;
            }
            .logo{
                width: 80px;
                height: 60px;
                position: absolute;
                bottom: 500px;
                left: 20px;
                z-index: 99;
            }
            .cover_header{
                color: rgb(96, 96, 96);
                font-weight: bold;
                margin-top: 150px;
                text-align: center;
                font-size: 36px;
            }
        </style>
    </head>
    <body>
        <div class="page">
            <div style="text-align:center;z-index:2">
                <p style="font-size:40px;color:red">{{$header}}</p>
                <p style="text-align:center;width:100%">{{$description}}</p>
            </div>
            <div class="pdfBackgroundContainer">
                <img class="pdfBackground"  src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/Pdf/Pictures/pdfBackground.jpg'))) }}" alt=""/>
                <div class="word-wrapper">
                    <div class="word">
                        <span class="blueLetter">Y<span class="redLetter">O</span><span class="blueLetter">UR</span></span>&nbsp;
                        <span class="blueLetter">S<span class="redLetter">A</span><span class="blueLetter">FETY</span></span>&nbsp;
                        <span class="blueLetter">I<span class="redLetter">S</span></span>&nbsp;
                        <span class="blueLetter">O<span class="redLetter">U</span><span class="blueLetter">R</span></span>&nbsp;
                        <span class="blueLetter">M<span class="redLetter">ISSIO</span><span class="blueLetter">N</span></span>
                    </div>
                </div>
                <img class="logo" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/Pdf/Pictures/WhitePixelLogo.png'))) }}" alt="" />
            </div>
        </div>
    </body>
    </html>
