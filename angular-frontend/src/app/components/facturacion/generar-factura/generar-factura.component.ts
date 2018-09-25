import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {NgbDateStruct,NgbDateParserFormatter,NgbCalendar} from "@ng-bootstrap/ng-bootstrap";
import {GLOBAL} from "../../../services/global";
import {FacturaService} from "../../../services/factura/factura.service";
import {Ramal_factura} from "../../../models/factura/ramal_factura";

@Component({
  selector: 'app-generar-factura',
  templateUrl: './generar-factura.component.html',
  styleUrls: ['./generar-factura.component.scss'],
  providers:[ElementsService,FacturaService]
})
export class GenerarFacturaComponent implements OnInit {
  position = "top-right";
  public urlFile:any;
  validacion = '';
  token = '';
  public listRamales: Array<Ramal_factura>;
  public id_ramal:string;
  constructor(private _ElementService: ElementsService,public parserFormatter: NgbDateParserFormatter,
              public calendar: NgbCalendar,
              private _FacturaService: FacturaService) {
    this.urlFile = GLOBAL.urlFiles;
    this.token = localStorage.getItem('token');
    this.id_ramal='todo';
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('GenerarFacturaComponent');
    this.listarRamales();
  }
  generarFacturas(){
    this.urlFile=GLOBAL.urlFiles;
    this._FacturaService.generarFactura(this.token,this.id_ramal).subscribe(
      respuesta=>{
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success'){
          this.urlFile += respuesta.data;
          let frame = '<iframe src="'+this.urlFile+'"style="width:100%; height:1200px;" frameborder="0"></iframe>';
          $("#seccioPdf").html(frame);

        }else {
          this._ElementService.pi_poVentanaAlertaWarning('PIXEL',respuesta.msg);
        }
      },error2 => {

      }
    )
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
