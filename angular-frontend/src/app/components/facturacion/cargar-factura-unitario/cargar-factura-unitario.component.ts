import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {NgbCalendar, NgbDateParserFormatter} from "@ng-bootstrap/ng-bootstrap";
import {FacturaService} from "../../../services/factura/factura.service";
import swal from "sweetalert2";
import {UsuarioService} from "../../../services/usuario.service";
import {Usuario} from "../../../models/seguridad/usuario";

@Component({
  selector: 'app-cargar-factura-unitario',
  templateUrl: './cargar-factura-unitario.component.html',
  styleUrls: ['./cargar-factura-unitario.component.scss'],
  providers:[ElementsService,FacturaService,UsuarioService]
})
export class CargarFacturaUnitarioComponent implements OnInit {
  navigation = 'select';
  model:any;
  disabled = true;
  toggle = false;
  token: any;
  position = "top-right";
  public loader: any;
  public listUsuario: Array<any>;
  public seleccionUsuario: Usuario;
  public filtro:any;
  constructor(private _ElementService: ElementsService,public parserFormatter: NgbDateParserFormatter,
              public calendar: NgbCalendar,
              private _UsuarioService: UsuarioService,
              private _FacturaService:FacturaService) {
    this.model = '';
    this.token= localStorage.getItem('token');
    this.seleccionUsuario = new Usuario('', '', '',
      '', '', '', '',
      '', '', '', '', '', '','');

    this.filtro = '';
    this.loader=0;
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CargarFacturaUnitarioComponent');
  }
  cargarFactura(){
    if (this.seleccionUsuario.id_usuario != ''){
      if (this.model !=''){
        swal({
          title: '¿Esta seguro de cargar las facturas?',
          text: 'Se cargaran las facturas con fecha de inicio de: '+this.parserFormatter.format(this.model)+' ' +
          'al usuario '+this.seleccionUsuario.nombre_usuario+' '+this.seleccionUsuario.apellido_usuario+' ' +
          'con documento: '+this.seleccionUsuario.documento_usuario,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, lo estoy'
        }).then((result) => {
          this.loader=1;
          if (result.value) {
            this._FacturaService.cargarFacturasUsuarioUnitario(this.token,
              this.parserFormatter.format(this.model),this.seleccionUsuario.id_usuario).subscribe(
              respuesta=>{
                this._ElementService.pi_poValidarCodigo(respuesta);
                if (respuesta.status == 'success'){
                  this.model = '';
                  this._ElementService.pi_poAlertaSuccess(respuesta.msg,'PIXEL');
                  this.loader=0;
                }else
                {
                  this._ElementService.pi_poVentanaAlertaWarning('PIXEL',respuesta.msg);
                  this.loader=0;
                }
              },error2 => {

              }
            )
          }
        });
      }else
      {
        this._ElementService.pi_poVentanaAlertaWarning('PIXEL','Lo sentimos, sebes seleccionar una fecha');
        this.loader=0;
      }
    }else
    {
      this._ElementService.pi_poAlertaError('Lo sentimos, no se selecciono el usuario','PIXEL');
      this.loader=0;
    }


  }
  filtrarUsuario(){
    if (this.filtro.trim() != ''){
      this.loader=1;
      this._UsuarioService.filtroUsuario(this.token,this.filtro).subscribe(
        respuesta=>{
          this._ElementService.pi_poValidarCodigo(respuesta);
          if  (respuesta.status == 'success'){
            if (respuesta.data != 0){
              this.listUsuario = respuesta.data;
              this.filtro='';
              this.loader=0;
            }else
            {
              this._ElementService.pi_poAlertaError('No se encontraron resultados','PIXEL');
              this.listarUsuarios();
              this.loader=0;
            }
          }else
          {
            this._ElementService.pi_poVentanaAlertaWarning('PIXEL',respuesta.msg);
            this.loader=0;
          }

        },error2 => {
          this.loader=0;
        }
      )
    }else
    {
      this._ElementService.pi_poAlertaWarning('El campo filtro es requerido','PIXEL');
      this.listarUsuarios();
    }

  }
  listarUsuarios() {
    this.loader=1;
    this._UsuarioService.listarUsuarioSeguridad(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listUsuario = respuesta.data;
            this.loader=0;
          } else {
            this.listUsuario = [];
            this.loader=0;
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          this.loader=0;
        }
      }, error2 => {
        this.loader=0;
      }
    );

  }
  seleccionarUsuario(usuario) {
    this.seleccionUsuario = usuario;

  }
}
