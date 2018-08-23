import { Component, OnInit } from '@angular/core';
import {NgbCalendar, NgbDateParserFormatter, NgbDateStruct} from "@ng-bootstrap/ng-bootstrap";
import {ElementsService} from "../../../services/elements.service";
import swal from "sweetalert2";
import {FacturaService} from "../../../services/factura/factura.service";

@Component({
  selector: 'app-cargar-factura',
  templateUrl: './cargar-factura.component.html',
  styleUrls: ['./cargar-factura.component.scss'],
  providers:[ElementsService,FacturaService]
})
export class CargarFacturaComponent implements OnInit {
  navigation = 'select';
  model:any;

  disabled = true;

  toggle = false;
  token: any;
  position = "top-right";
  constructor(private _ElementService: ElementsService,public parserFormatter: NgbDateParserFormatter,
              public calendar: NgbCalendar,
              private _FacturaService:FacturaService) {
    this.model = '';
    this.token= localStorage.getItem('token');
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('GenerarFacturaComponent');
  }
  cargarFactura(){
    if (this.model !=''){
      swal({
        title: '¿Esta seguro de cargar las facturas?',
        text: 'Se cargaran las facturas con fecha de inicio de: '+this.parserFormatter.format(this.model),
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, lo estoy'
      }).then((result) => {
        $("#loaderUsuario").show();
        if (result.value) {
          this._FacturaService.cargarFacturas(this.token,this.parserFormatter.format(this.model)).subscribe(
            respuesta=>{
              this._ElementService.pi_poValidarCodigo(respuesta);
              if (respuesta.status == 'success'){
                this.model = '';
                this._ElementService.pi_poAlertaSuccess(respuesta.msg,'PIXEL');
              }else
              {
                this._ElementService.pi_poVentanaAlertaWarning('PIXEL',respuesta.msg);
              }
            },error2 => {

            }
          )
        }
      });
    }else
    {
      this._ElementService.pi_poVentanaAlertaWarning('PIXEL','Lo sentimos, sebes seleccionar una fecha');
    }

  }
}
