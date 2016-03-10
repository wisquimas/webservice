<?php
require_once('../wp-load.php');
require_once("init.php");
get_header();
?>
<link rel="stylesheet" href="assets/css/style.css?v=<?php echo time();?>">
<link rel="stylesheet" href="assets/css/responsive.css?v=<?php echo time();?>">
<script>
    var home_url = "<?php echo get_home_url();?>";
</script>
<script src="assets/js/script.js"></script>
<?php WW_infoConekta();?>
<div class="page-wrap siclo-ww">
    <div class="ww-logo" style="background-image: url('assets/img/ww_logo.png')"></div>
    <h1 class="color_rojo text-center font30 bolder">Una rodada con causa.</h1>
    <div class="text-center main-text">
        <div>
            Rueda con <span class="color_rojo">Sí</span>clo y <span class="color_rojo">Women's Weekend.</span>
            <br>
            El próximo <span class="color_rojo">sábado 5 de Marzo a las 9:00 pm en Centro Banamex</span> te invitamos al primer
            ride masivo para el apoyo de la fundación <span class="color_rojo">Kardias México</span>
            que apoya a niños con problemas de corazón.
        </div>
        <!--div>
            La clase va por nuestra cuenta y por <span class="color_rojo">cada peso</span>
            que logremos recaudar con tus donativos nosotros ponemos otro.
        </div-->
        <div>
            <span class="color_rojo">Recordemos que juntos somos más.</span>
            <br>
            Reserva y vamos a rodar.
        </div>
    </div>
    <div class="calendario">
        <div class="fila fila-1">
            <?php
                for ($i=82;$i<=88;$i++) {
                    echo '<div class="bici-ww" data-bici="'.$i.'">'.$i.'</div>';
                }
            ?>
        </div>
        <div class="fila fila-2 inline">
            <?php
                for ($i=69;$i<=71;$i++) {
                    echo '<div class="bici-ww" data-bici="'.$i.'">'.$i.'</div>';
                }
            ?>
        </div>
        <div class="fila fila-3 inline">
            <?php
                for ($i=72;$i<=78;$i++) {
                    echo '<div class="bici-ww" data-bici="'.$i.'">'.$i.'</div>';
                }
            ?>
        </div>
        <div class="fila fila-4 inline">
            <?php
                for ($i=79;$i<=81;$i++) {
                    echo '<div class="bici-ww" data-bici="'.$i.'">'.$i.'</div>';
                }
            ?>
        </div>
        <div class="fila fila-5 inline">
            <?php
                for ($i=55;$i<=59;$i++) {
                    echo '<div class="bici-ww" data-bici="'.$i.'">'.$i.'</div>';
                }
            ?>
        </div>
        <div class="fila fila-6 inline">
            <?php
                for ($i=60;$i<=64;$i++) {
                    echo '<div class="bici-ww" data-bici="'.$i.'">'.$i.'</div>';
                }
            ?>
        </div>
        <div class="fila fila-7 inline">
            <?php
                for ($i=65;$i<=68;$i++) {
                    echo '<div class="bici-ww" data-bici="'.$i.'">'.$i.'</div>';
                }
            ?>
        </div>
        <div class="fila fila-8 text-center">
            <?php
                for ($i=37;$i<=54;$i++) {
                    echo '<div class="bici-ww" data-bici="'.$i.'">'.$i.'</div>';
                }
            ?>
        </div>
        <div class="fila fila-9 text-center">
            <?php
                for ($i=19;$i<=36;$i++) {
                    echo '<div class="bici-ww" data-bici="'.$i.'">'.$i.'</div>';
                }
            ?>
        </div>
        <div class="fila fila-10 text-center">
            <?php
                for ($i=1;$i<=18;$i++) {
                    echo '<div class="bici-ww" data-bici="'.$i.'">'.$i.'</div>';
                }
            ?>
        </div>
        <div class="text-center">
            <div class="fila fila-11 text-center inline">
                <?php
                    for ($i=89;$i<=94;$i++) {
                        echo '<div class="bici-ww" data-bici="'.$i.'">'.$i.'</div>';
                    }
                ?>
            </div>
            <div class="fila fila-12 text-center inline">
                <div class="instructor-ww">Jeremy</div>
            </div>
            <div class="fila fila-13 text-center inline">
                <?php
                    for ($i=95;$i<=100;$i++) {
                        echo '<div class="bici-ww" data-bici="'.$i.'">'.$i.'</div>';
                    }
                ?>
            </div>
        </div>
    </div>
    <?php WW_SelectorBicis();?>
</div>
<?php

SistemaCheckout();

get_footer();
