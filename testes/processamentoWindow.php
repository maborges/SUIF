<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        .box-load {
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            position: fixed;
            background-color: #fff;
            opacity: 0.7;
            z-index: 100;
            text-align: center;
            align-items: center;
        }

        .loader,
        .loader:after {
            border-radius: 50%;
            width: 10em;
            height: 10em;
        }

        .loader {
            margin: 60px auto;
            font-size: 10px;
            position: relative;
            text-indent: -9999em;
            border-top: 1.1em solid rgba(17, 156, 7, 0.2);
            border-right: 1.1em solid rgba(17, 156, 7, 0.2);
            border-bottom: 1.1em solid rgba(17, 156, 7, 0.2);
            border-left: 1.1em solid #119c07;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-animation: load8 1.1s infinite linear;
            animation: load8 1.1s infinite linear;
        }

        @-webkit-keyframes load8 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes load8 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>

</head>

<body onload="loading()">

    <?php
    $nome = $_POST['nome'] ?? 'Informe o seu nome';
    ?>

    <div class="box-load">
        <div class="loader"></div>
        <h2 class="loadTitle">Aguarde...</h2>
        <p class="loadMessage">Processando as informações.</p>
    </div>

    <h2>A basic HTML table</h2>


    <div class="content">
        <form action="processamentoWindow.php" method="post">
            <h2>Cabeçalho</h2>
            <p>Somente teste..</p>

            <input hidden type="number" name="nome" value="<?= $nome ?>">

            <button type='submit' name="btnStartProcess">
                Iniciar Processo..
            </button>

            <?php echo "<script>loadingChangeTitle('marcos a. borges');</script>" ?>

            <table style="width:100%">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Country</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($a = 0; $a <= 110100; $a++) : ?>
                        <tr>
                            <td>Alfreds Futterkiste</td>
                            <td>Maria Anders</td>
                            <td>Germany</td>
                        </tr>

                        <?php sleep(0.2); ?>

                    <?php endfor ?>

                </tbody>
            </table>

        </form>
    </div>

    <script>
        function loading() {
            document.getElementsByClassName('box-load')[0].style.display = 'none';
        }

        function loadingChangeTitle(loadinTitle) {
            document.getElementsByClassName('box-load')[0].style.display = 'none';
            const element = document.getElementsByClassName("box-load")[0];
            element.getElementsByClassName("loadinTitle")[0].innerHTML = loadinTitle;
        }
    </script>


</body>

</html>