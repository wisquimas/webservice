<?php

function SistemaCheckout(){
    ?>
    <div id="WW--Checkout" class="checkout">
        <?php WW_Loguin();?>
        <?php WW_Pago();?>
    </div>
    <?php
}
function WW_Loguin(){
    if( is_user_logged_in() ){ return; };
    ?>
    <div id="WW--Loguin">
        <?php
            acceso_siclo("callback", false, true, true,'AccesoParaWW');
        ?>
        <div class="datos--clase-ww">
            <div class="linea-datos">
                <div class="color_gris inline font12 titledatos">Reservando en</div><div class="color_rojo blanco inline font12 subdatos">Siclo 147</div>
            </div>
            <div class="linea-datos">
                <div class="color_gris inline font12 titledatos">fecha y hora</div><div class="color_negro blanco inline font12 subdatos">Sábado 5 de Febrero / 9:00 PM</div>
            </div>
            <div class="linea-datos">
                <div class="color_gris inline font12 titledatos">Con</div><div class="color_negro blanco inline font12 subdatos">Jeremy</div>
            </div>
            <div class="linea-datos">
                <div class="color_gris inline font12 titledatos">No. De Bici</div><div class="color_negro blanco inline font12 subdatos numbici-ww">--</div>
            </div>
        </div>
    </div>
    <?php
}
function WW_Pago(){
    $clases = !is_user_logged_in() ? 'escondido' : '';
    $checkout = new Checkout( false, false, get_current_user_id() );
    ?>
    <div id="WW--Pago" class="<?php echo $clases;?>">
        <div id="WW--BiciReserva">
            <div class="WW--BiciReserva--bici">--</div>
            <div class="WW--BiciReserva--lugar">Lugar reservado</div>
            <div id="terminos_condiciones" class="terminos_condiciones"><div class="checkbox"></div><span data-id_fancy="<?php echo TERMINOS; ?>">Acepto términos y condiciones</span></div>
            <div class="WW--BiciReserva--reservarAhora boton">Reservar Ahora</div>
        </div>
        <div id="WW--Tarjetero" class="columna_general">
            <div id="WW--Tarjetero--tarjetas" class="oculto">
                <?php
                if( is_user_logged_in() ){
                    echo $checkout->loop_formularios_pago( true );
                };
                ?>
            </div>
            <div id="WW--Pagar" class="boton escondido">Pagar Ahora</div>
        </div>
        <div id="WW--mensaje" class="escondido text-center">
            <div class="font12 color_rojo">Tu bici es la número <span class="numbici"></span></div>
            <div class="font12 color_negro">
                Gracias por rodar con nosotros.
                <br>
                ¡Nos vemos el sábado!
            </div>
            <div class="font20 color_negro">Recordemos que juntos somos más.</div>
        </div>
    </div>
    <?php
}
/*
** IMPRIME EL SELECTOR DEL MOVIL
*/
function WW_SelectorBicis(){
    $htmlOpciones = '';
    for( $i = 1;$i<=100;$i++ ){
        $htmlOpciones.= '<option data-bici="'.$i.'" value="'.$i.'">'.$i.'</option>';
    };
    echo
    '<select id="selectorBicis"class="escondido color_negro">'.
        '<option>No. de bici</option>'.
        $htmlOpciones.
    '</select>';
}
/*
** IMPRIME DIV PARA TENER ID DE CONEKTA
*/
function WW_infoConekta(){
    $pago = new Pago_Conekta();
    echo '<div id="WW--Conekta" data-infoconekta="'.$pago->publickey.'"></div>';
}
