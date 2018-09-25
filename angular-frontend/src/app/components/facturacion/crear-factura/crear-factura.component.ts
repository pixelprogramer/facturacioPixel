import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {UsuarioService} from "../../../services/usuario.service";
import {Usuario} from "../../../models/seguridad/usuario";
import {Factura} from "../../../models/factura/factura";
import {FacturaService} from "../../../services/factura/factura.service";
import swal from "sweetalert2";
import {Tarifas} from "../../../models/factura/tarifas";
import {Ramal_factura} from "../../../models/factura/ramal_factura";

@Component({
  selector: 'app-crear-factura',
  templateUrl: './crear-factura.component.html',
  styleUrls: ['./crear-factura.component.scss'],
  providers: [ElementsService, UsuarioService, FacturaService]
})
export class CrearFacturaComponent implements OnInit {
  public listUsuario: Array<any>;
  public token: any;
  public seleccionUsuario: Usuario;
  public objFactura: Factura;
  public listFactura = [];
  public objTarifaFactura: Tarifas;
  public listTarifasFactura: Array<Tarifas>;
  position = "top-right";
  public codigoFiltro: any;
  public filtro: any;
  public listRamales: Array<Ramal_factura>;

  constructor(private _ElementService: ElementsService,
              private _UsuarioService: UsuarioService,
              private _FacturaService: FacturaService) {
    this.seleccionUsuario = new Usuario('', '', '',
      '', '', '', '',
      '', '', '', '', '', '', '');
    this.objFactura = new Factura('', 'ASUACOR', '',
      '', '', '', '', '000', '');
    this.objTarifaFactura = new Tarifas('', '', '', '000', '');
    this.codigoFiltro = '';
    this.filtro = '';
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CrearFacturaComponent');
    this.token = localStorage.getItem('token');
    this.listarUsuarios();
    this.listarRamales();
    $("#seccionFactura").hide();
    $("#idSeccionTarifa").hide();
  }

  listarUsuarios() {
    $("#loaderTablaMenu").show();
    this._UsuarioService.listarUsuarioSeguridad(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listUsuario = respuesta.data;
            $("#loaderTablaMenu").hide();
          } else {
            this.listUsuario = [];
            $("#loaderTablaMenu").hide();
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    );

  }

  cargarSeccionFactura() {
    $("#seccionUsuario").toggle(500);
    $("#seccionFactura").toggle(600);
    this.listarFactura();
  }

