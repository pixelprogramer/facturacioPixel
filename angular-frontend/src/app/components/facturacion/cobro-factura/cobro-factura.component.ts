import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {Registro_factura} from "../../../models/factura/registro_factura";
import {FacturaService} from "../../../services/factura/factura.service";
import swal from "sweetalert2";
import {Abonos_factura} from "../../../models/factura/abonos_factura";

@Component({
  selector: 'app-cobro-factura',
  templateUrl: './cobro-factura.component.html',
  styleUrls: ['./cobro-factura.component.scss'],
  providers: [ElementsService, FacturaService]
})
export class CobroFacturaComponent implements OnInit {
  public objregistro_factura: Registro_factura;
  public objAbono: Abonos_factura;
  public abono: any;
  token: any;
  public objRespuesta: any;
  position = "top-right";
  public loader: any;

  constructor(private _ElementService: ElementsService,
              private _FacturaService: FacturaService) {
    this.objregistro_factura = new Registro_factura('', '',
      '', '', '', '',
      '', '', '', '',
      '', '');
    this.objAbono = new Abonos_factura('', '',
      '', '', '',
      '', '', '', '');
    this.token = localStorage.getItem('token');
    this.objRespuesta = null;
    this.loader = 0;
    this.abono = '';

  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CobroFacturaComponent');
    this.loader = 0;
  }

  tecla(event) {
    if (event.key === "Enter" || event.key == "Tab") {
      this.loader = 1;
      var arregloCode = this.objregistro_factura.codigo_registro_factura.split('-');
      if (arregloCode.length > 1) {
        if (arregloCode[0] == 'F') {
          this._FacturaService.cargarFacturaPagar(this.token, this.objregistro_factura).subscribe(
            respuesta => {
              this._ElementService.pi_poValidarCodigo(respuesta);
              if (respuesta.status == 'success') {
                this.objRespuesta = respuesta.data;
                this._ElementService.pi_poBontonDesabilitar('#codigoFactura');
                this._ElementService.pi_poAlertaSuccess(respuesta.msg, 'PIXEL');
                this.loader = 0;
              } else {
                this.objRespuesta = respuesta.data;
                this._ElementService.pi_poVentanaAlertaWarning('PIXEL', respuesta.msg);
                this.loader = 0;
              }
            }, error2 => {

            }
          )
        } else if (arregloCode[0] == 'A') {//Logica abono
          this.objAbono.codigo_abono_factura = this.objregistro_factura.codigo_registro_factura;
          this._FacturaService.efectuarAbono(this.token, this.objAbono).subscribe(
            returned => {
              if (returned.status == 'success') {
                this.abono = '1';
                this.loader = 0;
              } else {
                this.abono = '';
                this._ElementService.pi_poVentanaAlertaWarning(returned.code, returned.msg);
                this.loader = 0;
              }
            }, error2 => {
              this.loader = 0;
            }
          )
        } else {
          this._ElementService.pi_poVentanaAlertaWarning('1000', 'Lo sentimos, no reconocemos este codigo');
          this.loader = 0;
        }
      } else {
        this._ElementService.pi_poVentanaAlertaWarning('1000', 'Lo sentimos, el codigo es requerido');
        this.loader = 0;
      }
    }
  }

  pagarFactura() {
    this.loader = 1;
    this._FacturaService.pagarFactura(this.token, this.objregistro_factura).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.objRespuesta = respuesta.data;
          this.objregistro_factura.codigo_registro_factura = '';
          this.objRespuesta = null;
          this._ElementService.pi_poBotonHabilitar('#codigoFactura');
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, 'PIXEL');
          this.loader = 0;
          $("#codigoFactura").focus();
        } else {
          this.objRespuesta = respuesta.data;
          this._ElementService.pi_poVentanaAlertaWarning('PIXEL', respuesta.msg);
          this.loader = 0;
        }
      }, error2 => {

      }
    )
  }

  cancelarFactura() {
    swal({
      title: '¿Esta seguro de cancelar la factura?',
      text: 'Si su respuesat es SI, esta factura se recargara en la proxima factura generada',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, lo estoy'
    }).then((result) => {
      if (result.value) {
        this.objregistro_factura.codigo_registro_factura = '';
        this.objRespuesta = null;
        this._ElementService.pi_poBotonHabilitar('#codigoFactura');
        this._ElementService.pi_poAlertaError('Se cancelo el pago de la factura', 'PIXEL');
        $("#codigoFactura").focus();
      }
    });
  }
}
