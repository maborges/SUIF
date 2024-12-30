<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modal Example...</title>
</head>


<!--
<script type="text/javascript" src="processamento.js"></script>
-->

<?PHP
include_once("../includes/config.php");
include_once("../includes/conecta_bd.php");
include_once("../includes/valida_cookies.php");
include_once("../helpers.php");

include_once("../includes/head.php"); 


// require_once("../includes/processando.php");


?>

</head>


<body>
    <form action="processamento.php" method="post">
        <h1>Teste tela da Call</h1>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cupiditate cum corporis quam vel illum assumenda ipsa deserunt, error at quos culpa voluptate sapiente sequi ex adipisci sunt nulla molestiae odio!</p>

        <button type='submit' name="btnStartProcess">
            Iniciar Processo..
        </button>

        <?php 
            Helpers::consoleLog('dentro do call antes');
            sleep(3);
            Helpers::consoleLog('dentro do call depois');
        ?>
    </form>
</body>

</html>