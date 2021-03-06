<?php

$app->post('/administrador/listarFacturaUsuario', function () use ($app) {
    $conexion = new conexPGSeguridad();
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $id_usuario = $app->request->post('id_usuario', null);
        $sql = "select fac.*,rf.descripcion_ramal_factura from facturacion.factura fac 
                left join  configuracion.ramal_factura rf on 
                fac.fk_ramal_factura_factura_id=rf.id_ramal_factura 
                where fac.id_usuario_factura_fk = '$id_usuario';";
        $r = $conexion->consultaComplejaAso($sql);
        $data = [
            'code' => 'LTE-001',
            'data' => $r
        ];
    } else {
        $data = [
            'code' => 'LTE-013'
        ];

    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/usuario/nuevaFactura', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $json = $app->request->post('json', null);
            $parametros = json_decode($json);
            $observacion_factura = (isset($parametros->observacion_factura)) ? $parametros->observacion_factura : null;
            $codigo_medidor_factura = (isset($parametros->codigo_medidor_factura)) ? $parametros->codigo_medidor_factura : null;
            $direccion_factura = (isset($parametros->direccion_factura)) ? $parametros->direccion_factura : null;
            $numero_tapa_factura = (isset($parametros->numero_tapa_factura)) ? $parametros->numero_tapa_factura : null;
            $id_usuario_factura_fk = (isset($parametros->id_usuario_factura_fk)) ? $parametros->id_usuario_factura_fk : null;
            $estado_factura = (isset($parametros->estado_factura)) ? $parametros->estado_factura : null;
            $fk_ramal_factura_factura_id = (isset($parametros->fk_ramal_factura_factura_id)) ? $parametros->fk_ramal_factura_factura_id : null;
            $sql = "select * from configuracion.configuracion_factura";
            $r = $conexon->consultaComplejaNorAso($sql);
            $id_configuracion_factura = $r['id_configuracion_factura'];
            $sql = "INSERT INTO facturacion.factura(
                    observacion_factura, codigo_medidor_factura, direccion_factura, 
                    numero_tapa_factura, id_usuario_factura_fk, id_configuracion_factura_fk, estado_factura,fk_ramal_factura_factura_id)
                    VALUES ( '$observacion_factura', '$codigo_medidor_factura',
                     '$direccion_factura', '$numero_tapa_factura', '$id_usuario_factura_fk', '$id_configuracion_factura', '$estado_factura','$fk_ramal_factura_factura_id');";
            $conexon->consultaSimple($sql);
            $data = [
                'code' => 'LTE-001'
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/usuario/actualizarFactura', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $json = $app->request->post('json', null);
            $parametros = json_decode($json);
            $id_factura = (isset($parametros->id_factura)) ? $parametros->id_factura : null;
            $observacion_factura = (isset($parametros->observacion_factura)) ? $parametros->observacion_factura : null;
            $codigo_medidor_factura = (isset($parametros->codigo_medidor_factura)) ? $parametros->codigo_medidor_factura : null;
            $direccion_factura = (isset($parametros->direccion_factura)) ? $parametros->direccion_factura : null;
            $numero_tapa_factura = (isset($parametros->numero_tapa_factura)) ? $parametros->numero_tapa_factura : null;
            $id_usuario_factura_fk = (isset($parametros->id_usuario_factura_fk)) ? $parametros->id_usuario_factura_fk : null;
            $estado_factura = (isset($parametros->estado_factura)) ? $parametros->estado_factura : null;
            $fk_ramal_factura_factura_id = (isset($parametros->fk_ramal_factura_factura_id)) ? $parametros->fk_ramal_factura_factura_id : null;
            $sql = "UPDATE facturacion.factura
                    SET observacion_factura='$observacion_factura', codigo_medidor_factura='$codigo_medidor_factura', direccion_factura='$direccion_factura', 
                    fk_ramal_factura_factura_id='$fk_ramal_factura_factura_id',
                    numero_tapa_factura='$numero_tapa_factura', estado_factura='$estado_factura'
                    WHERE id_factura='$id_factura';";
            $conexon->consultaSimple($sql);
            $data = [
                'code' => 'LTE-001'
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/factura/listarTarifa', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $id_factura = $app->request->post('id', null);
            $sql = "select tf.* from configuracion.tarifas tf where tf.id_factura_tarifa_fk = '$id_factura'";
            $r = $conexon->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/factura/crearTarifa', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $json = $app->request->post('json', null);
            $parametros = json_decode($json);
            $descripcion_tarifa = (isset($parametros->descripcion_tarifa)) ? $parametros->descripcion_tarifa : null;
            $total_tarifa = (isset($parametros->total_tarifa)) ? $parametros->total_tarifa : null;
            $estado_tarifa = (isset($parametros->estado_tarifa)) ? $parametros->estado_tarifa : null;
            $id_factura_tarifa_fk = (isset($parametros->id_factura_tarifa_fk)) ? $parametros->id_factura_tarifa_fk : null;
            $sql = "INSERT INTO configuracion.tarifas(
                               descripcion_tarifa, total_tarifa, estado_tarifa, id_factura_tarifa_fk)
                                VALUES ('$descripcion_tarifa', '$total_tarifa', '$estado_tarifa', '$id_factura_tarifa_fk');";
            $conexon->consultaSimple($sql);
            $data = [
                'code' => 'LTE-001'
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/factura/actualizarTarifa', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $json = $app->request->post('json', null);
            $parametros = json_decode($json);
            $id_tarifa = (isset($parametros->id_tarifa)) ? $parametros->id_tarifa : null;
            $descripcion_tarifa = (isset($parametros->descripcion_tarifa)) ? $parametros->descripcion_tarifa : null;
            $total_tarifa = (isset($parametros->total_tarifa)) ? $parametros->total_tarifa : null;
            $estado_tarifa = (isset($parametros->estado_tarifa)) ? $parametros->estado_tarifa : null;
            $id_factura_tarifa_fk = (isset($parametros->id_factura_tarifa_fk)) ? $parametros->id_factura_tarifa_fk : null;
            $sql = "UPDATE configuracion.tarifas
                        SET descripcion_tarifa='$descripcion_tarifa', total_tarifa='$total_tarifa', estado_tarifa='$estado_tarifa'
                        WHERE id_tarifa='$id_tarifa';";
            $conexon->consultaSimple($sql);
            $data = [
                'code' => 'LTE-001'
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/factura/generarFactura', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    $fecha_factura = $app->request->post('fecha', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $sql = "select usu.* from seguridad.usuario usu where usu.estado_usuario = 'ACTIVO'";
            $usuario = $conexon->consultaComplejaAso($sql);
            $arregloFacturas = array();
            for ($i = 0; $i < count($usuario); $i++) {
                $id_usuario = $usuario[$i]['id_usuario'];
                $sql = "select fac.* from facturacion.factura fac where fac.id_usuario_factura_fk = '$id_usuario' and fac.estado_factura ='ACTIVO'";
                $rFactura = $conexon->consultaComplejaAso($sql);
                $factura = array();
                if ($rFactura != 0) {
                    for ($n = 0; $n < count($rFactura); $n++) {
                        $id_factura = $rFactura[$n]['id_factura'];
                        $arregloTarifas = array();
                        $sql = "select tf.* from configuracion.tarifas tf where tf.id_factura_tarifa_fk ='$id_factura'";
                        $rTarifas = $conexon->consultaComplejaAso($sql);
                        if ($rTarifas != 0) {
                            $totalTria = 0;
                            for ($y = 0; $y < count($rTarifas); $y++) {
                                if ($rTarifas[$y]['estado_tarifa'] == 'ACTIVO') {
                                    $totalTria += $rTarifas[$y]['total_tarifa'];
                                    array_push($arregloTarifas, $rTarifas[$y]);
                                }

                            }
                            $tarifasArray = [
                                'totalTarifa' => $totalTria,
                                'codigo_medidor_factura' => $rFactura[$n]['codigo_medidor_factura'],
                                'numero_tapa_factura' => $rFactura[$n]['numero_tapa_factura'],
                                'observacion_factura' => $rFactura[$n]['observacion_factura'],
                                'tarifas' => $arregloTarifas
                            ];
                        }
                        $fechFac = explode('-', $fecha_factura);
                        $sql = "select * from facturacion.registro_factura rf where rf.id_factura_registro_factura_fk = '$id_factura' 
                                and to_char(rf.fecha_inicial_facturado,'YYYY-MM') like '$fechFac[0]-$fechFac[1]'";
                        $r = $conexon->consultaComplejaAso($sql);
                        if ($usuario[$i]['estado_usuario'] != 'RETIRADO' && $rFactura[$n]['estado_factura'] != 'CANCELADO' && $r == 0) {
                            $fecha_inicial = date('Y-m-d H:i', strtotime($fecha_factura));
                            $fecha_creacion = date('Y-m-d H:i');
                            $fechaCorte = strtotime('+51 day', strtotime($fecha_inicial));
                            $fechaCorte = date('Y-m-j', $fechaCorte);

                            $fechaPagar = strtotime('+30 day', strtotime($fecha_inicial));
                            $fechaPagar = date('Y-m-j', $fechaPagar);

                            $jsonTarifa = json_encode($tarifasArray);
                            $sql = "INSERT INTO facturacion.registro_factura(id_usuario_registro_factura_fk,
                             estado_factura, fecha_creacion_factura, fecha_pago_factura, fecha_inicial_facturado, id_factura_registro_factura_fk, 
                             fecha_final_factura, json_tarifas)
                            VALUES ('$id_usuario','SINPAGAR', '$fecha_creacion', '$fechaPagar', '$fecha_factura', '$id_factura', '$fechaCorte', '$jsonTarifa') returning id_registro_factura;";
                            $r = $conexon->consultaComplejaNorAso($sql);
                            $codigoFactura = 'F-' . $fechFac[0] . $fechFac[1] . $usuario[$i]['id_usuario'] . $r['id_registro_factura'];
                            $id_registro_factura = $r['id_registro_factura'];
                            $sql = "update facturacion.registro_factura set codigo_registro_factura='$codigoFactura' where id_registro_factura = '$id_registro_factura'";
                            $conexon->consultaSimple($sql);

                        }
                    }
                }

            }
            $data = [
                'code' => 'LTE-001'
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/factura/generarReporteFacturaTodos', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    $fecha_factura = $app->request->post('fecha', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $usuario_id = $app->request->post('id_usuario', null);
            if ($usuario_id == '' || $usuario_id == null) {
                $sql = "select * from seguridad.usuario";
            } else {
                $sql = "select * from seguridad.usuario where id_usuario = '$usuario_id'";
            }

            $usuario = $conexon->consultaComplejaAso($sql);
            $arregloFactura = array();
            $validaEntrada = 1;
            $ramal = $app->request->post('id', null);
            for ($i = 0; $i < count($usuario); $i++) {
                $idUsuario = $usuario[$i]['id_usuario'];
                $sql = "select * from facturacion.factura fa where fa.id_usuario_factura_fk = '$idUsuario'";
                $rFactura = $conexon->consultaComplejaAso($sql);
                if ($rFactura != 0) {
                    for ($y = 0; $y < count($rFactura); $y++) {
                        $idFactura = $rFactura[$y]['id_factura'];
                        if ($ramal == 'todo' || $ramal == '' || $ramal == null) {
                            $sql = "select * from facturacion.registro_factura rf 
                                    inner join facturacion.factura fa on rf.id_factura_registro_factura_fk = fa.id_factura 
                                    inner join seguridad.usuario usu on fa.id_usuario_factura_fk = usu.id_usuario
                                    left join configuracion.ramal_factura rafa on rafa.id_ramal_factura=fa.fk_ramal_factura_factura_id 
                                    where  rf.id_usuario_registro_factura_fk = '$idUsuario' and fa.id_factura ='$idFactura' and rf.estado_factura ='SINPAGAR'
                                    ORDER BY  rf.fecha_inicial_facturado desc";
                        } else {
                            $sql = "select * from facturacion.registro_factura rf 
                                    inner join facturacion.factura fa on rf.id_factura_registro_factura_fk = fa.id_factura 
                                    inner join seguridad.usuario usu on fa.id_usuario_factura_fk = usu.id_usuario
                                    left join configuracion.ramal_factura rafa on rafa.id_ramal_factura=fa.fk_ramal_factura_factura_id 
                                    where  rafa.id_ramal_factura='$ramal' and rf.id_usuario_registro_factura_fk = '$idUsuario' and fa.id_factura ='$idFactura' and rf.estado_factura ='SINPAGAR'
                                    ORDER BY  rf.fecha_inicial_facturado desc";
                        }
                        $registros = $conexon->consultaComplejaAso($sql);
                        if ($registros != 0) {
                            $validaEntrada = 0;
                            $totalFactura = 0;
                            $arregloDetalle = array();
                            $totalFacturasCobradas = 0;
                            for ($n = 0; $n < count($registros); $n++) {
                                $arregloTarifas = json_decode($registros[$n]['json_tarifas'], true);

                                $totalFactura += $arregloTarifas['totalTarifa'];
                                array_push($arregloDetalle, ['codigoFactura' => $registros[$n]['codigo_registro_factura'],
                                    'detalle' => $arregloTarifas]);
                                $totalFacturasCobradas++;
                                if ($n > 0) {
                                    $codigo = $registros[0]['codigo_registro_factura'];
                                    $registroActualizar = $registros[$n]['codigo_registro_factura'];
                                    $sql = "update facturacion.registro_factura set factura_principal='$codigo' 
                                    where codigo_registro_factura = '$registroActualizar'";
                                    $conexon->consultaSimple($sql);
                                }
                            }
                            $id_registro = $registros[0]['id_registro_factura'];
                            $json = json_encode($arregloDetalle);
                            $sql = "update facturacion.registro_factura set json_cargue_factura='$json' where id_registro_factura = '$id_registro'";
                            $conexon->consultaSimple($sql);
                            // $fecha_creacion = date('Y-m-d',$registros[0]['fecha_inicial_facturado']);
                            array_push($arregloFactura, [
                                'documento' => $registros[0]['documento_usuario'],
                                'nombre' => $registros[0]['nombre_usuario'],
                                'apellido' => $registros[0]['apellido_usuario'],
                                'telefono' => $registros[0]['telefono_usuario'],
                                'direccion' => $registros[0]['direccion_usuario'],
                                'fecha_inicial' => date('Y-m-d', strtotime($registros[0]['fecha_inicial_facturado'])),
                                'fecha_pago' => date('Y-m-d', strtotime($registros[0]['fecha_pago_factura'])),
                                'fecha_corte' => date('Y-m-d', strtotime($registros[0]['fecha_final_factura'])),
                                'ramal' => $registros[0]['descripcion_ramal_factura'],
                                'direccion_entrega' => $registros[0]['direccion_factura'],
                                'codigo_medidor' => $registros[0]['codigo_medidor_factura'],
                                'codigo_registro_factura' => $registros[0]['codigo_registro_factura'],
                                'numero_tapa_factura' => $registros[0]['numero_tapa_factura'],
                                'total_pagar_factura' => $totalFactura,
                                'total_facturas_cobradas' => $totalFacturasCobradas,
                                'detalles' => $arregloDetalle
                            ]);
                            $rutaReporte = generarReportePDF($arregloFactura);
                        }
                    }

                }

            }

            if ($validaEntrada == 0) {
                $data = [
                    'code' => 'LTE-001',
                    'data' => $rutaReporte
                ];
            } else {
                $data = [
                    'code' => 'LTE-000',
                    'status' => 'error',
                    'msg' => 'Lo sentimos, no hay facturas cargadas'
                ];
            }

        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/factura/cobrarFactura', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $json = $app->request->post('json', null);
            $parametros = json_decode($json);
            $codigo_registro_factura = (isset($parametros->codigo_registro_factura)) ? $parametros->codigo_registro_factura : null;
            $validacionFacturaPadre = false;
            $entradaRecargo = false;
            $codigoIngresado = $codigo_registro_factura;
            while ($validacionFacturaPadre == false) {
                $sql = "select *, rf.estado_factura as estado_factura_registro from facturacion.registro_factura rf 
                    inner join facturacion.factura f on rf.id_factura_registro_factura_fk = f.id_factura
                    inner join seguridad.usuario usu on f.id_usuario_factura_fk=usu.id_usuario 
                    where rf.codigo_registro_factura ='$codigo_registro_factura'";
                $r = $conexon->consultaComplejaNorAso($sql);
                if ($r['factura_principal'] != '' || $r['factura_principal'] != null) {
                    $codigo_registro_factura = $r['factura_principal'];
                    $entradaRecargo = true;
                } else {
                    $validacionFacturaPadre = true;
                }
            }
            $totalPagar = 0;
            if ($r['estado_factura_registro'] == 'SINPAGAR') {

                $arregloFacturasPagar = json_decode($r['json_cargue_factura'], true);

                for ($i = 0; $i < count($arregloFacturasPagar); $i++) {
                    $codigoFactura = $arregloFacturasPagar[$i]['codigoFactura'];
                    $totalPagar += $arregloFacturasPagar[$i]['detalle']['totalTarifa'];
                    $sql = "UPDATE facturacion.registro_factura
                            SET  estado_factura='PAGADO'
                            WHERE codigo_registro_factura='$codigoFactura';";
                    $conexon->consultaSimple($sql);
                }
                $sql = "select *, rf.estado_factura as estado_factura_registro from facturacion.registro_factura rf 
                    inner join facturacion.factura f on rf.id_factura_registro_factura_fk = f.id_factura
                    inner join seguridad.usuario usu on f.id_usuario_factura_fk=usu.id_usuario 
                    where rf.codigo_registro_factura ='$codigo_registro_factura'";
                $r = $conexon->consultaComplejaNorAso($sql);
                $data = [
                    'code' => 'LTE-001',
                    'data' => [
                        'totalPagar' => $totalPagar,
                        'nombre' => $r['nombre_usuario'],
                        'apellido' => $r['apellido_usuario'],
                        'documento_usuario' => $r['documento_usuario'],
                        'estado_factura_registro' => $r['estado_factura_registro'],
                        'observacion_factura' => $r['observacion_factura'],
                        'json_cargue_factura' => json_decode($r['json_cargue_factura']),
                        'numero_tapa_factura' => $r['numero_tapa_factura'],
                        'estado_usuario' => $r['estado_usuario'],
                        'codigo_registro_factura' => $r['codigo_registro_factura'],
                        'codigo_medidor_factura' => $r['codigo_medidor_factura'],
                        'entrtrada_recargo' => $entradaRecargo,
                        'msg' => 'La factura concodigo: ' . $codigoIngresado . ' esta como recargo en la factura con codigo: ' . $codigo_registro_factura
                    ]
                ];
            } else {
                $data = [
                    'code' => 'LTE-000',
                    'status' => 'error',
                    'msg' => 'Lo sentimos, esta factura se encuentra en estado PAGADO',
                    'data' => $r
                ];
            }

        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/factura/cargarFacturaCobrar', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $json = $app->request->post('json', null);
            $parametros = json_decode($json);
            $codigo_registro_factura = (isset($parametros->codigo_registro_factura)) ? $parametros->codigo_registro_factura : null;
            $validacionFacturaPadre = false;
            $entradaRecargo = false;
            $codigoIngresado = $codigo_registro_factura;
            while ($validacionFacturaPadre == false) {
                $sql = "select *, rf.estado_factura as estado_factura_registro from facturacion.registro_factura rf 
                    inner join facturacion.factura f on rf.id_factura_registro_factura_fk = f.id_factura
                    inner join seguridad.usuario usu on f.id_usuario_factura_fk=usu.id_usuario 
                    where rf.codigo_registro_factura ='$codigo_registro_factura'";
                $r = $conexon->consultaComplejaNorAso($sql);
                if ($r['factura_principal'] != '' || $r['factura_principal'] != null) {
                    $codigo_registro_factura = $r['factura_principal'];
                    $entradaRecargo = true;
                } else {
                    $validacionFacturaPadre = true;
                }
            }
            if ($r != 0) {
                $totalPagar = 0;
                $arregloFacturasPagar = json_decode($r['json_cargue_factura'], true);
                for ($i = 0; $i < count($arregloFacturasPagar); $i++) {
                    $totalPagar += $arregloFacturasPagar[$i]['detalle']['totalTarifa'];
                }
                $data = [
                    'code' => 'LTE-001',
                    'data' => [
                        'totalPagar' => number_format($totalPagar, 0),
                        'nombre' => $r['nombre_usuario'],
                        'apellido' => $r['apellido_usuario'],
                        'documento_usuario' => $r['documento_usuario'],
                        'estado_factura_registro' => $r['estado_factura_registro'],
                        'observacion_factura' => $r['observacion_factura'],
                        'json_cargue_factura' => json_decode($r['json_cargue_factura']),
                        'numero_tapa_factura' => $r['numero_tapa_factura'],
                        'estado_usuario' => $r['estado_usuario'],
                        'codigo_registro_factura' => $r['codigo_registro_factura'],
                        'codigo_medidor_factura' => $r['codigo_medidor_factura'],
                        'fecha_creacion_factura' => date('Y-m-d', strtotime($r['fecha_creacion_factura'])),
                        'fecha_final_factura' => date('Y-m-d', strtotime($r['fecha_final_factura'])),
                        'fecha_inicial_facturado' => date('Y-m-d', strtotime($r['fecha_inicial_facturado'])),
                        'fecha_pago_factura' => date('Y-m-d', strtotime($r['fecha_pago_factura'])),
                        'observacion_factura' => $r['observacion_factura'],
                        'entrtrada_recargo' => $entradaRecargo,
                        'msg' => 'La factura con codigo: ' . $codigoIngresado . ' esta como recargo en la factura con codigo: ' . $codigo_registro_factura
                    ]
                ];
            } else {
                $data = [
                    'code' => 'LTE-000',
                    'status' => 'error',
                    'msg' => 'Lo sentimos, no se encontraron resultados'
                ];
            }

        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/factura/generarFacturaUsuarioUnitario', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    $fecha_factura = $app->request->post('fecha', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $id_usuario = $app->request->post('id', null);
            $sql = "select usu.* from seguridad.usuario usu where usu.id_usuario = '$id_usuario'";
            $usuario = $conexon->consultaComplejaNorAso($sql);
            $id_usuario = $usuario['id_usuario'];
            $arregloFacturas = array();
            $sql = "select fac.* from facturacion.factura fac where fac.id_usuario_factura_fk = '$id_usuario' and fac.estado_factura ='ACTIVO'";
            $rFactura = $conexon->consultaComplejaAso($sql);
            $factura = array();
            if ($rFactura != 0) {
                for ($n = 0; $n < count($rFactura); $n++) {
                    $id_factura = $rFactura[$n]['id_factura'];
                    $arregloTarifas = array();
                    $sql = "select tf.* from configuracion.tarifas tf where tf.id_factura_tarifa_fk ='$id_factura'";
                    $rTarifas = $conexon->consultaComplejaAso($sql);
                    if ($rTarifas != 0) {
                        $totalTria = 0;
                        for ($y = 0; $y < count($rTarifas); $y++) {
                            if ($rTarifas[$y]['estado_tarifa'] == 'ACTIVO') {
                                $totalTria += $rTarifas[$y]['total_tarifa'];
                                array_push($arregloTarifas, $rTarifas[$y]);
                            }

                        }
                        $tarifasArray = [
                            'totalTarifa' => $totalTria,
                            'codigo_medidor_factura' => $rFactura[$n]['codigo_medidor_factura'],
                            'numero_tapa_factura' => $rFactura[$n]['numero_tapa_factura'],
                            'observacion_factura' => $rFactura[$n]['observacion_factura'],
                            'tarifas' => $arregloTarifas
                        ];
                    }
                    $fechFac = explode('-', $fecha_factura);
                    $sql = "select * from facturacion.registro_factura rf where rf.id_factura_registro_factura_fk = '$id_factura' 
                                and to_char(rf.fecha_inicial_facturado,'YYYY-MM') like '$fechFac[0]-$fechFac[1]'";
                    $r = $conexon->consultaComplejaAso($sql);
                    if ($usuario['estado_usuario'] != 'RETIRADO' && $rFactura[$n]['estado_factura'] != 'CANCELADO' && $r == 0) {
                        $fecha_inicial = date('Y-m-d H:i', strtotime($fecha_factura));
                        $fecha_creacion = date('Y-m-d H:i');
                        $fechaCorte = strtotime('+51 day', strtotime($fecha_inicial));
                        $fechaCorte = date('Y-m-j', $fechaCorte);

                        $fechaPagar = strtotime('+30 day', strtotime($fecha_inicial));
                        $fechaPagar = date('Y-m-j', $fechaPagar);

                        $jsonTarifa = json_encode($tarifasArray);
                        $sql = "INSERT INTO facturacion.registro_factura(id_usuario_registro_factura_fk,
                             estado_factura, fecha_creacion_factura, fecha_pago_factura, fecha_inicial_facturado, id_factura_registro_factura_fk, 
                             fecha_final_factura, json_tarifas)
                            VALUES ('$id_usuario','SINPAGAR', '$fecha_creacion', '$fechaPagar', '$fecha_factura', '$id_factura', '$fechaCorte', '$jsonTarifa') returning id_registro_factura;";
                        $r = $conexon->consultaComplejaNorAso($sql);
                        $codigoFactura = 'F-' . $fechFac[0] . $fechFac[1] . $usuario['id_usuario'] . $r['id_registro_factura'];
                        $id_registro_factura = $r['id_registro_factura'];
                        $sql = "update facturacion.registro_factura set codigo_registro_factura='$codigoFactura' where id_registro_factura = '$id_registro_factura'";
                        $conexon->consultaSimple($sql);

                    }
                }
            }


            $data = [
                'code' => 'LTE-001'
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/usuario/nuevoRamal', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $json = $app->request->post('json', null);
            $parametros = json_decode($json);
            $descripcion_ramal_factura = (isset($parametros->descripcion_ramal_factura)) ? $parametros->descripcion_ramal_factura : null;
            $sql = "INSERT INTO configuracion.ramal_factura(
                            descripcion_ramal_factura)
                            VALUES ('$descripcion_ramal_factura');";
            $conexon->consultaSimple($sql);
            $data = [
                'code' => 'LTE-001'
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/usuario/actualizarRamal', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $json = $app->request->post('json', null);
            $parametros = json_decode($json);
            $descripcion_ramal_factura = (isset($parametros->descripcion_ramal_factura)) ? $parametros->descripcion_ramal_factura : null;
            $id_ramal_factura = (isset($parametros->id_ramal_factura)) ? $parametros->id_ramal_factura : null;
            $sql = "UPDATE configuracion.ramal_factura
                    SET descripcion_ramal_factura='$descripcion_ramal_factura'
                    WHERE id_ramal_factura = '$id_ramal_factura';";
            $conexon->consultaSimple($sql);
            $data = [
                'code' => 'LTE-001'
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/usuario/listarRamales', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $sql = "select * from configuracion.ramal_factura";
            $r = $conexon->consultaComplejaAso($sql);
            $data = [
                'code' => 'LTE-001',
                'data' => $r
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/abono/cargarAbono', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {

            $json = $app->request->post('json', null);
            $jsonUsuario = $app->request->post('usuario', null);
            $total = $app->request->post('total', null);

            $parametros = json_decode($json);
            $usuario = json_decode($jsonUsuario);
            $total_abono_factura = (isset($parametros->total_abono_factura)) ? $parametros->total_abono_factura : null;
            $tipo_abono = (isset($parametros->tipo_abono)) ? $parametros->tipo_abono : null;
            $id_usuario = (isset($usuario->id_usuario)) ? $usuario->id_usuario : null;
            $fecha_creacion = date('Y-m-d H:i');

            $sql = "INSERT INTO facturacion.abonos_factura(
                    total_abono_factura,fecha_creacion_abono_factura, 
                     estado_abono_factura, tipo_abono, id_usuario_fk_abono_factura)
                    VALUES ( '$total_abono_factura', '$fecha_creacion', 'ACTIVO', '$tipo_abono', '$id_usuario') returning id_abono_factura;";
            $r = $conexon->consultaComplejaNorAso($sql);
            $id_abono_factura = $r['id_abono_factura'];
            $fecha_codigo = date('y-m-d');
            $arrayFecha = explode('-', $fecha_codigo);
            $codigoAbono = 'A-' . $arrayFecha[0] . $arrayFecha[1] . $id_usuario . $id_abono_factura;
            $sql = "update facturacion.abonos_factura set codigo_abono_factura = '$codigoAbono' WHERE
                    id_abono_factura = '$id_abono_factura'";
            $conexon->consultaSimple($sql);
            $r1 = generarReporteAbono($usuario, $total, $codigoAbono, $total_abono_factura, $fecha_creacion);
            $data = [
                'code' => 'LTE-001',
                'data' => $r1
            ];
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/abono/efectuarAbono', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);

    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $json = $app->request->post('json', null);
            $parametros = json_decode($json);
            $codigo_abono_factura = (isset($parametros->codigo_abono_factura)) ? $parametros->codigo_abono_factura : null;
            $fecha_creacion = date('Y-m-d H:i');
            $sql = "select * from facturacion.abonos_factura  a_f
                    inner join seguridad.usuario usu on a_f.id_usuario_fk_abono_factura = usu.id_usuario 
                    where a_f.codigo_abono_factura = '$codigo_abono_factura' and a_f.estado_abono_factura = 'ACTIVO';";
            $r = $conexon->consultaComplejaNorAso($sql);
            if ($r != 0) {
                $id_usuario = $r['id_usuario_fk_abono_factura'];
                $id_abono = $r['id_abono_factura'];
                $sql = "select * from facturacion.registro_factura rf 
                    inner join facturacion.factura f on rf.id_factura_registro_factura_fk = f.id_factura
                    where rf.id_usuario_registro_factura_fk = '$id_usuario' and rf.estado_factura = 'SINPAGAR' 
                    order by rf.fecha_inicial_facturado asc";
                $r2 = $conexon->consultaComplejaAso($sql);
                if ($r2 != 0) {
                    $validacionSalida = 0;
                    $validacionEntrada = 0;
                    $contador = 0;
                    $id_registro = null;
                    while ($validacionSalida == 0) {

                        if ($contador <= count($r2)) {
                            $json_tarifas_factura = json_decode($r2[$contador]['json_tarifas'], true);
                            if ($json_tarifas_factura['totalTarifa'] > 0) {
                                $validacionSalida = 1;
                                $validacionEntrada = 1;
                            } else {
                                $contador++;
                            }
                        } else {
                            $validacionSalida = 1;
                        }

                    }
                    if ($validacionEntrada == 1) {
                        $total_abono = $r['total_abono_factura'];
                        $id_registro = $r2[$contador]['id_registro_factura'];
                        if (count($r2) == 1) {
                            if ($r2[$contador]['json_cargue_factura'] != '' && $r2[$contador]['json_cargue_factura'] != null) {
                                $result = $json_tarifas_factura['totalTarifa'] - $total_abono;
                                $concatenar = '';
                                if ($result < 0) {
                                    $json_tarifas_factura['totalTarifa'] = 0;
                                    $concatenar = ", estado_factura='PAGADO'";
                                } else {
                                    $json_tarifas_factura['totalTarifa'] = $result;
                                }
                                $jt = json_encode($json_tarifas_factura);
                                $sql = "UPDATE facturacion.registro_factura
                                    SET  json_tarifas='$jt' " . $concatenar . "
                                    WHERE id_registro_factura='$id_registro';";
                                $conexon->consultaSimple($sql);
                                $json_cargue_factura = json_decode($r2[$contador]['json_cargue_factura'], true);
                                $codigo_factura = $r2[$contador]['codigo_registro_factura'];
                                for ($i = 0; $i < count($json_cargue_factura); $i++) {
                                    if ($codigo_factura == $json_cargue_factura[$i]['codigoFactura']) {
                                        $total_tarifa = $json_cargue_factura[$i]['detalle']['totalTarifa'];
                                        $tarifa = $json_cargue_factura[$i]['detalle']['totalTarifa'];
                                        if ($result < 0) {
                                            $json_cargue_factura[$i]['detalle']['totalTarifa'] = 0;
                                            $result = $result * -1;
                                            $sql = "UPDATE facturacion.abonos_factura
                                                SET  total_factura_abono_factura='$tarifa', 
                                                restante_abono_factura='$result', fecha_creacion_abono_factura='$fecha_creacion', 
                                                id_registro_factura_fk_abono_factura='$id_registro', estado_abono_factura='UTILIZADO'
                                                WHERE id_abono_factura='$id_abono';";
                                            $conexon->consultaSimple($sql);
                                            $sql = "INSERT INTO facturacion.abonos_factura(
                                                 total_abono_factura,  fecha_creacion_abono_factura, 
                                                 estado_abono_factura,
                                                 tipo_abono, id_usuario_fk_abono_factura, id_abono_factura_fk_abono_factura)
                                                VALUES ('$result', '$fecha_creacion', 'ACTIVO', 
                                                'SOBRANTE', '$id_usuario', '$id_abono') returning id_abono_factura;";
                                            $r3 = $conexon->consultaComplejaNorAso($sql);
                                            $id_abono_factura = $r3['id_abono_factura'];
                                            $fecha_codigo = date('y-m-d');
                                            $arrayFecha = explode('-', $fecha_codigo);
                                            $codigoAbono = 'A-' . $arrayFecha[0] . $arrayFecha[1] . $id_usuario . $id_abono_factura;
                                            $sql = "update facturacion.abonos_factura set codigo_abono_factura = '$codigoAbono' WHERE 
                                                id_abono_factura = '$id_abono_factura'";
                                            $conexon->consultaSimple($sql);
                                        } else {
                                            $json_cargue_factura[$i]['detalle']['totalTarifa'] = $result;
                                            $sql = "UPDATE facturacion.abonos_factura
                                                SET  total_factura_abono_factura='$result', 
                                                restante_abono_factura='0', fecha_creacion_abono_factura='$fecha_creacion', 
                                                id_registro_factura_fk_abono_factura='$id_registro', estado_abono_factura='UTILIZADO'
                                                WHERE id_abono_factura='$id_abono';";
                                            $conexon->consultaSimple($sql);
                                        }
                                        $jt = json_encode($json_cargue_factura);
                                        $sql = "UPDATE facturacion.registro_factura
                                            SET  json_cargue_factura='$jt' WHERE id_registro_factura='$id_registro';";
                                        $conexon->consultaSimple($sql);
                                    }
                                }
                                $data = [
                                    'code' => 'LTE-001'
                                ];
                            } else {
                                $data = [
                                    'code' => 'LTE-000',
                                    'status' => 'error',
                                    'msg' => 'Lo sentimos, Para aplicar este abono debes generar las facturas'
                                ];
                            }
                        } else {
                            $ultimo = count($r2) - 1;
                            if ($r2[$ultimo]['json_cargue_factura'] != '' && $r2[$ultimo]['json_cargue_factura'] != null) {
                                $validacionSalida = 0;
                                $contador = 0;
                                while ($validacionSalida == 0) {
                                    $json_tarifas_factura = json_decode($r2[$contador]['json_tarifas'], true);
                                    $total_tarifa = $json_tarifas_factura['totalTarifa'];
                                    $restante = $json_tarifas_factura['totalTarifa'] - $total_abono;
                                    if ($restante < 0) {
                                        $json_tarifas_factura['totalTarifa'] = 0;
                                        $restante = $restante * -1;
                                        $contadorJsonCargue = null;
                                        if ($r2[$contador]['json_cargue_factura'] != '' && $r2[$contador]['json_cargue_factura'] != null) {
                                            $json_cargue_factura = json_decode($r2[$contador]['json_cargue_factura'], true);
                                            for ($i = 0; $i < count($json_cargue_factura); $i++) {
                                                $codigo_factura = $r2[$contador]['codigo_registro_factura'];
                                                if ($json_cargue_factura[$i]['codigoFactura'] == $codigo_factura) {
                                                    $contadorJsonCargue = $i;
                                                }
                                            }
                                        }
                                        $concatenar = '';
                                        if ($contadorJsonCargue != null) {
                                            $json_cargue_factura[$contadorJsonCargue]['detalle']['totalTarifa'] = 0;
                                            $json_cargue_factura = json_encode($json_cargue_factura);
                                            $concatenar = ", json_cargue_factura ='$json_cargue_factura' ";
                                        }
                                        $id_factura = $r2[$contador]['id_registro_factura'];
                                        $json_tarifas_factura = json_encode($json_tarifas_factura);
                                        $sql = "UPDATE facturacion.registro_factura
                                        SET  estado_factura='PAGADO', json_tarifas='$json_tarifas_factura' " . $concatenar . "
                                        WHERE id_registro_factura='$id_factura';";
                                        $conexon->consultaSimple($sql);
                                        $sql = "UPDATE facturacion.abonos_factura
                                        SET  total_factura_abono_factura='$total_tarifa', restante_abono_factura='$restante', 
                                        id_registro_factura_fk_abono_factura='$id_factura', estado_abono_factura='UTILIZADO'
                                        WHERE id_abono_factura='$id_abono';";
                                        $conexon->consultaSimple($sql);

                                        $sql = "INSERT INTO facturacion.abonos_factura(
                                        total_abono_factura, fecha_creacion_abono_factura, 
                                        estado_abono_factura, tipo_abono, id_usuario_fk_abono_factura, 
                                         id_abono_factura_fk_abono_factura)
                                        VALUES ('$restante','$fecha_creacion', 'ACTIVO', 'SOBRANTE', '$id_usuario', '$id_abono') returning id_abono_factura;";
                                        $r3 = $conexon->consultaComplejaNorAso($sql);
                                        $id_abono_factura = $r3['id_abono_factura'];
                                        $fecha_codigo = date('y-m-d');
                                        $arrayFecha = explode('-', $fecha_codigo);
                                        $codigoAbono = 'A-' . $arrayFecha[0] . $arrayFecha[1] . $id_usuario . $id_abono_factura;
                                        $sql = "update facturacion.abonos_factura set codigo_abono_factura = '$codigoAbono' WHERE 
                                                id_abono_factura = '$id_abono_factura'";
                                        $conexon->consultaSimple($sql);
                                        $id_abono = $id_abono_factura;
                                        $total_abono = $restante;
                                        $ultimo = count($r2) - 1;
                                        if ($r2[$ultimo]['json_cargue_factura'] != '' && $r2[$ultimo]['json_cargue_factura'] != null) {
                                            $json_cargue_factura = json_decode($r2[$ultimo]['json_cargue_factura'], true);
                                            for ($i = 0; $i < count($json_cargue_factura); $i++) {
                                                $codigo_factura = $r2[$contador]['codigo_registro_factura'];
                                                if ($json_cargue_factura[$i]['codigoFactura'] == $codigo_factura) {
                                                    $id_f = $r2[$ultimo]['id_registro_factura'];
                                                    $json_cargue_factura[$i]['detalle']['totalTarifa'] = 0;
                                                    $json_cargue_factura = json_encode($json_cargue_factura);
                                                    $r2[$ultimo]['json_cargue_factura'] = $json_cargue_factura;
                                                    $sql = "UPDATE facturacion.registro_factura
                                                    SET   json_cargue_factura ='$json_cargue_factura'
                                                    WHERE id_registro_factura='$id_f';";
                                                    $conexon->consultaSimple($sql);
                                                    break;
                                                }
                                            }
                                        }
                                        $contador++;
                                    } else {
                                        $json_tarifas_factura['totalTarifa'] = $restante;
                                        $contadorJsonCargue = null;
                                        if ($r2[$contador]['json_cargue_factura'] != '' && $r2[$contador]['json_cargue_factura'] != null) {
                                            $json_cargue_factura = json_decode($r2[$contador]['json_cargue_factura'], true);
                                            for ($i = 0; $i < count($json_cargue_factura); $i++) {
                                                $codigo_factura = $r2[$contador]['codigo_registro_factura'];
                                                if ($json_cargue_factura[$i]['codigoFactura'] == $codigo_factura) {
                                                    $contadorJsonCargue = $i;
                                                }
                                            }
                                        }
                                        $concatenar = '';
                                        if ($contadorJsonCargue != null) {
                                            $json_cargue_factura[$contadorJsonCargue]['detalle']['totalTarifa'] = $restante;
                                            $json_cargue_factura = json_encode($json_cargue_factura);
                                            $concatenar = ", json_cargue_factura ='$json_cargue_factura' ";
                                        }
                                        $concatenar2 = '';
                                        if ($restante == 0) {
                                            $concatenar2 = "estado_factura='PAGADO',";
                                        }
                                        $id_factura = $r2[$contador]['id_registro_factura'];
                                        $json_tarifas_factura = json_encode($json_tarifas_factura);
                                        $sql = "UPDATE facturacion.registro_factura
                                        SET " . $concatenar2 . "  json_tarifas='$json_tarifas_factura' " . $concatenar . "
                                        WHERE id_registro_factura='$id_factura';";
                                        $conexon->consultaSimple($sql);
                                        $ultimo = count($r2) - 1;
                                        if ($r2[$ultimo]['json_cargue_factura'] != '' && $r2[$ultimo]['json_cargue_factura'] != null) {
                                            $json_cargue_factura = json_decode($r2[$ultimo]['json_cargue_factura'], true);
                                            for ($n = 0; $n < count($json_cargue_factura); $n++) {
                                                $codigo_factura = $r2[$contador]['codigo_registro_factura'];
                                                if ($json_cargue_factura[$n]['codigoFactura'] == $codigo_factura) {
                                                    $id_f = $r2[$ultimo]['id_registro_factura'];
                                                    $json_cargue_factura[$n]['detalle']['totalTarifa'] = $restante;
                                                    $json_cargue_factura = json_encode($json_cargue_factura);
                                                    $sql = "UPDATE facturacion.registro_factura
                                                    SET   json_cargue_factura ='$json_cargue_factura'
                                                    WHERE id_registro_factura='$id_f';";
                                                    $conexon->consultaSimple($sql);
                                                    break;
                                                }
                                            }
                                        }

                                        if ($total_tarifa > $total_abono) {
                                            $restante = 0;
                                        }
                                        $sql = "UPDATE facturacion.abonos_factura
                                        SET  total_factura_abono_factura='$total_tarifa', restante_abono_factura='$restante', 
                                        id_registro_factura_fk_abono_factura='$id_factura', estado_abono_factura='UTILIZADO'
                                        WHERE id_abono_factura='$id_abono';";
                                        $conexon->consultaSimple($sql);
                                        $validacionSalida = 1;

                                    }
                                }
                                $data = [
                                    'code' => 'LTE-001'
                                ];
                            } else {
                                $data = [
                                    'code' => 'LTE-000',
                                    'status' => 'error',
                                    'msg' => 'Lo sentimos, Para aplicar este abono debes generar las facturas'
                                ];
                            }
                        }
                    } else {
                        $data = [
                            'code' => 'LTE-000',
                            'status' => 'error',
                            'msg' => 'Lo sentimos, No tiene facturas pendientes'
                        ];
                    }
                } else {
                    $data = [
                        'code' => 'LTE-000',
                        'status' => 'error',
                        'msg' => 'Lo sentimos, no tienen facturas pendientes'
                    ];
                }
            } else {
                $data = [
                    'code' => 'LTE-000',
                    'status' => 'error',
                    'msg' => 'Lo sentimos, Este abono ya fue utlizado o no existe'
                ];
            }

        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/administrador/abono/cargarFacturaValor', function () use ($app) {
    $helper = new helper();
    $conexon = new conexPGSeguridad();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionToken = $helper->authCheck($token);
        if ($validacionToken == true) {
            $id_usuario = $app->request->post('id_user', null);
            $sql = "select * from facturacion.registro_factura rf 
                    inner join facturacion.factura f on rf.id_factura_registro_factura_fk = f.id_factura
                    where rf.id_usuario_registro_factura_fk = '$id_usuario' and rf.estado_factura = 'SINPAGAR' 
                    order by rf.fecha_inicial_facturado desc";

            $r = $conexon->consultaComplejaNorAso($sql);
            if ($r != 0) {
                if ($r['json_cargue_factura'] != '' && $r['json_cargue_factura'] != null) {
                    $totalPagar = 0;
                    $arregloFacturasPagar = json_decode($r['json_cargue_factura'], true);
                    for ($i = 0; $i < count($arregloFacturasPagar); $i++) {
                        $totalPagar += $arregloFacturasPagar[$i]['detalle']['totalTarifa'];
                    }
                    $data = [
                        'code' => 'LTE-001',
                        'data' => ['totalAbonoFormato' => number_format($totalPagar, 0),
                            'totalAbono' => $totalPagar]

                    ];
                } else {
                    $data = [
                        'code' => 'LTE-000',
                        'status' => 'error',
                        'msg' => 'Lo sentimos, Para crear este abono debes generar las facturas de este usuario'
                    ];
                }
            } else {
                $data = [
                    'code' => 'LTE-000',
                    'status' => 'error',
                    'msg' => 'Lo sentimos, no tiene facturas pendientes'
                ];
            }

        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-013',
        ];
    }
    echo $helper->checkCode($data);
});
function generarReportePDF($arregloFactura)
{

    $route = __DIR__ . '../../../public/pdf/';
    $img = __DIR__ . '../../../public/imagenes_estandar/logo25%factura.png';
    $imgTijera = __DIR__ . '../../../public/imagenes_estandar/tijeras.png';
    $style = array(
        'position' => '',
        'align' => 'C',
        'stretch' => false,
        'fitwidth' => true,
        'cellfitalign' => '',
        'border' => false,
        'hpadding' => 'auto',
        'vpadding' => 'auto',
        'fgcolor' => array(0, 0, 0),
        'bgcolor' => false, //array(255,255,255),
        'text' => true,
        'font' => 'helvetica',
        'fontsize' => 6,
        'stretchtext' => 4
    );
    $pdf = new TCPDF('P', 'mm', 'Letter', true, 'UTF-8', false, false);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();


    $X = 10;
    $Y = 10;
    $contadorFacturas = 0;
    for ($i = 0; $i < count($arregloFactura); $i++) {
        $pdf->SetXY($X, $Y);
        $pdf->Image($img, '', '', 190, 87, '', '', 'T', false, 300, '', false,
            false, 0, false, false, true);
        $pdf->SetXY($X, $Y);
        $pdf->Cell(190, 130, '', 1, 0, '', 0);
        $pdf->SetXY($X, $Y);
        $pdf->write1DBarcode($arregloFactura[$i]['codigo_registro_factura'], 'C128', '', '', '', 20, 0.5, $style, 'N');
        $pdf->SetFont('times', 'B', 16);
        $pdf->SetTextColor(0, 115, 170);
        $pdf->SetXY($X, $Y);
        $pdf->Cell(190, 20, 'ASUACOR', 1, 0, 'C', 0);
        $pdf->SetX($X);
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 20);
        $pdf->SetFont('times', 'B', 11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(95, 5, 'Nombres: ' . $arregloFactura[$i]['nombre'] . ' ' . $arregloFactura[$i]['apellido'], 1, 0, '', 0);
        $pdf->SetX($pdf->GetX());
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Ramal: ' . $arregloFactura[$i]['ramal'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Direccion envio: ' . $arregloFactura[$i]['direccion_entrega'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Telefono: ' . $arregloFactura[$i]['telefono'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Fecha de inicio: ' . $arregloFactura[$i]['fecha_inicial'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Fecha de pago: ' . $arregloFactura[$i]['fecha_pago'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Fecha de corte: ' . $arregloFactura[$i]['fecha_corte'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Código medidor: ' . $arregloFactura[$i]['codigo_medidor'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Número tapa: ' . $arregloFactura[$i]['numero_tapa_factura'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Total facturas cobradas: ' . $arregloFactura[$i]['total_facturas_cobradas'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 14);
        $pdf->SetTextColor(254, 92, 92);
        $pdf->Cell(95, 10, 'Total factura: ' . number_format($arregloFactura[$i]['total_pagar_factura'], 0), 1, 0, '', 0);
        $pdf->SetXY(10, $pdf->GetY() + 15);

        $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 190, $pdf->GetY());
        $pdf->SetXY($pdf->GetX() + 1, $pdf->GetY() - 3);
        $pdf->Image($imgTijera, '', '', 3, 3, '', '', 'T', false, 300, '', false,
            false, 0, false, false, true);
        $pdf->SetY($pdf->GetY() + 10);
        $pdf->SetTextColor(0, 115, 170);
        $pdf->SetFont('times', 'B', 10);
        $pdf->Cell(190, 5, 'ASUACOR-Desprendible', 1, 1, 'C');
        $pdf->SetFont('times', 'B', 9);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(10, $pdf->GetY());
        $pdf->Cell(95, 3, 'Codigo Factura: ' . $arregloFactura[$i]['codigo_registro_factura'], 1, 1, 'L');
        $pdf->SetXY(10, $pdf->GetY());
        $pdf->Cell(95, 3, 'Nombre: ' . $arregloFactura[$i]['nombre'] . ' ' . $arregloFactura[$i]['apellido'], 1, 1, 'L');
        $pdf->SetXY(10, $pdf->GetY());
        $pdf->Cell(95, 3, 'Ramal: ' . $arregloFactura[$i]['ramal'], 1, 1, 'L');
        $pdf->SetXY(10, $pdf->GetY());
        $pdf->Cell(95, 3, 'Fecha de corte: ' . $arregloFactura[$i]['fecha_corte'], 1, 1, 'L');
        $pdf->SetXY(10, $pdf->GetY());
        $pdf->Cell(95, 3, 'Cantidad facturas cobradas: ' . $arregloFactura[$i]['total_facturas_cobradas'], 1, 1, 'L');
        $pdf->SetXY(10, $pdf->GetY());
        $pdf->SetTextColor(254, 92, 92);
        $pdf->Cell(95, 3, 'Total factura: ' . number_format($arregloFactura[$i]['total_pagar_factura']), 1, 1, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(10, $pdf->GetY());
        $pdf->Cell(95, 13, '', 1, 0, 'L');
        $pdf->SetXY(10, $pdf->GetY() + 9);
        $pdf->SetFont('times', 'B', 5);
        $pdf->Cell(95, 5, 'ESPACIO PARA SELLO Y FIRMA DEL CAJERO: ', 0, 0, 'C');
        $pdf->SetXY(100, $pdf->GetY() - 30);
        $pdf->write1DBarcode($arregloFactura[$i]['codigo_registro_factura'], 'C128', '', '', '', 30, 1.0, $style, 'N');

        /*
         *
        $pdf->SetY($pdf->GetY() + 10);
        $pdf->SetX($pdf->GetX() + 88);
        $pdf->SetFont('times', 'B', 14);
        $pdf->SetTextColor(254, 92, 92);
        $pdf->write1DBarcode($arregloFactura[$i]['codigo_registro_factura'], 'C128', '', '', '', 19, 1.0, $style, 'N');
        $pdf->SetFont('times', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(105, $pdf->GetY() - 5);
        $pdf->Cell(5, 5, 'Nombre: ' . $arregloFactura[$i]['nombre'] . ' ' . $arregloFactura[$i]['apellido'], 0);
        $pdf->SetXY(105, $pdf->GetY() + 3);
        $pdf->Cell(5, 5, 'Total factura: ' . number_format($arregloFactura[$i]['total_pagar_factura'], 0), 0);
        */
        //Pintar detalles de la factura
        $pdf->SetXY($X, $Y + 25);
        $pdf->SetFont('times', 'B', 8);
        $pdf->SetTextColor(0, 115, 170);
        $pdf->Cell(95, 5, 'Detalles', 1, 0, 'C', 0);
        $pdf->SetXY($X, $Y + 28);
        $contador = 0;
        $pdf->SetY($pdf->GetY() + 3);
        $pdf->Cell(25, 1, 'Producto', 0, 0, 'L', 0);
        $pdf->SetX($pdf->GetX());
        $pdf->Cell(15, 1, 'Total', 0, 0, 'L', 0);
        $pdf->SetX($pdf->GetX());
        $pdf->Cell(15, 1, 'código factura', 0, 0, 'L', 0);
        $pdf->SetTextColor(0, 0, 0);
        for ($n = 0; $n < count($arregloFactura[$i]['detalles']); $n++) {
            $codigoFactura = $arregloFactura[$i]['detalles'][$n]['codigoFactura'];
            for ($y = 0; $y < count($arregloFactura[$i]['detalles'][$n]['detalle']['tarifas']); $y++) {
                $pdf->SetY($pdf->GetY() + 3);
                $pdf->Cell(25, 1, $arregloFactura[$i]['detalles'][$n]['detalle']['tarifas'][$y]['descripcion_tarifa'], 0, 0, 'L', 0);
                $pdf->SetX($pdf->GetX());
                $pdf->Cell(15, 1, number_format($arregloFactura[$i]['detalles'][$n]['detalle']['tarifas'][$y]['total_tarifa'], 0), 0, 0, 'L', 0);
                $pdf->SetX($pdf->GetX());
                $pdf->Cell(15, 1, $codigoFactura, 0, 0, 'L', 0);

            }
            if ($n % 2 == 0) {
                $pdf->SetTextColor(0, 115, 170);
            } else {
                $pdf->SetTextColor(0, 0, 0);
            }
        }
        $X = 10;
        $Y += 133;
        $contadorFacturas++;
        if ($contadorFacturas == 2) {
            $X = 10;
            $Y = 10;
            $contadorFacturas = 0;
            $pdf->AddPage();
        }
    }


    if (!file_exists($route))
        mkdir($route, 0, true);

    $pdf->Output($route . "reporteFactura.pdf", "F");
    return '/pdf/reporteFactura.pdf';
}

function generarReporteAbono($usuario, $total, $codigo, $abono, $fecha_cracion)
{

    $route = __DIR__ . '../../../public/pdf/';
    $img = __DIR__ . '../../../public/imagenes_estandar/logo25%factura.png';
    $imgTijera = __DIR__ . '../../../public/imagenes_estandar/tijeras.png';
    $style = array(
        'position' => '',
        'align' => 'C',
        'stretch' => false,
        'fitwidth' => true,
        'cellfitalign' => '',
        'border' => false,
        'hpadding' => 'auto',
        'vpadding' => 'auto',
        'fgcolor' => array(0, 0, 0),
        'bgcolor' => false, //array(255,255,255),
        'text' => true,
        'font' => 'helvetica',
        'fontsize' => 6,
        'stretchtext' => 4
    );
    $pdf = new TCPDF('P', 'mm', 'Letter', true, 'UTF-8', false, false);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();


    $X = 10;
    $Y = 10;
    $contadorFacturas = 0;

    $pdf->SetXY($X, $Y);
    $pdf->Image($img, '', '', 190, 87, '', '', 'T', false, 300, '', false,
        false, 0, false, false, true);
    $pdf->SetXY($X, $Y);
    $pdf->Cell(190, 124, '', 1, 0, '', 0);
    $pdf->SetXY($X, $Y);
    $pdf->write1DBarcode($codigo, 'C128', '', '', '', 20, 0.5, $style, 'N');
    $pdf->SetFont('times', 'B', 16);
    $pdf->SetTextColor(0, 115, 170);
    $pdf->SetXY($X, $Y);
    $pdf->Cell(190, 20, 'ASUACOR', 1, 0, 'C', 0);
    $pdf->SetX($X);
    $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 20);
    $pdf->SetFont('times', 'B', 11);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(95, 5, 'Nombres: ' . $usuario->nombre_usuario . ' ' . $usuario->apellido_usuario, 1, 0, '', 0);;
    $pdf->SetFont('times', 'B', 11);
    $pdf->Cell(95, 5, 'Cedula: ' . $usuario->documento_usuario, 1, 0, '', 0);
    $pdf->SetXY(105, $pdf->GetY() + 5);
    $pdf->SetFont('times', 'B', 11);
    $pdf->Cell(95, 5, 'Telefono: ' . $usuario->telefono_usuario, 1, 0, '', 0);
    $pdf->SetXY(105, $pdf->GetY() + 5);
    $pdf->SetFont('times', 'B', 11);
    $pdf->Cell(95, 5, 'Fecha cracion: ' . $fecha_cracion, 1, 0, '', 0);
    $pdf->SetXY(105, $pdf->GetY() + 5);
    $pdf->SetFont('times', 'B', 14);
    $pdf->SetTextColor(254, 92, 92);
    $pdf->Cell(95, 10, 'Total deuda: ' . number_format($total, 0), 1, 0, '', 0);
    $pdf->SetXY(105, $pdf->GetY() + 10);
    $pdf->SetFont('times', 'B', 14);
    $pdf->SetTextColor(87, 121, 40);
    $pdf->Cell(95, 10, 'Total abono: ' . number_format($abono, 0), 1, 0, '', 0);
    $pdf->SetXY(105, $pdf->GetY() + 10);
    $pdf->SetFont('times', 'B', 14);
    $pdf->SetTextColor(255, 27, 45);
    $pdf->Cell(95, 10, 'Total pendiente: ' . number_format($total - $abono, 0), 1, 0, '', 0);
    $pdf->SetXY(10, $pdf->GetY() + 15);

    $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 190, $pdf->GetY());
    $pdf->SetXY($pdf->GetX() + 1, $pdf->GetY() - 3);
    $pdf->Image($imgTijera, '', '', 3, 3, '', '', 'T', false, 300, '', false,
        false, 0, false, false, true);
    $pdf->SetY($pdf->GetY() + 10);
    $pdf->SetTextColor(0, 115, 170);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(190, 5, 'ASUACOR-Desprendible', 1, 1, 'C');
    $pdf->SetFont('times', 'B', 9);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(10, $pdf->GetY());
    $pdf->Cell(95, 3, 'Codigo abono: ' . $codigo, 1, 1, 'L');
    $pdf->SetXY(10, $pdf->GetY());
    $pdf->Cell(95, 3, 'Nombre: ' . $usuario->nombre_usuario . ' ' . $usuario->apellido_usuario, 1, 1, 'L');
    $pdf->SetXY(10, $pdf->GetY());
    $pdf->Cell(95, 3, 'Cedula: ' . $usuario->documento_usuario, 1, 1, 'L');
    $pdf->SetXY(10, $pdf->GetY());
    $pdf->Cell(95, 3, 'Fecha de creacion: ' . $fecha_cracion, 1, 1, 'L');
    $pdf->SetXY(10, $pdf->GetY());
    $pdf->SetTextColor(254, 92, 92);
    $pdf->Cell(95, 3, 'Total deuda: ' . number_format($total, 0), 1, 1, 'L');
    $pdf->SetXY(10, $pdf->GetY());
    $pdf->SetTextColor(87, 121, 40);
    $pdf->Cell(95, 3, 'Total abono: ' . number_format($abono, 0), 1, 1, 'L');
    $pdf->SetXY(10, $pdf->GetY());
    $pdf->SetTextColor(255, 27, 45);
    $pdf->Cell(95, 3, 'Total pendiente: ' . number_format($total - $abono, 0), 1, 1, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(10, $pdf->GetY());
    $pdf->Cell(95, 13, '', 1, 0, 'L');
    $pdf->SetXY(10, $pdf->GetY() + 9);
    $pdf->SetFont('times', 'B', 5);
    $pdf->Cell(95, 5, 'ESPACIO PARA SELLO Y FIRMA DEL CAJERO: ', 0, 0, 'C');
    $pdf->SetXY(100, $pdf->GetY() - 30);
    $pdf->write1DBarcode($codigo, 'C128', '', '', '', 30, 1.0, $style, 'N');

    /*
     *
    $pdf->SetY($pdf->GetY() + 10);
    $pdf->SetX($pdf->GetX() + 88);
    $pdf->SetFont('times', 'B', 14);
    $pdf->SetTextColor(254, 92, 92);
    $pdf->write1DBarcode($arregloFactura[$i]['codigo_registro_factura'], 'C128', '', '', '', 19, 1.0, $style, 'N');
    $pdf->SetFont('times', 'B', 8);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(105, $pdf->GetY() - 5);
    $pdf->Cell(5, 5, 'Nombre: ' . $arregloFactura[$i]['nombre'] . ' ' . $arregloFactura[$i]['apellido'], 0);
    $pdf->SetXY(105, $pdf->GetY() + 3);
    $pdf->Cell(5, 5, 'Total factura: ' . number_format($arregloFactura[$i]['total_pagar_factura'], 0), 0);
    */
    //Pintar detalles de la factura
    $pdf->SetXY($X, $Y + 25);
    $pdf->SetFont('times', 'B', 8);
    $pdf->SetTextColor(0, 115, 170);
    $pdf->Cell(95, 5, 'Detalles', 1, 0, 'C', 0);
    $pdf->SetXY($X, $Y + 28);
    $contador = 0;
    $pdf->SetY($pdf->GetY() + 3);
    $pdf->Cell(25, 1, 'Producto', 0, 0, 'L', 0);
    $pdf->SetX($pdf->GetX());
    $pdf->Cell(15, 1, 'Total', 0, 0, 'L', 0);
    $pdf->SetX($pdf->GetX());
    $pdf->Cell(15, 1, 'código factura', 0, 0, 'L', 0);
    $pdf->SetTextColor(0, 0, 0);


    if (!file_exists($route))
        mkdir($route, 0, true);

    $pdf->Output($route . "reporteAbono.pdf", "F");
    return '/pdf/reporteAbono.pdf';
}