  seleccionarUsuario(usuario) {
    this.seleccionUsuario = usuario;
    this.objFactura.id_usuario_factura_fk = this.seleccionUsuario.id_usuario;
  }


//####################################Seccion factura
  listarFactura() {
    this._FacturaService.listarFacturasUsuario(this.token, this.seleccionUsuario.id_usuario).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listFactura = respuesta.data;
          } else {
            this.listFactura = [];
            this._ElementService.pi_poVentanaAlertaWarning('PIXEL-000', 'No se encontraron facturas');
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning('PIXEL-000', respuesta.msg);
        }
      }, error2 => {

      }
    )
  }

  seleccionarFactura(factura) {
    this.objFactura = factura;
    this.objTarifaFactura.id_factura_tarifa_fk = this.objFactura.id_factura;
    this.listarTarifasFactura();
  }

  crearFactura() {
    this._ElementService.pi_poBontonDesabilitar('#btnCrearFactura');
    if (this.objFactura.observacion_factura != '') {
      if (this.objFactura.estado_factura != '000') {
        if (this.objFactura.codigo_medidor_factura != '') {
          if (this.objFactura.direccion_factura != '') {
            if (this.objFactura.fk_ramal_factura_factura_id != '') {
              if (this.objFactura.estado_factura == 'CANCELADO') {
                swal({
                  title: 'Esta seguro de crear la factura en estado cancelado?',
                  text: 'Si es afirmativa su respuesta, esta factura no va ser tomada al momento de de generar el reporte de facturas.',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Si, lo estoy'
                }).then((result) => {
                  $("#loaderUsuario").show();
                  if (result.value) {
                    this.metodoCrearFactura();
                  } else {
                    this._ElementService.pi_poBotonHabilitar('#btnCrearFactura');
                  }
                });
              } else {
                this.metodoCrearFactura();
              }
            } else {
              this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentismo, el ramal es requerido');
              this._ElementService.pi_poBotonHabilitar('#btnCrearFactura');
            }
          } else {
            this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentismo, la direccion de envio es requerida');
            this._ElementService.pi_poBotonHabilitar('#btnCrearFactura');
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentimos, el codigo del medidor no es necesario');
          this._ElementService.pi_poBotonHabilitar('#btnCrearFactura');
        }
      } else {
        this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentimos, no se ha seleccionado el estado de la factura');
        this._ElementService.pi_poBotonHabilitar('#btnCrearFactura');
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('PIXEL-000', 'Lo sentimos, la observacion es requerida');
      this._ElementService.pi_poBotonHabilitar('#btnCrearFactura');
    }
  }

  actualizarFactura() {
    this._ElementService.pi_poBontonDesabilitar('#btnActualizarFactura');
    if (this.objFactura.observacion_factura != '') {
      if (this.objFactura.estado_factura != '000') {
        if (this.objFactura.codigo_medidor_factura != '') {
          if (this.objFactura.direccion_factura != '') {
            if (this.objFactura.fk_ramal_factura_factura_id != '') {
              if (this.objFactura.estado_factura == 'CANCELADO') {
                swal({
                  title: 'Esta seguro de crear la factura en estado cancelado?',
                  text: 'Si es afirmativa su respuesta, esta factura no va ser tomada al momento de de generar el reporte de facturas.',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Si, lo estoy'
                }).then((result) => {
                  $("#loaderUsuario").show();
                  if (result.value) {
                    this.metodoActualizarFactura();
                    this._ElementService.pi_poBotonHabilitar('#btnActualizarFactura');
                  } else {
                    this._ElementService.pi_poBotonHabilitar('#btnActualizarFactura');
                  }
                });
              } else {
                this.metodoActualizarFactura();
                this._ElementService.pi_poBotonHabilitar('#btnActualizarFactura');
              }
            } else {
              this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentismo, El ramal es requerido');
              this._ElementService.pi_poBotonHabilitar('#btnActualizarFactura');
            }
          } else {
            this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentismo, la direccion de envio es requerida');
            this._ElementService.pi_poBotonHabilitar('#btnActualizarFactura');
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentimos, el codigo del medidor no es necesario');
          this._ElementService.pi_poBotonHabilitar('#btnActualizarFactura');
        }
      } else {
        this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentimos, no se ha seleccionado el estado de la factura');
        this._ElementService.pi_poBotonHabilitar('#btnActualizarFactura');
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('PIXEL-000', 'Lo sentimos, la observacion es requerida');
      this._ElementService.pi_poBotonHabilitar('#btnActualizarFactura');
    }
  }

  metodoActualizarFactura() {
    this._FacturaService.actualizarFactura(this.token, this.objFactura).subscribe(
      respuesta => {
        console.log(respuesta);
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, 'PIXEL');
          this.listarFactura();
          this.limpiarCampos();
          this._ElementService.pi_poBotonHabilitar('#btnCrearFactura');
        } else {
          this._ElementService.pi_poVentanaAlertaWarning('PIXEL', respuesta.msg);
        }
      }, error => {

      }
    )
  }

  metodoCrearFactura() {
    this._FacturaService.crearFactura(this.token, this.objFactura).subscribe(
      respuesta => {
        console.log(respuesta);
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, 'PIXEL');
          this.listarFactura();
          this.limpiarCampos();
          this._ElementService.pi_poBotonHabilitar('#btnCrearFactura');
        } else {
          this._ElementService.pi_poVentanaAlertaWarning('PIXEL', respuesta.msg);
        }
      }, error => {

      }
    )
  }

  limpiarCampos() {
    this.objFactura = new Factura('', 'ASUACOR', '',
      '', '', '', '', '000', '');
  }

  cargarSeccionTarifaFactura() {
    $("#seccionFactura").toggle(600);
    $("#idSeccionTarifa").toggle(500);
    this.listarTarifasFactura();
  }

  //####################################Seccion tarifa
  listarTarifasFactura() {
    this._FacturaService.listarTarifasFactura(this.token, this.objFactura.id_factura).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listTarifasFactura = respuesta.data;
          } else {
            this.listTarifasFactura = [];
          }

        } else {
          this.listTarifasFactura = [];
        }
      }, error2 => {

      }
    )
  }

  crearTarifa() {
    this._ElementService.pi_poBontonDesabilitar('#btnCrearTarifa');
    if (this.objTarifaFactura.descripcion_tarifa != '') {
      if (this.objTarifaFactura.total_tarifa != '') {
        if (this.objTarifaFactura.estado_tarifa != '000') {
          this._FacturaService.crearTarifa(this.token, this.objTarifaFactura).subscribe(
            respuesta => {
              this._ElementService.pi_poValidarCodigo(respuesta);
              if (respuesta.status == 'success') {
                this._ElementService.pi_poAlertaSuccess(respuesta.msg, 'PIXEL');
                this.listarTarifasFactura();
                this._ElementService.pi_poBotonHabilitar('#btnCrearTarifa');
                this.limpiarCamposTarifa();
              } else {
                this._ElementService.pi_poVentanaAlertaWarning('PIXEL', respuesta.msg);
                this._ElementService.pi_poBotonHabilitar('#btnCrearTarifa');
              }
            }, error2 => {

            }
          )
        } else {
          this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentimos, Seleccione un estado de la tarifa');
          this._ElementService.pi_poBotonHabilitar('#btnCrearTarifa');
        }
      } else {
        this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentimos, el total de la tarifa es requerida');
        this._ElementService.pi_poBotonHabilitar('#btnCrearTarifa');
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentimos, la descripcion de la tarifa es requerida');
      this._ElementService.pi_poBotonHabilitar('#btnCrearTarifa');
    }
  }

  actualizarTarifa() {
    this._ElementService.pi_poBontonDesabilitar('#btnActualizarTarifa');
    if (this.objTarifaFactura.id_tarifa != '') {
      if (this.objTarifaFactura.descripcion_tarifa != '') {
        if (this.objTarifaFactura.total_tarifa != '') {
          if (this.objTarifaFactura.estado_tarifa != '000') {
            this._FacturaService.actualizarTarifa(this.token, this.objTarifaFactura).subscribe(
              respuesta => {
                this._ElementService.pi_poValidarCodigo(respuesta);
                if (respuesta.status == 'success') {
                  this._ElementService.pi_poAlertaSuccess(respuesta.msg, 'PIXEL');
                  this.listarTarifasFactura();
                  this._ElementService.pi_poBotonHabilitar('#btnActualizarTarifa');
                  this.limpiarCamposTarifa()
                } else {
                  this._ElementService.pi_poVentanaAlertaWarning('PIXEL', respuesta.msg);
                  this._ElementService.pi_poBotonHabilitar('#btnActualizarTarifa');
                }
              }, error2 => {

              }
            )
          } else {
            this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentimos, Seleccione un estado de la tarifa');
            this._ElementService.pi_poBotonHabilitar('#btnActualizarTarifa');
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentimos, el total de la tarifa es requerida');
          this._ElementService.pi_poBotonHabilitar('#btnActualizarTarifa');
        }
      } else {
        this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentimos, la descripcion de la tarifa es requerida');
        this._ElementService.pi_poBotonHabilitar('#btnActualizarTarifa');
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Seleccione una tarifa para poder actualizar sus datos');
      this._ElementService.pi_poBotonHabilitar('#btnActualizarTarifa');
    }

  }

  limpiarCamposTarifa() {
    this.objTarifaFactura = new Tarifas('', '', '', '000', '');
  }

  seleccionarTarifa(tarifa) {
    this.objTarifaFactura = tarifa;
    this._ElementService.pi_poAlertaMensaje('Se selecciono la tarifa con codigo: ' + this.objTarifaFactura.id_tarifa, 'PIXEL');
  }

  regresarPedido() {
    $("#idSeccionTarifa").toggle(600);
    $("#seccionFactura").toggle(500);
  }

  regresarUsuario() {
    $("#seccionFactura").toggle(500);
    $("#seccionUsuario").toggle(600);
    this.seleccionUsuario = new Usuario('', '', '',
      '', '', '', '',
      '', '', '', '', '', '', '');
    this.objFactura = new Factura('', 'ASUACOR', '',
      '', '', '', '', '000', '');
    this.objTarifaFactura = new Tarifas('', '', '', '000', '');
    this.codigoFiltro = '';
    this.filtro = '';
  }

  filtrarUsuario() {
    if (this.filtro.trim() != '') {
      this._UsuarioService.filtroUsuario(this.token, this.filtro).subscribe(
        respuesta => {
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success') {
            if (respuesta.data != 0) {
              this.listUsuario = respuesta.data;
              this.filtro = '';
            } else {
              this._ElementService.pi_poAlertaError('No se encontraron resultados', 'PIXEL');
              this.listarUsuarios();
            }
          } else {
            this._ElementService.pi_poVentanaAlertaWarning('PIXEL', respuesta.msg);
          }

        }, error2 => {

        }
      )
    } else {
      this._ElementService.pi_poAlertaWarning('El campo filtro es requerido', 'PIXEL');
      this.listarUsuarios();
    }

  }

  listarRamales() {
    $("#loaderTablaMenu").show();
    this._FacturaService.listarRamales(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listRamales = respuesta.data;
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
          $("#loaderTablaMenu").hide();
        } else {
          $("#loaderTablaMenu").hide();
        }
      }, error2 => {

      }
    )
  }
}


