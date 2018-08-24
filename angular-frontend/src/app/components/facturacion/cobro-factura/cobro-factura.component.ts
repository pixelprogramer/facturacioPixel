import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {Registro_factura} from "../../../models/factura/registro_factura";
import {FacturaService} from "../../../services/factura/factura.service";

@Component({
  selector: 'app-cobro-factura',
  templateUrl: './cobro-factura.component.html',
  styleUrls: ['./cobro-factura.component.scss'],
  providers: [ElementsService,FacturaService]
})
export class CobroFacturaComponent implements OnInit {
  public objregistro_factura:Registro_factura;
  token:any;
  public objRespuesta:any;
  constructor(private _ElementService:ElementsService,
              private _FacturaService: FacturaService) {
    this.objregistro_factura = new Registro_factura('','',
      '','','','',
      '','','','',
      '','');
    this.token = localStorage.getItem('token');
    this.objRespuesta=null;
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CobroFacturaComponent');
  }
  tecla(event) {
    if (event.key === "Enter" || event.key == "Tab") {
      this._FacturaService.pagarFactura(this.token,this.objregistro_factura).subscribe(
        respuesta=>{
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success'){
            this.objRespuesta = respuesta.data;
            this.objregistro_factura.codigo_registro_factura='';
            this._ElementService.pi_poAlertaSuccess(respuesta.msg,'PIXEL');
          }else {
            this.objRespuesta = respuesta.data;
            this._ElementService.pi_poVentanaAlertaWarning('PIXEL',respuesta.msg);
          }
        },error2 => {

        }
      )
    }
  }
}
