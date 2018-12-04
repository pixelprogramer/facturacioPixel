import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {Usuario} from "../../../models/seguridad/usuario";
import {Rol} from "../../../models/seguridad/rol";
import {RolService} from "../../../services/administradorSistemas/rol.service";
import {UsuarioService} from "../../../services/usuario.service";
import {Ramal_factura} from "../../../models/factura/ramal_factura";
import {FacturaService} from "../../../services/factura/factura.service";

@Component({
  selector: 'app-crear-usuarios',
  templateUrl: './crear-usuarios.component.html',
  styleUrls: ['./crear-usuarios.component.scss'],
  providers: [ElementsService, RolService, UsuarioService,FacturaService]
})
export class CrearUsuariosComponent implements OnInit {
  position = "top-right";
  public objUsuario: Usuario;
  public listUsuario: Array<any>;
  public listRol: Array<Rol>;
  public token: any;
  public listRamales: Array<Ramal_factura>;
  public loader: any;
  constructor(private _ElementService: ElementsService,
              private _RolService: RolService,
              private _UsuarioService: UsuarioService,
              private _FacturaService:FacturaService) {
    this.objUsuario = new Usuario('', '', '', '-',
      '-', '', '2', '000',
      '', '', '', '','','');
    this.token = localStorage.getItem('token');
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CrearUsuariosComponent');
    this.listarRoles();
    this.listarUsuarios();
    this.listarRamales();
    this.loader= 0;
  }

  listarRoles() {
    this.loader= 1;
    this._RolService.listarRol(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listRol = respuesta.data;
            this.loader= 0;
          } else {
            this.listRol = [];
            this.loader= 0;
          }
        } else {
          this.loader= 0;
        }
      }, error2 => {
        this.loader= 0;
      }
    )
  }

  listarUsuarios() {
    this.loader= 1;
    this._UsuarioService.listarUsuarioSeguridad(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listUsuario = respuesta.data;
            this.loader= 0;
          } else {
            this.listUsuario = [];
            this.loader= 0;
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          this.loader= 0;
        }
      }, error2 => {
        this.loader= 0;
      }
    );
  }

  crearUsuario() {
    this.loader= 1;
    this._UsuarioService.crearUsuario(this.token, this.objUsuario).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this._ElementService.pi_poAlertaSuccess(respuesta.msg,respuesta.code);
          this.limpiarCampos()
          this.listarUsuarios();
          this.loader= 0;
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          this.loader= 0;
        }
      }, error2 => {
        this.loader= 0;
      }
    )
  }
  actualizarUsuario() {
    this.loader= 1;
    this._UsuarioService.actualizarUsuario(this.token, this.objUsuario).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this._ElementService.pi_poAlertaSuccess(respuesta.msg,respuesta.code);
          this.limpiarCampos();
          this.listarUsuarios();
          this.loader= 0;
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          this.loader= 0;
        }
      }, error2 => {
        this.loader= 0;

      }
    )
  }
  seleccionarUsuario(Usuario) {
    this.objUsuario = Usuario;
  }
  limpiarCampos(){
    this.objUsuario = new Usuario('', '', '', '-',
      '-', '', '000', '000',
      '', '', '', '','','');
  }
  listarRamales() {
    this.loader= 1;
    this._FacturaService.listarRamales(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listRamales = respuesta.data;
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
          this.loader= 0;
        } else {
          this.loader= 0;
        }
      }, error2 => {
        this.loader= 0;
      }
    )
  }
}
