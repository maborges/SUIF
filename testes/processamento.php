<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modal Example...</title>
</head>

<style>
    .ajaxLoading {
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        position: fixed;
        background-color: #000;
        opacity: 0.8;
        z-index: 10;
        display: none;
    }

    .spinner {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 20;
        text-align: center;
    }

    .spinner i {
        font-size: 3.5em;
        color: lime;
    }

    .spinner p {
        color: #fff;
        animation: rotacionar 2s infinite;
    }

    @keyframes rotacionar {

        0%,
        80% {
            transform: rotateY(360deg)
        }
    }
</style>

<!--
<script type="text/javascript" src="processamento.js"></script>
-->

<?PHP
include_once("../includes/config.php");
include_once("../includes/conecta_bd.php");
include_once("../includes/valida_cookies.php");

include_once("../includes/head.php");
?>


</head>


<body>
    <?php
    include("../includes/loading/loading.php");
    ?>

    <div class="flex-container">
        <div>1</div>
        <div>2</div>
        <div>3</div>
    </div>

    <form class="loadingProcess" action="processamento.php" method="post">
        <h1>Janela que faz a chamada</h1>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cupiditate cum corporis quam vel illum assumenda ipsa deserunt, error at quos culpa voluptate sapiente sequi ex adipisci sunt nulla molestiae odio!</p>
        <button type='submit' name="btnStartProcess">
            Chama Call
        </button>
    </form>


    <script src="../includes/js/jquery.min.js"></script>
    <script src="../includes/loading/loading.js"></script>

</body>

</html>