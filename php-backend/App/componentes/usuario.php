<?php
$app->post('/seguridad/login', function () use ($app) {
    $json = $app->request->post('json', null);
    $helper = new helper();
    if ($json != null) {
        $parametros = json_decode($json);
        $correo = (isset($parametros->correo_usuario)) ? $parametros->correo_usuario : null;
        $contrasena = (isset($parametros->contrasena_usuario)) ? $parametros->contrasena_usuario : null;
        $getHash = (isset($parametros->has)) ? $parametros->has : false;
        $conexion = new conexPGSeguridad();
        $pwd = hash('SHA256', $contrasena);
        $sql = "select * from seguridad.usuario where correo_usuario='$correo' and contrasena_usuario='$pwd'";
        $r = $conexion->consultaComplejaNor($sql);
        if (pg_num_rows($r) > 0) {
            $usuario = pg_fetch_assoc($r);
            if ($usuario['contrasena_usuario'] != 'INACTIVO') {
                if ($correo == $usuario['correo_usuario'] && $pwd == $usuario['contrasena_usuario']) {
                    $jwtAuth = new jwtAuth();
                    $jwt = $jwtAuth->signIn($usuario, $getHash);
                    $data = [
                        'code' => 'LTE-001',
                        'data' => $jwt
                    ];
                } else {
                    $data = [
                        'code' => 'LTE-006'
                    ];
                }
            } else {
                $data = [
                    'code' => 'LTE-022'
                ];
            }
        } else {
            $data = [
                'code' => 'LTE-000',
                'status' => 'error',
                'msg' => 'Lo sentimos, no se encontraron resultados'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-009'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/seguridad/crearUsuario', function () use ($app) {
    $json = $app->request->post('json', null);
    $helper = new helper();
    if ($json != null) {
        $token = $app->request->post('token', null);
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $parametros = json_decode($json);
                $conexion = new conexPGSeguridad();
                $documento = (isset($parametros->documento_usuario)) ? $parametros->documento_usuario : null;
                $sql = "select * from seguridad.usuario where documento_usuario='$documento'";
                $r = $conexion->consultaComplejaAso($sql);
                if ($r == 0) {
                    $correo = (isset($parametros->correo_usuario)) ? $parametros->correo_usuario : null;
                    $sql = "select * from seguridad.usuario where correo_usuario='$correo'";
                    $r = $conexion->consultaComplejaAso($sql);
                    if ($r == 0) {
                        $nombre = (isset($parametros->nombre_usuario)) ? $parametros->nombre_usuario : null;
                        $apellido = (isset($parametros->apellido_usuario)) ? $parametros->apellido_usuario : null;
                        $contrasena = (isset($parametros->contrasena_usuario)) ? $parametros->contrasena_usuario : null;
                        $pwd = hash('SHA256', $documento);
                        $telefono = (isset($parametros->telefono_usuario)) ? $parametros->telefono_usuario : null;
                        $id_rol_fk = (isset($parametros->id_rol_fk_usuario)) ? $parametros->id_rol_fk_usuario : null;
                        $estado_usuario = (isset($parametros->estado_usuario)) ? $parametros->estado_usuario : null;
                        $direccion_usuario = (isset($parametros->direccion_usuario)) ? $parametros->direccion_usuario : null;
                        $fk_ramal_factura_usuario_id = (isset($parametros->fk_ramal_factura_usuario_id)) ? $parametros->fk_ramal_factura_usuario_id : null;
                        $fecha_creacion = date('Y-m-d H:i');
                        $fecha_actualizacion = date('Y-m-d H:i');
                        $sql = "INSERT INTO seguridad.usuario(
                         documento_usuario, nombre_usuario, apellido_usuario, 
                         telefono_usuario, correo_usuario, id_rol_fk_usuario, estado_usuario,contrasena_usuario, fecha_creacion_usuario, fecha_actualizacion_usuario,direccion_usuario,fk_ramal_factura_usuario_id)
                        VALUES ( '$documento', '$nombre', '$apellido', 
                        '$telefono', '$correo', '$id_rol_fk', '$estado_usuario','$pwd','$fecha_creacion','$fecha_actualizacion',
                        '$direccion_usuario','$fk_ramal_factura_usuario_id') returning id_usuario;";
                        $r=$conexion->consultaComplejaNorAso($sql);
                        $sql = "select * from configuracion.configuracion_factura";
                        $r3 = $conexion->consultaComplejaNorAso($sql);
                        if ($r3 == 0){
                            $sql = "INSERT INTO configuracion.configuracion_factura(
                                dias_recargo_factura, precio_recargo, recargo_factura, ruta_logo_factura)
                                VALUES ('0', '0', '0', 'imagenes_estandar/logo_factura.png') returning id_configuracion_factura;";
                        }
                        $r3 = $conexion->consultaComplejaNorAso($sql);
                        $id_configuracion_factura = $r3['id_configuracion_factura'];
                        $idUsuario = $r['id_usuario'];
                        $sql = "INSERT INTO facturacion.factura(
                             observacion_factura, codigo_medidor_factura, direccion_factura, numero_tapa_factura, 
                            id_usuario_factura_fk, id_configuracion_factura_fk, estado_factura,fk_ramal_factura_factura_id)
                            VALUES ('ASUACOR', 'No encontrado', '$direccion_usuario', 'No encontrado', '$idUsuario', '$id_configuracion_factura', 'ACTIVO','$fk_ramal_factura_usuario_id') returning id_factura;";
                        $r = $conexion->consultaComplejaNorAso($sql);
                        $id_factura = $r['id_factura'];
                        $sql = "INSERT INTO configuracion.tarifas(
                                descripcion_tarifa, total_tarifa, estado_tarifa, id_factura_tarifa_fk)
                                VALUES ('Tarifa fija', '12500', 'ACTIVO', '$id_factura');";
                        $conexion->consultaSimple($sql);
                        $data = [
                            'code' => 'LTE-001'
                        ];
                    } else {
                        $data = [
                            'code' => 'LTE-000',
                            'status' => 'error',
                            'msg' => 'Lo sentimos, ya existe un usuario con este correo: ' . $correo
                        ];
                    }
                } else {
                    $data = [
                        'code' => 'LTE-000',
                        'status' => 'error',
                        'msg' => 'Lo sentimos, ya existe un usuario con este documentos: ' . $documento
                    ];
                }
            } else {
                $data = [
                    'code' => 'LTE-013'
                ];
            }
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-009'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/seguridad/actualizarUsuario', function () use ($app) {
    $json = $app->request->post('json', null);
    $helper = new helper();
    if ($json != null) {
        $token = $app->request->post('token', null);
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $parametros = json_decode($json);
                $conexion = new conexPGSeguridad();
                $id_usuario = (isset($parametros->id_usuario)) ? $parametros->id_usuario : null;
                $sql = "select * from seguridad.usuario where id_usuario='$id_usuario'";
                $r1 = $conexion->consultaComplejaNorAso($sql);
                if ($r1 != 0) {
                    $documento = (isset($parametros->documento_usuario)) ? $parametros->documento_usuario : null;
                    if ($documento != $r1['documento_usuario']) {
                        $sql = "select * from seguridad.usuario where documento_usuario='$documento'";
                        $r = $conexion->consultaComplejaAso($sql);
                    } else {
                        $r = 0;
                    }
                    if ($r == 0) {
                        $correo = (isset($parametros->correo_usuario)) ? $parametros->correo_usuario : null;
                        if ($correo != $r1['correo_usuario']) {
                            $sql = "select * from seguridad.usuario where correo_usuario='$correo'";
                            $r = $conexion->consultaComplejaAso($sql);
                        } else {
                            $r = 0;
                        }
                        if ($r == 0) {
                            $nombre = (isset($parametros->nombre_usuario)) ? $parametros->nombre_usuario : null;
                            $apellido = (isset($parametros->apellido_usuario)) ? $parametros->apellido_usuario : null;
                            $contrasena = (isset($parametros->contrasena_usuario)) ? $parametros->contrasena_usuario : null;
                            $pwd = hash('SHA256', $documento);
                            $telefono = (isset($parametros->telefono_usuario)) ? $parametros->telefono_usuario : null;
                            $id_rol_fk = (isset($parametros->id_rol_fk_usuario)) ? $parametros->id_rol_fk_usuario : null;
                            $estado_usuario = (isset($parametros->estado_usuario)) ? $parametros->estado_usuario : null;
                            $fecha_creacion = date('Y-m-d H:i');
                            $fecha_actualizacion = date('Y-m-d H:i');
                            $sql = "UPDATE seguridad.usuario
                                    SET documento_usuario='$documento', nombre_usuario='$nombre', apellido_usuario='$apellido', telefono_usuario='$telefono', 
                                    correo_usuario='$correo', id_rol_fk_usuario='$id_rol_fk', estado_usuario='$estado_usuario', contrasena_usuario='$pwd', 
                                    fecha_actualizacion_usuario='$fecha_actualizacion'
                                    WHERE id_usuario='$id_usuario';";
                            $conexion->consultaSimple($sql);
                            $data = [
                                'code' => 'LTE-007'
                            ];
                        } else {
                            $data = [
                                'code' => 'LTE-000',
                                'status' => 'error',
                                'msg' => 'Lo sentimos, ya existe un usuario con este correo: ' . $correo
                            ];
                        }
                    }

                } else {
                    $data = [
                        'code' => 'LTE-000',
                        'status' => 'error',
                        'msg' => 'Lo sentimos, ya existe un usuario con este documentos: ' . $documento
                    ];
                }
            } else {
                $data = [
                    'code' => 'LTE-013'
                ];
            }
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-009'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/seguridad/listarUsuarios', function () use ($app) {
    $json = $app->request->post('json', null);
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $parametros = json_decode($json);
            $conexion = new conexPGSeguridad();
            $sql = "select usu.*, ro.descripcion_rol from seguridad.usuario usu inner join seguridad.rol ro on usu.id_rol_fk_usuario=ro.id_rol order by id_usuario asc";
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
    } else {
        $data = [
            'code' => 'LTE-013'
        ];
    }

    echo $helper->checkCode($data);
});
$app->post('/seguridad/CrearRol', function () use ($app) {
    $json = $app->request->post('json', null);
    $helper = new helper();
    if ($json != null) {
        $token = $app->request->post('token', null);
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $parametros = json_decode($json);
                $conexion = new conexPGSeguridad();
                $descripcion_rol = (isset($parametros->descripcion_rol)) ? $parametros->descripcion_rol : null;
                $estado_rol = (isset($parametros->estado_rol)) ? $parametros->estado_rol : null;
                $fecha_creacion = date('Y-m-d H:i');
                $fecha_actualizacion = date('Y-m-d H:i');
                $sql = "INSERT INTO seguridad.rol(
                        descripcion_rol, estado_rol, fecha_creacion_rol, fecha_actualizacion_rol)
                        VALUES ('$descripcion_rol', '$estado_rol', '$fecha_creacion', '$fecha_actualizacion');";
                $conexion->consultaSimple($sql);
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
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-009'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/seguridad/actualizarRol', function () use ($app) {
    $json = $app->request->post('json', null);
    $helper = new helper();
    if ($json != null) {
        $token = $app->request->post('token', null);
        if ($token != null) {
            $validacionUsuario = $helper->authCheck($token);
            if ($validacionUsuario == true) {
                $parametros = json_decode($json);
                $conexion = new conexPGSeguridad();
                $id_rol = (isset($parametros->id_rol)) ? $parametros->id_rol : null;
                $descripcion_rol = (isset($parametros->descripcion_rol)) ? $parametros->descripcion_rol : null;
                $estado_rol = (isset($parametros->estado_rol)) ? $parametros->estado_rol : null;
                $fecha_creacion = date('Y-m-d H:i');
                $fecha_actualizacion = date('Y-m-d H:i');
                $sql = "UPDATE seguridad.rol
                        SET descripcion_rol='$descripcion_rol', estado_rol='$estado_rol',
                         fecha_actualizacion_rol='$fecha_actualizacion'
                        WHERE id_rol='$id_rol'";
                $conexion->consultaSimple($sql);
                $data = [
                    'code' => 'LTE-007'
                ];
            } else {
                $data = [
                    'code' => 'LTE-013'
                ];
            }
        } else {
            $data = [
                'code' => 'LTE-013'
            ];
        }
    } else {
        $data = [
            'code' => 'LTE-009'
        ];
    }
    echo $helper->checkCode($data);
});
$app->post('/seguridad/listarRol', function () use ($app) {
    $json = $app->request->post('json', null);
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $parametros = json_decode($json);
            $conexion = new conexPGSeguridad();
            $sql = "SELECT id_rol, descripcion_rol, estado_rol, fecha_creacion_rol, fecha_actualizacion_rol
                    FROM seguridad.rol order by id_rol desc;";
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
    } else {
        $data = [
            'code' => 'LTE-013'
        ];
    }

    echo $helper->checkCode($data);
});
$app->post('/seguridad/filtroUsuario', function () use ($app) {
    $json = $app->request->post('json', null);
    $helper = new helper();
    $token = $app->request->post('token', null);
    if ($token != null) {
        $validacionUsuario = $helper->authCheck($token);
        if ($validacionUsuario == true) {
            $filtro = $app->request->post('filtro',null);
            $parametros = json_decode($json);
            $conexion = new conexPGSeguridad();
            $sql = "select usu.*, ro.descripcion_rol from seguridad.usuario usu 
                    inner join seguridad.rol ro on usu.id_rol_fk_usuario=ro.id_rol 
                    where usu.documento_usuario like '%$filtro%' or usu.nombre_usuario 
                    like '%$filtro%' or usu.apellido_usuario like '%$filtro%' 
                    order by id_usuario asc";
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
    } else {
        $data = [
            'code' => 'LTE-013'
        ];
    }

    echo $helper->checkCode($data);
});