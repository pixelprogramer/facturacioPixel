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
  public loader: any;
  public listRamales: Array<Ramal_factura>;
  position = "top-right";

  constructor(private _ElementService: ElementsService,
              private _FacturaService: FacturaService) {
    this.objRamalFactura = new Ramal_factura('', '');
    this.token = localStorage.getItem('token');
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CargarRamalesComponent');
    this.loader=0;
    this.listarRamales();
  }
  limpiarCampos(){
    this.objRamalFactura = new Ramal_factura('', '');
  }
  cargarRamales() {
    if (this.objRamalFactura.descripcion_ramal_factura.trim() != '') {
      this.loader=1;
      this._FacturaService.cargarRamales(this.token, this.objRamalFactura).subscribe(
        respuesta => {
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success') {
            this.listarRamales();
            this.limpiarCampos();
            this.loader=0;
            this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
          } else {
            this.loader=0;
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
        this.loader=1;
        this._FacturaService.actualizarRamales(this.token, this.objRamalFactura).subscribe(
          respuesta => {
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success') {
              this.listarRamales();
              this.limpiarCampos();
              this.loader=0;
              this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
            } else {
              this.loader=0;
            }
          }, error2 => {
            this.loader=0;
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
    this.loader=1;
    this._FacturaService.listarRamales(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listRamales = respuesta.data;
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
          this.loader=0;
        } else {
          this.loader=0;
        }
      }, error2 => {
        this.loader=0;
      }
    )
  }
}
