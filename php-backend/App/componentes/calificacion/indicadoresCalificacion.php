<?php
$app->post('/calificacion/generarIndicadores', function () use ($app) { //Metodo para generar el reporte de indicadores de calificacion y premarcado
    $helper = new helper();
    $conexion = new conexMsql();
    $sql = "select upr.*,userr.name,userr.surname,userr.id, sum(od.quantity_calificado) as total_calificado, sum(od.quantity_premarcado) as total_premarcado, sum(od.quantity_order) as total_escaneo, 
        DATE_FORMAT(upr.created_at,'%Y-%m') as fecha_formateada from users_per_order upr 
        inner join (orders orde inner join (order_details od INNER JOIN products pro ON od.id_product = pro.id)ON orde.id = od.id_order)on upr.id_order=orde.id 
        inner join users userr on upr.id_user=userr.id
        where pro.sesion = 1 and upr.id_process_state IN (5,9,14) and DATE_FORMAT(upr.created_at,'%Y-%m') between '2018-06' and '2018-07'
        GROUP BY upr.id order by upr.id_user asc";
    $r = $conexion->consultaComplejaAso($sql);
    $arregloCompleto = array();
    $totalCalificadoGlobal = 0;
    $totalPremarcadoGlobal = 0;
    for ($i = 0; $i < count($r); $i++) {//Organizar arreglo para pintar el
        if ($r[$i]['id_process_state'] == '9') {//CALIFICACION
            $validacionEntradaMes = 0;
            for ($j = 0; $j < count($arregloCompleto); $j++) {
                if ($r[$i]['fecha_formateada'] == $arregloCompleto[$j]['fecha']) {
                    $validacionEntradaMes = 1;
                    $validacionEntradaUsuario = 0;
                    for ($n = 0; $n < count($arregloCompleto[$j]['data']); $n++) {
                        if ($r[$i]['id'] == $arregloCompleto[$j]['data'][$n]['id']) {
                            $arregloCompleto[$j]['data'][$n]['totalCalificado'] += $r[$i]['total_calificado'];

                            $validacionEntradaUsuario = 1;
                        }
                    }
                    $arregloCompleto[$j]['totalColegios'] += 1;
                    if ($validacionEntradaUsuario == 0) {
                        array_push($arregloCompleto[$j]['data'], [
                            'id' => $r[$i]['id'],
                            'nombre' => $r[$i]['name'],
                            'apellido' => $r[$i]['surname'],
                            'participacionPremarcado' => 0,
                            'participacionEscaneo' => 0,
                            'productividadCalificacion' => 0,
                            'productividadPremarcado' => 0,
                            'totalCalificado' => $r[$i]['total_calificado'],
                            'totalEscaneado' => 0,
                            'totalPremarcado' => 0
                        ]);
                    }

                }
            }
            if ($validacionEntradaMes == 0) {
                array_push($arregloCompleto, [
                    'fecha' => $r[$i]['fecha_formateada'],
                    'totalCalificado' => 0,
                    'totalPremarcado' => 0,
                    'totalEscaneado' => 0,
                    'totalColegios' => 1,
                    'capacidadInstaladaCalificacion' => 10000,
                    'capacidadInstaladaPremarcado' => 10000,
                    'productividadCalificacion' => 0,
                    'productividadPremarcado' => 0,
                    'diferenciaCalificacion' => 0,
                    'diferenciaPremarcado' => 0,
                    'diferenciaNegativaCalificacion' => 0,
                    'diferenciaNegativaPremarcado' => 0,
                    'costoPruebaCalificacion' => 2500,
                    'costoPruebaPremarcado' => 2500,
                    'valorTotalCalificaion' => 0,
                    'valorTotalPremarcado' => 0,
                    'data' => [[
                        'id' => $r[$i]['id'],
                        'nombre' => $r[$i]['name'],
                        'apellido' => $r[$i]['surname'],
                        'participacionPremarcado' => 0,
                        'participacionEscaneo' => 0,
                        'productividadCalificacion' => 0,
                        'productividadPremarcado' => 0,
                        'totalCalificado' => $r[$i]['total_calificado'],
                        'totalEscaneado' => 0,
                        'totalPremarcado' => 0]
                    ]
                ]);
            }
        } else if ($r[$i]['id_process_state'] == '5')//PREMARCADO
        {
            $validacionEntradaMes = 0;
            for ($j = 0; $j < count($arregloCompleto); $j++) {
                if ($r[$i]['fecha_formateada'] == $arregloCompleto[$j]['fecha']) {
                    $validacionEntradaMes = 1;
                    $validacionEntradaUsuario = 0;
                    for ($n = 0; $n < count($arregloCompleto[$j]['data']); $n++) {
                        if ($r[$i]['id'] == $arregloCompleto[$j]['data'][$n]['id']) {
                            $arregloCompleto[$j]['data'][$n]['totalPremarcado'] += $r[$i]['total_premarcado'];

                            $validacionEntradaUsuario = 1;
                        }
                    }
                    $arregloCompleto[$j]['totalColegios'] += 1;
                    if ($validacionEntradaUsuario == 0) {
                        array_push($arregloCompleto[$j]['data'], [
                            'id' => $r[$i]['id'],
                            'nombre' => $r[$i]['name'],
                            'apellido' => $r[$i]['surname'],
                            'participacionPremarcado' => 0,
                            'participacionEscaneo' => 0,
                            'productividadCalificacion' => 0,
                            'productividadPremarcado' => 0,
                            'totalCalificado' => 0,
                            'totalEscaneado' => 0,
                            'totalPremarcado' => $r[$i]['total_premarcado']
                        ]);
                    }

                }
            }
            if ($validacionEntradaMes == 0) {
                array_push($arregloCompleto, [
                    'fecha' => $r[$i]['fecha_formateada'],
                    'totalCalificado' => 0,
                    'totalPremarcado' => 0,
                    'totalEscaneado' => 0,
                    'totalColegios' => 1,
                    'capacidadInstaladaCalificacion' => 10000,
                    'capacidadInstaladaPremarcado' => 10000,
                    'productividadCalificacion' => 0,
                    'productividadPremarcado' => 0,
                    'diferenciaCalificacion' => 0,
                    'diferenciaPremarcado' => 0,
                    'diferenciaNegativaCalificacion' => 0,
                    'diferenciaNegativaPremarcado' => 0,
                    'costoPruebaCalificacion' => 2500,
                    'costoPruebaPremarcado' => 2500,
                    'valorTotalCalificaion' => 0,
                    'valorTotalPremarcado' => 0,
                    'data' => [[
                        'id' => $r[$i]['id'],
                        'nombre' => $r[$i]['name'],
                        'apellido' => $r[$i]['surname'],
                        'participacionPremarcado' => 0,
                        'participacionEscaneo' => 0,
                        'productividadCalificacion' => 0,
                        'productividadPremarcado' => 0,
                        'totalCalificado' => 0,
                        'totalEscaneado' => 0,
                        'totalPremarcado' => $r[$i]['total_premarcado']]
                    ]
                ]);
            }
        } else//Escaneo
        {
            $validacionEntradaMes = 0;
            for ($j = 0; $j < count($arregloCompleto); $j++) {
                if ($r[$i]['fecha_formateada'] == $arregloCompleto[$j]['fecha']) {
                    $validacionEntradaMes = 1;
                    $validacionEntradaUsuario = 0;
                    for ($n = 0; $n < count($arregloCompleto[$j]['data']); $n++) {
                        if ($r[$i]['id'] == $arregloCompleto[$j]['data'][$n]['id']) {
                            $arregloCompleto[$j]['data'][$n]['totalEscaneado'] += $r[$i]['total_escaneo'];

                            $validacionEntradaUsuario = 1;
                        }
                    }
                    $arregloCompleto[$j]['totalColegios'] += 1;
                    if ($validacionEntradaUsuario == 0) {
                        array_push($arregloCompleto[$j]['data'], [
                            'id' => $r[$i]['id'],
                            'nombre' => $r[$i]['name'],
                            'apellido' => $r[$i]['surname'],
                            'participacionPremarcado' => 0,
                            'participacionEscaneo' => 0,
                            'productividadCalificacion' => 0,
                            'productividadPremarcado' => 0,
                            'totalCalificado' => 0,
                            'totalEscaneado' => $r[$i]['total_escaneo'],
                            'totalPremarcado' => 0
                        ]);
                    }

                }
            }
            if ($validacionEntradaMes == 0) {
                array_push($arregloCompleto, [
                    'fecha' => $r[$i]['fecha_formateada'],
                    'totalCalificado' => 0,
                    'totalPremarcado' => 0,
                    'totalEscaneado' => 0,
                    'totalColegios' => 1,
                    'capacidadInstaladaCalificacion' => 10000,
                    'capacidadInstaladaPremarcado' => 10000,
                    'productividadCalificacion' => 0,
                    'productividadPremarcado' => 0,
                    'diferenciaCalificacion' => 0,
                    'diferenciaPremarcado' => 0,
                    'diferenciaNegativaCalificacion' => 0,
                    'diferenciaNegativaPremarcado' => 0,
                    'costoPruebaCalificacion' => 2500,
                    'costoPruebaPremarcado' => 2500,
                    'valorTotalCalificaion' => 0,
                    'valorTotalPremarcado' => 0,
                    'data' => [[
                        'id' => $r[$i]['id'],
                        'nombre' => $r[$i]['name'],
                        'apellido' => $r[$i]['surname'],
                        'participacionPremarcado' => 0,
                        'participacionEscaneo' => 0,
                        'productividadCalificacion' => 0,
                        'productividadPremarcado' => 0,
                        'totalCalificado' => 0,
                        'totalEscaneado' => $r[$i]['total_escaneo'],
                        'totalPremarcado' => 0]
                    ]
                ]);
            }
        }
    }
    for ($i = 0; $i < count($arregloCompleto); $i++) {//Incializar la capcidad instalada calcular los demas valores necesarios
        $arregloFecha = explode('-', $arregloCompleto[$i]['fecha']);
        $sql = "select * from capacidad_instalada ci where ci.anodesc like '$arregloFecha[0]' and ci.mesdesc like '$arregloFecha[1]' 
         and (ci.departamento like 'CALIFICACION' or ci.departamento like 'PREMARCADO') and ci.state like 'ACTIVO'";
        $r = $conexion->consultaComplejaAso($sql);
        $validacionEntradaCalificacion = 0;
        $posicionCalificacion = null;
        $validacionEntradaPremarcado = 0;
        $posicionPremarcado = null;
        for ($j = 0; $j < count($r); $j++) {
            if ($r[$j]['departamento'] == 'PREMARCADO') {
                $validacionEntradaPremarcado = 1;
                $posicionPremarcado = $j;

            } else if ($r[$j]['departamento'] == 'CALIFICACION') {
                $validacionEntradaCalificacion = 1;
                $posicionCalificacion = $j;
            }
        }
        if ($validacionEntradaCalificacion == 1 && $posicionCalificacion != null) {
            $arregloCompleto[$i]['capacidadInstaladaCalificacion'] = $r[$posicionCalificacion]['cantidad'];
        } else {
            $arregloCompleto[$i]['capacidadInstaladaCalificacion'] = 10000;
        }
        if ($validacionEntradaPremarcado == 1) {
            $arregloCompleto[$i]['capacidadInstaladaPremarcado'] = $r[$posicionPremarcado]['cantidad'];
        } else {
            $arregloCompleto[$i]['capacidadInstaladaPremarcado'] = 10000;
        }

        $totalPremarcado = 0;
        $totalCalificacion = 0;
        $totalEscaneado = 0;
        for ($n = 0; $n < count($arregloCompleto[$i]['data']); $n++) {
            $totalPremarcado += $arregloCompleto[$i]['data'][$n]['totalPremarcado'];
            $totalCalificacion += $arregloCompleto[$i]['data'][$n]['totalCalificado'];
            $totalEscaneado += $arregloCompleto[$i]['data'][$n]['totalEscaneado'];
        }
        $arregloCompleto[$i]['totalCalificado'] = $totalCalificacion;
        $arregloCompleto[$i]['totalPremarcado'] = $totalPremarcado;
        $arregloCompleto[$i]['totalEscaneado'] = $totalEscaneado;
        //Cacular productividad
        $arregloCompleto[$i]['productividadCalificacion'] = ($arregloCompleto[$i]['totalCalificado'] * 100) / ($arregloCompleto[$i]['capacidadInstaladaCalificacion'] * 10);
        $arregloCompleto[$i]['productividadPremarcado'] = ($arregloCompleto[$i]['totalPremarcado'] * 100) / ($arregloCompleto[$i]['capacidadInstaladaPremarcado'] * 10);
        //Calcular Diferencia
        $arregloCompleto[$i]['diferenciaCalificacion'] = $arregloCompleto[$i]['productividadCalificacion'] - 100;
        $arregloCompleto[$i]['diferenciaPremarcado'] = $arregloCompleto[$i]['productividadPremarcado'] - 100;
        //Calcular diferencias negativa
        $arregloCompleto[$i]['diferenciaNegativaCalificacion'] = $arregloCompleto[$i]['totalCalificado'] - ($arregloCompleto[$i]['capacidadInstaladaCalificacion'] * 10);
        $arregloCompleto[$i]['diferenciaNegativaPremarcado'] = $arregloCompleto[$i]['totalPremarcado'] - ($arregloCompleto[$i]['capacidadInstaladaPremarcado'] * 10);
        // valor total
        $arregloCompleto[$i]['valorTotalCalificaion'] = $arregloCompleto[$i]['diferenciaNegativaCalificacion'] * $arregloCompleto[$i]['costoPruebaCalificacion'];
        $arregloCompleto[$i]['valorTotalPremarcado'] = $arregloCompleto[$i]['diferenciaNegativaPremarcado'] * $arregloCompleto[$i]['costoPruebaPremarcado'];


    }
    $totalCapacidadCalificacion = 0;
    $totalCapacidadPremarcado = 0;
    $totalCalificado = 0;
    $totalPremarcado = 0;
    $totalProductividadCalificacion = 0;
    $totalProductividadPremarcado = 0;
    $totalDiferenciaPremarcado = 0;
    $totalDiferenciaCalificacion = 0;
    $valorTotalCalificacion = 0;
    $valorTotalPremarcado = 0;
    for ($i = 0; $i < count($arregloCompleto); $i++) {
        for ($j = 0; $j < count($arregloCompleto[$i]['data']); $j++) {
            if ($arregloCompleto[$i]['totalPremarcado'] != 0) {
                $arregloCompleto[$i]['data'][$j]['participacionPremarcado'] = ($arregloCompleto[$i]['data'][$j]['totalPremarcado'] * 100) / $arregloCompleto[$i]['totalPremarcado'];
            }

            if ($arregloCompleto[$i]['totalEscaneado'] != 0) {
                $arregloCompleto[$i]['data'][$j]['participacionEscaneo'] = ($arregloCompleto[$i]['data'][$j]['totalEscaneado'] * 100) / $arregloCompleto[$i]['totalEscaneado'];
            }
            if ($arregloCompleto[$i]['capacidadInstaladaCalificacion'] != 0) {
                $arregloCompleto[$i]['data'][$j]['productividadCalificacion'] = ($arregloCompleto[$i]['data'][$j]['totalCalificado'] * 100) / $arregloCompleto[$i]['capacidadInstaladaCalificacion'];
            }
            if ($arregloCompleto[$i]['capacidadInstaladaPremarcado'] != 0) {
                $arregloCompleto[$i]['data'][$j]['productividadPremarcado'] = ($arregloCompleto[$i]['data'][$j]['totalPremarcado'] * 100) / $arregloCompleto[$i]['capacidadInstaladaPremarcado'];
            }

        }
        $totalCapacidadCalificacion += $arregloCompleto[$i]['capacidadInstaladaCalificacion'] * 10;
        $totalCapacidadPremarcado += $arregloCompleto[$i]['capacidadInstaladaPremarcado'];
        $totalCalificado += $arregloCompleto[$i]['totalCalificado'];
        $totalPremarcado += $arregloCompleto[$i]['totalPremarcado'];
        $totalProductividadCalificacion += $arregloCompleto[$i]['productividadCalificacion'];
        $totalProductividadPremarcado += $arregloCompleto[$i]['productividadPremarcado'];
        $totalDiferenciaPremarcado += $arregloCompleto[$i]['diferenciaPremarcado'];
        $totalDiferenciaCalificacion += $arregloCompleto[$i]['diferenciaCalificacion'];
        $valorTotalCalificacion += $arregloCompleto[$i]['valorTotalCalificaion'];
        $valorTotalPremarcado += $arregloCompleto[$i]['valorTotalPremarcado'];
    }
    $data = [
        'code' => 'LTE-001',
        'data' => ['totalCapacidadCalificada' => $totalCapacidadCalificacion,
            'total capacidad premarcado' => $totalCapacidadPremarcado,
            'total calificacion' => $totalCalificado,
            'total premarcado' => $totalPremarcado,
            'total productividad calificacion' => $totalProductividadCalificacion,
            'total productividad premarcado' => $totalProductividadPremarcado,
            'total diferencia premarcado' => $totalDiferenciaPremarcado,
            'total diferencia calificacion' => $totalDiferenciaCalificacion,
            'valor total calificacion' => $valorTotalCalificacion,
            'valor total premarcado' => $valorTotalPremarcado,
            'data' => $arregloCompleto]
    ];
    generarReporteExcel($arregloCompleto, $totalCapacidadCalificacion, $totalCapacidadPremarcado, $totalCalificado, $totalPremarcado, $totalProductividadCalificacion, $totalProductividadPremarcado,
        $totalDiferenciaPremarcado, $totalDiferenciaCalificacion, $valorTotalCalificacion, $valorTotalPremarcado);
    echo $helper->checkCode($data);
});

