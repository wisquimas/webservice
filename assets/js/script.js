var AccesoParaWW = function( data ){
    ProcesarDataWW(data);
    $('#WW--Loguin').hide(0);
    $('#WW--Pago').show(0);
}

$(document).ready(function(){
    /*
    ** LOGO
    */
    var logo = $('.siclo-ww').closest('body').find('nav .logo');
    logo.attr('src','assets/img/logo.png');
    /*
    ** CLICK BICI
    */
    $('.bici-ww').on('click',function(e,noScroll){
        var este = $(this);
        if( este.is('.reservaPorOtroUsuario') ){ return; };
        var numero = este.data('bici');
        WW_SeleccionarBici( numero,noScroll );
    });
    /*
    ** SELECTOR BICI
    */
    $('#selectorBicis').on('change',function(){
        WW_SeleccionarBici( $(this).val() );
    });
    $('.calendario').on('click',function(){
        if( $(window).outerWidth() <= 799 ){
            alert('Utiliza el selector para seleccionar la bicicleta que deseas');
            $('body,html').stop().animate({
                scrollTop : $('#selectorBicis').offset().top - $('.navbar').outerHeight()
            },1000)
        };
    });
    function WW_SeleccionarBici( numero,noScroll ){
        var este = $('.bici-ww[data-bici="'+numero+'"]');
        /*
        ** MARCAR MAPA
        */
        $('.ww-biciSelect').removeClass('ww-biciSelect');
        este.addClass('ww-biciSelect');
        /*
        ** MARCAR SELECTOR
        */
        $('#selectorBicis').val(numero);
        /*
        ** TEXTO
        */
        $('.numbici-ww,.WW--BiciReserva--bici').text(numero);
        /*
        ** SCROLL
        */
        if( !noScroll ){
            $('body,html').stop().animate({
                scrollTop : $('#WW--Checkout').offset().top - $('.navbar').outerHeight()
            },1000)
        };
    }
    /*
    ** CLICK PAGO
    */
    $('.WW--BiciReserva--reservarAhora').on('click',function(){
        $('#WW--Pagar').trigger('click');
    });
    $('#WW--Pagar').on('click',function(){
        var este	= $(this);
    	var checkout= este.closest('.checkout');
        /*
        ** TERMINOS Y CONDICIONES
        */
        if( !checkout.find('#terminos_condiciones .checked').length ){
    		checkout.find('#terminos_condiciones').css('border-color','red');
    		alert('Debes aceptar los términos y condiciones para poder continuar');
    		return;
    	}else{
    		checkout.find('#terminos_condiciones').removeAttr('style');
    	};
        if( !$('.ww-biciSelect').length ){
            alert('Debes Seleccionar una bici para continuar.');
    		return;
        };
        /*
        ** BICI SELECCIONADO
        */
        //WW_get_token( checkout );
        WW_Reservar();
    });
    /*
    ** FUNCION INTERMEDIA DE COMPRA
    */
    function WW_Reservar(){
        var biciSelec = $('.ww-biciSelect').data('bici');
        save_data('funcion=WW_ReservarBici&attr='+biciSelec);
    }
    /*
    ** CREAMOS TOKEN CONEKTA
    */
    function WW_get_token( checkout ){
        Conekta.setPublishableKey( $('#WW--Conekta').data('infoconekta') );

        var token       = false;
        var invitado    = false;
        var cliente     = false;

        if( checkout.find('.tarjetas_user .checked').length ){
    		/*EN ESTOS CASOS ES PORQUE EL USUARIO ELIGIÓ UNA TARJETA*/
    		cargando();
    		token = checkout.find('.tarjetas_user .checked').closest('[data-token]').data('token');
    		WW_pagar( token, invitado, checkout, cliente, 'conekta' );
    		return;
    	}else{
    		/*USUARIO NO ELIGIÓ TARJETA*/
    		if( checkout.find('.data_tarjeta').is('.escondido') ){
    			/*DATOS DE LA TARJETA ESCONDIDOS*/
    			alert('Puedes crear una nueva tarjeta en tu cuenta si lo deseas','mensaje');
    			checkout.find('.annadir_tarjeta').trigger('click');
    			borrarCargando();
    			return;
    		};
    		if( !check_formularios( checkout.find('.data_tarjeta') ) ){
    			borrarCargando();
    			return;
    		};
    	};

    	cargando();
    	Conekta.token.create( checkout.find('.data_tarjeta') , function(d){
    		/*OK*/
    		token = d.id;
    		procesar_compra_ya( token, invitado, checkout, cliente,'conekta' );
    	}, function(d){
    		/*FALSE*/
    		alert( d.message_to_purchaser );
    		borrarCargando();
    	});
    };
    /*
    ** REALIZAR PAGO
    */
    function WW_pagar( token, invitado, checkout, cliente, metodo_pago ){
    	if( ajax_GF ){
    		return;
    	};
    	var data = {
    		security			: true,
    		data_facturacion	: [],
    		paquete				: checkout.find('.paquete.seleccionado').data('idpaquete'),
    		token				: token,
    		cliente				: cliente,
    		metodo_pago			: metodo_pago,
    	};
    	if( invitado ){
    		data.invitado = invitado;
    	};
    	if( checkout.find('.guardar_pregunta_tarj .checkbox.checked').length ){
    		data.guardar_tarjeta = 1;
    	};
    	if( checkout.find('#gastar_referencia.gastando_referencia').length ){
    		data.gastar_referencia = 1;
    	};
    	if( checkout.find('.finalizar_compra_ya').is('.add_to_waitlist') ){
    		data.waitlist = {
    			'clase'		: $('.clase_salon [data-id_mapa]').data('id_mapa'),
    			'waitlist'	: 'ok',
    		};
    	}else{
    		if( checkout.closest('#ajax_reservacion').length ){
    			var mapa		= checkout.closest('#ajax_reservacion').find('.clase_salon[data-id_mapa]');
    			var bicicleta	= mapa.find('.bicicleta.bici_select').first();
    			if( !bicicleta.length ){
    				alert('Selecciona una bicicleta para poder realizar la reservación');
    				borrarCargando();
    				return;
    			};
    			data.reserva	= {
    				clase	: mapa.data('id_mapa'),
    				bici	: [ bicicleta.data('x'), bicicleta.data('y') ],
    			};
    		};
    	};

    	/*GUARDAMOS INFO DE FACTURACION*/
    	checkout.find('.datos_usuario_facturacion input').each(function(i, e) {
    		data.data_facturacion.push( [ $(e).attr('id') , $(e).val() ] );
    	});
    	/*
    	**Compatibilidad para comprar producos en back
    	**sin necesidad de comprar paquetes ;)
    	*/
    	if( cliente ){
    		/*sabemos que estamos en back con esta variable*/
    		data.productos	= get_productos_comprados( true );
    		var tarjeta = checkout.find('#id_activacion_tarjeta');
    		if( tarjeta.length ){
    			if( check_formularios( $('#giftCard--form') ) ){
    				data.tarjeta_regalo	= tarjeta.val();
    			}else{
    				alert('Tienes que colocar el ID de la tarjeta de forma obligatoria para poder activarla');
    				borrarCargando();
    				return;
    			};
    		};
    	};
    	ajax_GF = true;
    	$.post('<?php plantilla();?>/procesos/checkout/finalizar_compra.php',data).done(function(d){
    		ajax_GF = false;
    		var todo_data = code = d;
    		var html	= todo_data.split("<!--DATA_AJAXEND-->")[1];

    		if ( code.indexOf("<!--DATA_AJAX-->") >= 0 ){
    			code = code.split("<!--DATA_AJAX-->")[1];
    		}
    		if ( code.indexOf("<!--DATA_AJAXEND-->") >= 0 ){
    			code = code.split("<!--DATA_AJAXEND-->")[0];
    		};
    		var data = JSON.parse( html );
    		if( !data.ok ){
    			alert( data.mensaje );
    			borrarCargando();
    			return;
    		};

    		/*
    		**ACÁ ACABA EL FLUJO DEL BACK
    		*/
    		if( cliente ){
    			alert('La compra se ha realizado correctamente.');
    			$('[data-id="estudios"]').trigger('click');
    			return;
    		};

    		$('.volver_a_paquetes').trigger('click');
    		if( $('#ajax_reservacion .back').length ){
    			$('#ajax_reservacion .back').trigger('click');
    		};
    		$('[data-idpaquete="regalo"]').remove();
    		crear_fancy( code );
    	});
    }
    /*
    **AJAX
    */
    function save_data( data, proceso, callback, callback_attr ){
		/*ENVIO DE INFO, HACER PROCESO O CALLBACK*/
		cargando();
		/*SET AJAX: SINO NO FUNCIONAN LAS DEL ADMIN*/
		ajax_GF = $.get( document.location.href+'/procesos/?'+data ).done(function(d){
            var info = d;
			if( !info || !info.ok ){
				alert( info.mensaje );
				return;
			}else{
                if( callback ){
                    callback( info );
                }else{
                    if( info.data ){
                        document.location.href=home_url+"/?gracias=1";
                    };
                };
			};
		}).always(function(){
			borrarCargando();
		});
	};
    /*
    ** ESTA FUNCIÓN CONTROLA LA INFORMACIÓN DEL USUARIO EN EL SISTEMA
    */
    function ComprobandoCapadaWeb( data ){
        if( data && data.data.length ){
            $(data.data).each(function(i,e){
                /*
                **DATA
                */
                var comprador = e.IdUsuario;
                $(e.Reservas).each(function(ii,ee){
                    /*
                    ** SELECCIONAMOS Y QUITAMOS CLICK
                    */
                    var bici = $('.bici-ww[data-bici="'+ee.IdBici+'"]');
                    bici.addClass('reservaPorOtroUsuario');
                    bici.data('comprador',ee.IdUsuario);
                    /*
                    ** ELIMINAMOS DE SELECTOR
                    */
                    $('#selectorBicis option[data-bici="'+ee.IdBici+'"]').remove();
                    if( ee.IdUsuario == comprador ){
                        /*
                        ** SI EL USUARIO YA COMPRO LO MANDAMOS A VOLAR
                        */
                        $('.numbici').text( ee.IdBici );
                        $('#WW--BiciReserva,#selectorBicis,#WW--Tarjetero').remove();
                        $('#WW--mensaje').show(0);
                        $('.calendario').addClass('ww_yaCompro');
                    };
                });
            });
        };
    }
    /*
    ** INICIO
    */
    WW_Inicio();
    function WW_Inicio(){
        save_data('funcion=WW_GetBicisSeleccionadas&attr=1',false,ComprobandoCapadaWeb);
    }
});
/*

/*
** FUNCION AJAX DEL LOGUEO
*/
function ProcesarDataWW( code ){
    code = $(code);

	if( $('.hover_cuenta_ajax .data_hover').length ){
		$('.hover_cuenta_ajax .data_hover').remove();
	};
	$('.hover_cuenta_ajax').data('hoverinfo', code.find('#textito_hover').html() );

	/*ACCIONES MENU*/
	$('.navbar').html( code.find('#nuevo_menu .navbar').html() );
	if( code.find('#nuevo_menu .navbar').is('.con_citillo') ){
		$('.navbar').addClass('con_citillo');
	};


    return;//TODO Habilitar luego
    /*TARJETERO*/
    var tarjetero = code.find('.form_final_compra').first();
    tarjetero.find('.bloque_descuento_check, #terminos_condiciones,.fijo_abajo,.tarjetas_user>.text-center').remove();
    $('#WW--Tarjetero').html( tarjetero.html() );
}
