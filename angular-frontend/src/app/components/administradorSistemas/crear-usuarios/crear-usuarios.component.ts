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
    $("#loaderTablaMenu").hide();
  }

  listarRoles() {
    this._RolService.listarRol(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listRol = respuesta.data;
          } else {
            this.listRol = [];
          }
        } else {

        }
      }, error2 => {

      }
    )
  }

  listarUsuarios() {
    $("#loaderTablaMenu").show();
    this._UsuarioService.listarUsuarioSeguridad(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listUsuario = respuesta.data;
            $("#loaderTablaMenu").hide();
          } else {
            this.listUsuario = [];
            $("#loaderTablaMenu").hide();
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    );
  }

  crearUsuario() {
    this._UsuarioService.crearUsuario(this.token, this.objUsuario).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this._ElementService.pi_poAlertaSuccess(respuesta.msg,respuesta.code);
          this.limpiarCampos()
          this.listarUsuarios();
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    )
  }
  actualizarUsuario() {
    this._UsuarioService.actualizarUsuario(this.token, this.objUsuario).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this._ElementService.pi_poAlertaSuccess(respuesta.msg,respuesta.code);
          this.limpiarCampos();
          this.listarUsuarios();
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

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
