<?php

$app->post('/administrador/listarFacturaUsuario', function () use ($app) {
    $conexion = new conexPGSeguridad();
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $id_usuario = $app->request->post('id_usuario', null);
        $sql = "select fac.* from facturacion.factura fac where fac.id_usuario_factura_fk = '$id_usuario';";
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
            $sql = "select * from configuracion.configuracion_factura";
            $r = $conexon->consultaComplejaNorAso($sql);
            $id_configuracion_factura = $r['id_configuracion_factura'];
            $sql = "INSERT INTO facturacion.factura(
                    observacion_factura, codigo_medidor_factura, direccion_factura, 
                    numero_tapa_factura, id_usuario_factura_fk, id_configuracion_factura_fk, estado_factura)
                    VALUES ( '$observacion_factura', '$codigo_medidor_factura',
                     '$direccion_factura', '$numero_tapa_factura', '$id_usuario_factura_fk', '$id_configuracion_factura', '$estado_factura');";
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
            $sql = "UPDATE facturacion.factura
                    SET observacion_factura='$observacion_factura', codigo_medidor_factura='$codigo_medidor_factura', direccion_factura='$direccion_factura', 
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
            $sql = "select usu.* from seguridad.usuario usu";
            $usuario = $conexon->consultaComplejaAso($sql);
            $arregloFacturas = array();
            for ($i = 0; $i < count($usuario); $i++) {
                $id_usuario = $usuario[$i]['id_usuario'];
                $sql = "select fac.* from facturacion.factura fac where fac.id_usuario_factura_fk = '$id_usuario'";
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
                            $codigoFactura = $fechFac[0] . $fechFac[1] . $usuario[$i]['documento_usuario'] . $r['id_registro_factura'];
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
            $sql = "select * from seguridad.usuario";
            $usuario = $conexon->consultaComplejaAso($sql);
            $arregloFactura = array();
            for ($i = 0; $i < count($usuario); $i++) {
                $idUsuario = $usuario[$i]['id_usuario'];
                $sql = "select * from facturacion.factura fa where fa.id_usuario_factura_fk = '$idUsuario'";
                $rFactura = $conexon->consultaComplejaAso($sql);
                if ($rFactura != 0) {
                    for ($y = 0; $y < count($rFactura); $y++) {
                        $idFactura = $rFactura[$y]['id_factura'];
                        $sql = "select * from facturacion.registro_factura rf 
                        inner join facturacion.factura fa on rf.id_factura_registro_factura_fk = fa.id_factura 
                        inner join seguridad.usuario usu on fa.id_usuario_factura_fk = usu.id_usuario 
                        where rf.id_usuario_registro_factura_fk = '$idUsuario' and fa.id_factura ='$idFactura' and rf.estado_factura ='SINPAGAR'
                        ORDER BY  rf.fecha_inicial_facturado desc";
                        $registros = $conexon->consultaComplejaAso($sql);
                        if ($registros != 0) {
                            $totalFactura = 0;
                            $arregloDetalle = array();
                            $totalFacturasCobradas = 0;
                            for ($n = 0; $n < count($registros); $n++) {
                                $arregloTarifas = json_decode($registros[$n]['json_tarifas'], true);

                                $totalFactura += $arregloTarifas['totalTarifa'];
                                array_push($arregloDetalle, ['codigoFactura'=>$registros[$n]['codigo_registro_factura'],
                                    'detalle'=>$arregloTarifas]);
                                $totalFacturasCobradas++;
                            }
                            $id_registro = $registros[0]['id_registro_factura'];
                            $json=json_encode($arregloDetalle);
                            $sql = "update facturacion.registro_factura set json_cargue_factura='$json' where id_registro_factura = '$id_registro'";
                            $conexon->consultaSimple($sql);
                            array_push($arregloFactura, [
                                'documento' => $registros[0]['documento_usuario'],
                                'nombre' => $registros[0]['nombre_usuario'],
                                'apellido' => $registros[0]['apellido_usuario'],
                                'telefono' => $registros[0]['telefono_usuario'],
                                'direccion' => $registros[0]['direccion_usuario'],
                                'fecha_inicial' => $registros[0]['fecha_inicial_facturado'],
                                'fecha_pago' => $registros[0]['fecha_pago_factura'],
                                'fecha_corte' => $registros[0]['fecha_final_factura'],
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


            $data = [
                'code' => 'LTE-001',
                'data' => $rutaReporte
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
            $sql = "select *, rf.estado_factura as estado_factura_registro from facturacion.registro_factura rf 
                    inner join facturacion.factura f on rf.id_factura_registro_factura_fk = f.id_factura
                    inner join seguridad.usuario usu on f.id_usuario_factura_fk=usu.id_usuario 
                    where rf.codigo_registro_factura ='$codigo_registro_factura'";
            $r = $conexon->consultaComplejaNorAso($sql);
            if ($r['estado_factura'] == 'SINPAGAR')
            {
                $arregloFacturasPagar = json_decode($r['json_cargue_factura'],true);
                for ($i=0; $i<count($arregloFacturasPagar); $i++){
                    $codigoFactura = $arregloFacturasPagar[$i]['codigoFactura'];
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
                $data =[
                    'code'=>'LTE-001',
                    'data'=>$r
                ];
            }else{
                $data=[
                    'code'=>'LTE-000',
                    'status'=>'error',
                    'msg'=>'Lo sentimos, esta factura se encuentra en estado PAGADO',
                    'data'=>$r
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
    for($i = 0; $i<count($arregloFactura);$i++){
        $pdf->SetXY($X, $Y);
        $pdf->Image($img, '', '', 190, 80, '', '', 'T', false, 300, '', false,
            false, 0, false, false, true);
        $pdf->SetXY($X, $Y);
        $pdf->Cell(190, 80, '', 1, 0, '', 0);
        $pdf->SetXY($X, $Y);
        $pdf->write1DBarcode($arregloFactura[$i]['codigo_registro_factura'], 'C128', '', '', '', 12, 0.5, $style, 'N');
        $pdf->SetFont('times', 'B', 16);
        $pdf->SetTextColor(0, 115, 170);
        $pdf->SetXY($X, $Y);
        $pdf->Cell(190, 10, 'ASUACOR', 1, 0, 'C', 0);
        $pdf->SetX($X);
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 10);
        $pdf->SetFont('times', 'B', 11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(95, 5, 'Nombres: '.$arregloFactura[$i]['nombre'].' '.$arregloFactura[$i]['apellido'], 1, 0, '', 0);
        $pdf->SetX($pdf->GetX());
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Direccion: '.$arregloFactura[$i]['direccion'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Direccion envio: '.$arregloFactura[$i]['direccion_entrega'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Telefono: '.$arregloFactura[$i]['telefono'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Fecha de inicio: '.$arregloFactura[$i]['fecha_inicial'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Fecha de pago: '.$arregloFactura[$i]['fecha_pago'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Fecha de corte: '.$arregloFactura[$i]['fecha_corte'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Codigo medidor: '.$arregloFactura[$i]['codigo_medidor'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Numero tapa: '.$arregloFactura[$i]['numero_tapa_factura'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(95, 5, 'Total facturas cobradas: '.$arregloFactura[$i]['total_facturas_cobradas'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetX($pdf->GetX() + 95);
        $pdf->SetFont('times', 'B', 14);
        $pdf->SetTextColor(254, 92, 92);
        $pdf->Cell(95, 10, 'Total factura: '.$arregloFactura[$i]['total_pagar_factura'], 1, 0, '', 0);
        $pdf->SetY($pdf->GetY() + 8);
        $pdf->SetX($pdf->GetX() + 88);
        $pdf->SetFont('times', 'B', 14);
        $pdf->SetTextColor(254, 92, 92);
        $pdf->write1DBarcode($arregloFactura[$i]['codigo_registro_factura'], 'C128', '', '', '', 19, 1.0, $style, 'N');
//Pintar detalles de la factura
        $pdf->SetXY($X, $Y + 15);
        $pdf->SetFont('times', 'B', 8);
        $pdf->SetTextColor(0, 115, 170);
        $pdf->Cell(95, 5, 'Detalles', 1, 0, 'C', 0);
        $pdf->SetXY($X, $Y + 18);
        $contador = 0;
        $pdf->SetY($pdf->GetY() + 3);
        $pdf->Cell(25, 1, 'Producto', 0, 0, 'L', 0);
        $pdf->SetX($pdf->GetX());
        $pdf->Cell(15, 1, 'Total', 0, 0, 'L', 0);
        $pdf->SetX($pdf->GetX());
        $pdf->Cell(15, 1, 'codigo factura', 0, 0, 'L', 0);
        $pdf->SetTextColor(0, 0, 0);
        for ($n = 0; $n < count($arregloFactura[$i]['detalles']); $n++) {
            $codigoFactura = $arregloFactura[$i]['detalles'][$n]['codigoFactura'];
            for ($y = 0; $y<count($arregloFactura[$i]['detalles'][$n]['detalle']['tarifas']);$y++){
                $pdf->SetY($pdf->GetY() + 3);
                $pdf->Cell(25, 1, $arregloFactura[$i]['detalles'][$n]['detalle']['tarifas'][$y]['descripcion_tarifa'], 0, 0, 'L', 0);
                $pdf->SetX($pdf->GetX());
                $pdf->Cell(15, 1, $arregloFactura[$i]['detalles'][$n]['detalle']['tarifas'][$y]['total_tarifa'], 0, 0, 'L', 0);
                $pdf->SetX($pdf->GetX());
                $pdf->Cell(15, 1, $codigoFactura, 0, 0, 'L', 0);

            }
            if ($n%2==0){
                $pdf->SetTextColor(0, 115, 170);
            }else{
                $pdf->SetTextColor(0, 0, 0);
            }
        }
        $X = 10;
        $Y += 85;
        $contadorFacturas++;
        if ($contadorFacturas == 3){
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