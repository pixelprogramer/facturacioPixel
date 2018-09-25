import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {FacturaService} from "../../../services/factura/factura.service";
import {Ramal_factura} from "../../../models/factura/ramal_factura";

@Component({
  selector: 'app-cargar-ramales',
  templateUrl: './cargar-ramales.component.html',
  styleUrls: ['./cargar-ramales.component.scss'],
  providers: [ElementsService, FacturaService]
})
export class CargarRamalesComponent implements OnInit {
  public objRamalFactura: Ramal_factura
  public token: any;
  public listRamales: Array<Ramal_factura>;
  position = "top-right";

  constructor(private _ElementService: ElementsService,
              private _FacturaService: FacturaService) {
    this.objRamalFactura = new Ramal_factura('', '');
    this.token = localStorage.getItem('token');
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CargarRamalesComponent');
    $("#loaderTablaMenu").hide();
    this.listarRamales();
  }
  limpiarCampos(){
    this.objRamalFactura = new Ramal_factura('', '');
  }
  cargarRamales() {
    if (this.objRamalFactura.descripcion_ramal_factura.trim() != '') {
      this._FacturaService.cargarRamales(this.token, this.objRamalFactura).subscribe(
        respuesta => {
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success') {
            this.listarRamales();
            this.limpiarCampos();
            this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
          } else {

          }
        }, error2 => {

        }
      )
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentismo, la descripcion del ramal es requerida');
    }
  }

  seleccionarRamal(ramal) {
    this.objRamalFactura = ramal;
    this._ElementService.pi_poAlertaWarning('Se selecciono el ramal: ' + this.objRamalFactura.descripcion_ramal_factura, 'PIXEL');
  }

  actualizarRamales() {
    if (this.objRamalFactura.descripcion_ramal_factura.trim() != '') {
      if (this.objRamalFactura.id_ramal_factura != '' || this.objRamalFactura.id_ramal_factura != null) {
        this._FacturaService.actualizarRamales(this.token, this.objRamalFactura).subscribe(
          respuesta => {
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success') {
              this.listarRamales();
              this.limpiarCampos();
              this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
            } else {

            }
          }, error2 => {

          }
        )
      } else {
        this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentimos, No se selecciono ningun ramal');
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'Lo sentismo, la descripcion del ramal es requerida');
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
