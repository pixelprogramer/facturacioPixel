import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {NgbDateStruct, NgbDateParserFormatter, NgbCalendar} from "@ng-bootstrap/ng-bootstrap";
import {GLOBAL} from "../../../services/global";
import {FacturaService} from "../../../services/factura/factura.service";
import {Ramal_factura} from "../../../models/factura/ramal_factura";
import {Usuario} from "../../../models/seguridad/usuario";
import {UsuarioService} from "../../../services/usuario.service";

@Component({
  selector: 'app-generar-factura',
  templateUrl: './generar-factura.component.html',
  styleUrls: ['./generar-factura.component.scss'],
  providers: [ElementsService, FacturaService, UsuarioService]
})
export class GenerarFacturaComponent implements OnInit {
  position = "top-right";
  public urlFile: any;
  validacion = '';
  token = '';
  public loader: any;
  public listRamales: Array<Ramal_factura>;
  public id_ramal: string;
  public listUsuario: Array<any>;
  public seleccionUsuario: Usuario;
  public filtro: any;

  constructor(private _ElementService: ElementsService, public parserFormatter: NgbDateParserFormatter, private _UsuarioService: UsuarioService,
              public calendar: NgbCalendar,
              private _FacturaService: FacturaService) {
    this.urlFile = GLOBAL.urlFiles;
    this.token = localStorage.getItem('token');
    this.id_ramal = 'todo';
    this.seleccionUsuario = new Usuario('', '', '',
      '', '', '', '',
      '', '', '', '', '', '', '');

    this.filtro = '';
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('GenerarFacturaComponent');
    this.listarRamales();
  }

  generarFacturas() {
    this.loader= 1;
    this.urlFile = GLOBAL.urlFiles;
    this._FacturaService.generarFactura(this.token, this.id_ramal, this.seleccionUsuario.id_usuario).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.urlFile += respuesta.data;
          let frame = '<iframe src="' + this.urlFile + '"style="width:100%; height:1200px;" frameborder="0"></iframe>';
          $("#seccioPdf").html(frame);
          this.loader= 0;
        } else {
          this._ElementService.pi_poVentanaAlertaWarning('PIXEL', respuesta.msg);
          this.loader= 0;
        }
      }, error2 => {
        this.loader= 0;
      }
    )
  }

  listarRamales() {
    this.loader = 1;
    this._FacturaService.listarRamales(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listRamales = respuesta.data;
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
          this.loader = 0;
        } else {
          this.loader = 0;
        }
      }, error2 => {
        this.loader= 0;
      }
    )
  }

  filtrarUsuario() {
    if (this.filtro.trim() != '') {
      this.loader = 1;
      this._UsuarioService.filtroUsuario(this.token, this.filtro).subscribe(
        respuesta => {
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success') {
            if (respuesta.data != 0) {
              this.listUsuario = respuesta.data;
              this.filtro = '';
              this.loader = 0;
            } else {
              this._ElementService.pi_poAlertaError('No se encontraron resultados', 'PIXEL');
              this.listarUsuarios();
              this.loader = 0;
            }
          } else {
            this._ElementService.pi_poVentanaAlertaWarning('PIXEL', respuesta.msg);
            this.loader = 0;
          }

        }, error2 => {
          this.loader = 0;
        }
      )
    } else {
      this._ElementService.pi_poAlertaWarning('El campo filtro es requerido', 'PIXEL');
      this.listarUsuarios();
    }

  }

  listarUsuarios() {
    this.loader = 1;
    this._UsuarioService.listarUsuarioSeguridad(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listUsuario = respuesta.data;
            this.loader = 0;
          } else {
            this.listUsuario = [];
            this.loader = 0;
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          this.loader = 0;
        }
      }, error2 => {
        this.loader = 0;
      }
    );

  }

  seleccionarUsuario(usuario) {
    this.seleccionUsuario = usuario;

  }
}
