import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {Registro_factura} from "../../../models/factura/registro_factura";
import {FacturaService} from "../../../services/factura/factura.service";
import swal from "sweetalert2";

@Component({
  selector: 'app-cobro-factura',
  templateUrl: './cobro-factura.component.html',
  styleUrls: ['./cobro-factura.component.scss'],
  providers: [ElementsService, FacturaService]
})
export class CobroFacturaComponent implements OnInit {
  public objregistro_factura: Registro_factura;
  token: any;
  public objRespuesta: any;
  position = "top-right";
  constructor(private _ElementService: ElementsService,
              private _FacturaService: FacturaService) {
    this.objregistro_factura = new Registro_factura('', '',
      '', '', '', '',
      '', '', '', '',
      '', '');
    this.token = localStorage.getItem('token');
    this.objRespuesta = null;
    $("#loaderGenerarReporte").hide();
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CobroFacturaComponent');
    $("#loaderGenerarReporte").hide();
  }

  tecla(event) {
    if (event.key === "Enter" || event.key == "Tab") {
      $("#loaderGenerarReporte").show();
      this._FacturaService.cargarFacturaPagar(this.token,this.objregistro_factura).subscribe(
        respuesta => {
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success') {
            this.objRespuesta = respuesta.data;
            this._ElementService.pi_poBontonDesabilitar('#codigoFactura');
            this._ElementService.pi_poAlertaSuccess(respuesta.msg, 'PIXEL');
            $("#loaderGenerarReporte").hide();
          } else {
            this.objRespuesta = respuesta.data;
            this._ElementService.pi_poVentanaAlertaWarning('PIXEL', respuesta.msg);
            $("#loaderGenerarReporte").hide();
          }
        }, error2 => {

        }
      )
    }
  }

  pagarFactura() {
    $("#loaderGenerarReporte").show();
    this._FacturaService.pagarFactura(this.token, this.objregistro_factura).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.objRespuesta = respuesta.data;
          this.objregistro_factura.codigo_registro_factura = '';
          this.objRespuesta=null;
          this._ElementService.pi_poBotonHabilitar('#codigoFactura');
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, 'PIXEL');
          $("#loaderGenerarReporte").hide();
          $("#codigoFactura").focus();
        } else {
          this.objRespuesta = respuesta.data;
          this._ElementService.pi_poVentanaAlertaWarning('PIXEL', respuesta.msg);
          $("#loaderGenerarReporte").hide();
        }
      }, error2 => {

      }
    )
  }
  cancelarFactura(){
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
        this.objregistro_factura.codigo_registro_factura='';
        this.objRespuesta=null;
        this._ElementService.pi_poBotonHabilitar('#codigoFactura');
        this._ElementService.pi_poAlertaError('Se cancelo el pago de la factura','PIXEL');
        $("#codigoFactura").focus();
      }
    });
  }
}