function generarReporteExcel($arregloCompleto, $totalCapacidadCalificacionF, $totalCapacidadPremarcadoF, $totalCalificadoF, $totalPremarcadoF, $totalProductividadCalificacionF, $totalProductividadPremarcadoF,
                             $totalDiferenciaPremarcadoF, $totalDiferenciaCalificacionF, $valorTotalCalificacionF, $valorTotalPremarcadoF)
{//Funcion para generar el reporte en excel el cual retornara la ruta del mismo
    $libro = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    //Reporte de indicadores I1
    $libro->getActiveSheet()->mergeCells('A1:E1');
    $hoja = $libro->getActiveSheet();
    $hoja->setTitle('I1');
    $hoja->setCellValue('A1', 'Capacidad instalada calificacion');
    $hoja->setCellValue('A2', 'Fecha');
    $hoja->setCellValue('B2', 'Capacidad');
    $hoja->setCellValue('C2', 'Calificado');
    $hoja->setCellValue('D2', 'Productividad');
    $hoja->setCellValue('E2', 'Diferencia');
    $hoja->setCellValue('G2', 'Diferencia negativa');
    $hoja->setCellValue('H2', 'Costo prueba');
    $hoja->setCellValue('I2', 'Valor total');
    $iterador = 3;
    $iteradorPremarcado = count($arregloCompleto) + 6;
    $libro->getActiveSheet()->mergeCells('A' . ($iteradorPremarcado - 1) . ':' . 'E' . ($iteradorPremarcado - 1));
    $hoja->setCellValue('A' . ($iteradorPremarcado - 1), 'Capacidad instalada Premarcado');
    $hoja->setCellValue('A' . $iteradorPremarcado, 'Fecha');
    $hoja->setCellValue('B' . $iteradorPremarcado, 'Capacidad');
    $hoja->setCellValue('C' . $iteradorPremarcado, 'Calificado');
    $hoja->setCellValue('D' . $iteradorPremarcado, 'Productividad');
    $hoja->setCellValue('E' . $iteradorPremarcado, 'Diferencia');
    $hoja->setCellValue('G' . $iteradorPremarcado, 'Diferencia negativa');
    $hoja->setCellValue('H' . $iteradorPremarcado, 'Costo prueba');
    $hoja->setCellValue('I' . $iteradorPremarcado, 'Valor total');
    $iteradorPremarcado++;
    for ($i = 0; $i < count($arregloCompleto); $i++) {
        $hoja->setCellValue('A' . $iterador, $arregloCompleto[$i]['fecha']);
        $libro->getSheet(1)->getColumnDimension('A'. $iterador)->setAutoSize(true);
        $hoja->setCellValue('B' . $iterador, $arregloCompleto[$i]['capacidadInstaladaCalificacion'] * 10);
        $libro->getSheet(1)->getColumnDimension('B'. $iterador)->setAutoSize(true);
        $hoja->setCellValue('C' . $iterador, $arregloCompleto[$i]['totalCalificado']);
        $libro->getSheet(1)->getColumnDimension('C'. $iterador)->setAutoSize(true);
        $hoja->setCellValue('D' . $iterador, $arregloCompleto[$i]['productividadCalificacion']);
        $libro->getSheet(1)->getColumnDimension('D'. $iterador)->setAutoSize(true);
        $hoja->setCellValue('E' . $iterador, $arregloCompleto[$i]['diferenciaCalificacion']);
        $libro->getSheet(1)->getColumnDimension('E'. $iterador)->setAutoSize(true);
        $hoja->setCellValue('G' . $iterador, $arregloCompleto[$i]['diferenciaNegativaCalificacion']);
        $libro->getSheet(1)->getColumnDimension('G'. $iterador)->setAutoSize(true);
        $hoja->setCellValue('H' . $iterador, $arregloCompleto[$i]['costoPruebaCalificacion']);
        $libro->getSheet(1)->getColumnDimension('H'. $iterador)->setAutoSize(true);
        $hoja->setCellValue('I' . $iterador, $arregloCompleto[$i]['valorTotalCalificaion']);
        $libro->getSheet(1)->getColumnDimension('I'. $iterador)->setAutoSize(true);

        $hoja->setCellValue('A' . $iteradorPremarcado, $arregloCompleto[$i]['fecha']);
        $hoja->setCellValue('B' . $iteradorPremarcado, $arregloCompleto[$i]['capacidadInstaladaPremarcado'] * 10);
        $hoja->setCellValue('C' . $iteradorPremarcado, $arregloCompleto[$i]['totalPremarcado']);
        $hoja->setCellValue('D' . $iteradorPremarcado, $arregloCompleto[$i]['productividadPremarcado']);
        $hoja->setCellValue('E' . $iteradorPremarcado, $arregloCompleto[$i]['diferenciaPremarcado']);
        $hoja->setCellValue('G' . $iteradorPremarcado, $arregloCompleto[$i]['diferenciaNegativaPremarcado']);
        $hoja->setCellValue('H' . $iteradorPremarcado, $arregloCompleto[$i]['costoPruebaCalificacion']);
        $hoja->setCellValue('I' . $iteradorPremarcado, $arregloCompleto[$i]['valorTotalPremarcado']);
        $iterador++;
        $iteradorPremarcado++;
    }
    //Totalizado calificacion
    $hoja->setCellValue('A' . $iterador, 'Total');
    $hoja->setCellValue('B' . $iterador, $totalCapacidadCalificacionF);
    $hoja->setCellValue('C' . $iterador, $totalCalificadoF);
    $hoja->setCellValue('D' . $iterador, $totalProductividadCalificacionF);
    $hoja->setCellValue('E' . $iterador, $totalDiferenciaCalificacionF);
    $hoja->setCellValue('I' . $iterador, $valorTotalCalificacionF);
    //totalizado premarcado
    $hoja->setCellValue('A' . $iteradorPremarcado, 'Total');
    $hoja->setCellValue('B' . $iteradorPremarcado, $totalCapacidadPremarcadoF);
    $hoja->setCellValue('C' . $iteradorPremarcado, $totalPremarcadoF);
    $hoja->setCellValue('D' . $iteradorPremarcado, $totalProductividadPremarcadoF);
    $hoja->setCellValue('E' . $iteradorPremarcado, $totalDiferenciaPremarcadoF);
    $hoja->setCellValue('I' . $iteradorPremarcado, $valorTotalPremarcadoF);
    //Indicador 2
    $libro->createSheet();
    $libro->getSheet(1)->mergeCells('A1:O1');
    $hoja= $libro->getSheet(1);
    $hoja->setTitle('I2');
    $hoja->setCellValue('A1','TRAZABILIDAD COLABORADORES MES A MES');
    //Cabezera tabla
    $arregloLetras = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF',
        'AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ'];
    $contador=5;

    for($i = 0; $i<count($arregloCompleto);$i++){
        $libro->getSheet(1)->mergeCells('B'.($contador-2).':'.'C'.($contador-2));
        $hoja->setCellValue('B'.($contador-2),$arregloCompleto[$i]['fecha']);
        $hoja->setCellValue('B'.($contador-1),'Total calificacion');
        $hoja->setCellValue('C'.($contador-1),'Productividad calificacion');
        $hoja->setCellValue('D'.($contador-1),'Total premarcado');
        $hoja->setCellValue('E'.($contador-1),'Productividad premarcado');
        for ($j=0; $j<count($arregloCompleto[$i]['data']); $j++){
            $hoja->setCellValue('A'.$contador,$arregloCompleto[$i]['data'][$j]['nombre']);
            $hoja->setCellValue('B'.$contador,$arregloCompleto[$i]['data'][$j]['totalCalificado']);
            $hoja->setCellValue('C'.$contador,$arregloCompleto[$i]['data'][$j]['productividadCalificacion']);
            $hoja->setCellValue('D'.$contador,$arregloCompleto[$i]['data'][$j]['totalPremarcado']);
            $hoja->setCellValue('E'.$contador,$arregloCompleto[$i]['data'][$j]['productividadPremarcado']);
            $contador++;
        }
        $libro->getSheet(1)->getStyle('A1000:H1000')
            ->getAlignment()->setWrapText(true);
        $hoja->setCellValue('A'.$contador,'Total calificado');
        $libro->getSheet(1)->getStyle('A'.$contador)
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
        $hoja->setCellValue('B'.$contador,$arregloCompleto[$i]['totalCalificado']);
        $hoja->setCellValue('A'.($contador+1),'Total premarcado');
        $hoja->setCellValue('D'.($contador+1),$arregloCompleto[$i]['totalPremarcado']);
        $contador=$contador+6;

    }


    $excel = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($libro);
    $excel->save('./App/public/excel/hola.xlsx');
}