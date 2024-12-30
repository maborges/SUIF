<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Center the loader */
        .loader {
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 9999;
            width: 120px;
            height: 120px;
            margin: -76px 0 0 -76px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            opacity: 0.7;
            color: red;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Add animation to "page content" */
        .animate-bottom {
            position: relative;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 1s;
            animation-name: animatebottom;
            animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }

            to {
                bottom: 0px;
                opacity: 1
            }
        }

        @keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }

            to {
                bottom: 0;
                opacity: 1
            }
        }

        #myDiv {
            display: none;
            text-align: center;
        }
    </style>
</head>

<body onload="hidePage()">

    <div class="loader" display=block>
    </div>

    <div class="animate-bottom">
        <form action="processamentoSelf.php" method="post" onsubmit="showPage()">
            <h2>Tada!</h2>
            <p>Some text in my newly loaded page..</p>

            <button type='submit' name="btnStartProcess">
                Iniciar Processo...
            </button>
        </form>
    </div>

    <?php sleep(4);?>

    <script>
        function hidePage() {
//            document.getElementsByClassName("loader")[0].style.display = 'none';
            console.log('hidePage');
            var element = document.getElementsByClassName("loader")[0];
            console.log(element.style.display);
            element.style.display = 'none';
            console.log(element.style.display);

        }

        function showPage() {
            console.log('showPage');
            var element = document.getElementsByClassName("loader")[0];
            console.log(element.style.display);
            element.style.display = 'block';
            console.log(element.style.display);
        }

    </script>

</body>

</html>