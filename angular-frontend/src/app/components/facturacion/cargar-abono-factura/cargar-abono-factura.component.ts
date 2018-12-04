import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {Abonos_factura} from "../../../models/factura/abonos_factura";
import {UsuarioService} from "../../../services/usuario.service";
import {Usuario} from "../../../models/seguridad/usuario";
import {FacturaService} from "../../../services/factura/factura.service";
import {GLOBAL} from "../../../services/global";

@Component({
  selector: 'app-cargar-abono-factura',
  templateUrl: './cargar-abono-factura.component.html',
  styleUrls: ['./cargar-abono-factura.component.scss'],
  providers: [ElementsService, UsuarioService,
    FacturaService]
})
export class CargarAbonoFacturaComponent implements OnInit {
  public objAbonoFactura: Abonos_factura;
  public loader: any;
  public listUsuario: Array<any>;
  public token: any;
  public filtro: any;
  public objUsuario: any;
  public userSelect: string;
  public totalPagar: any;
  public totalAbonoFormato: any;
  position = "top-right";
  public urlFile:any;
  constructor(private _ElementService: ElementsService,
              private _UsuarioService: UsuarioService,
              private _FacturaService: FacturaService) {
    this.objAbonoFactura = new Abonos_factura('', '', '',
      '', '',
      '', '', '000', '');
    this.userSelect = '';
    this.loader = 0;
    this.token = localStorage.getItem('token');
    this.filtro = '';
    this.totalPagar = '';
    this.totalAbonoFormato = '';

  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CargarAbonoFacturaComponent');
  }

  listarUsuarios() {
    this.loader = 1;
    this._UsuarioService.listarUsuarioSeguridad(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listUsuario = respuesta.data;
            this.loader = 0
          } else {
            this.listUsuario = [];
            this.loader = 0
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    );

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
              this.loader = 0;
              this._ElementService.pi_poAlertaError('No se encontraron resultados', 'PIXEL');
              this.listarUsuarios();
            }
          } else {
            this.loader = 0;
            this._ElementService.pi_poVentanaAlertaWarning('PIXEL', respuesta.msg);
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

  selectUser(objUser) {
    this.objUsuario = objUser;
    this.loader = 1;
    this._FacturaService.cargarValorTotal(this.token, this.objUsuario.id_usuario).subscribe(
      returned => {
        this._ElementService.pi_poValidarCodigo(returned);
        if (returned.status == 'success') {
          this.totalPagar = returned.data.totalAbono;
          this.totalAbonoFormato = returned.data.totalAbonoFormato;
          this.loader = 0;
        } else {
          this.totalPagar = '';
          this.totalAbonoFormato = '';
          this._ElementService.pi_poVentanaAlertaWarning(returned.code, returned.msg);
          this.objUsuario = null;
          this.userSelect = '';
          this.loader = 0;
        }

      }, error2 => {
        this.loader = 0;
      }
    );
    this.userSelect = this.objUsuario.documento_usuario + '-' + this.objUsuario.nombre_usuario
  }

  validateData() {

    if (this.objUsuario != null) {
      if (this.objAbonoFactura.total_abono_factura != '') {
        if (parseInt(this.objAbonoFactura.total_abono_factura) <= parseInt(this.totalPagar)) {
          if (this.objAbonoFactura.total_abono_factura > 0) {
            if (this.objAbonoFactura.tipo_abono != '000') {
              return true;
            } else {
              this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'El tipo de abono es requerido');
            }
          } else {
            this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'El total abono debe ser mayor a 0');
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'El abono no debe ser mayor al total a pagar');
        }
      } else {
        this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'El total abono es requerido');
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('PIXEL', 'No se selecciono un usuario');
    }
    return false;
  }

  new() {
    if (this.validateData()) {
      this.urlFile=GLOBAL.urlFiles;
      this.loader = 1;
      this._FacturaService.cargarAbono(this.token, this.objAbonoFactura, this.objUsuario,this.totalPagar).subscribe(
        returned => {
          this._ElementService.pi_poValidarCodigo(returned);
          if (returned.status == 'success') {
            this.urlFile += returned.data;
            let frame = '<iframe src="'+this.urlFile+'"style="width:100%; height:1200px;" frameborder="0"></iframe>';
            $("#seccioPdf").html(frame);
            this.loader = 0;
          } else {
            this._ElementService.pi_poVentanaAlertaWarning(returned.code, returned.msg);
            this.loader = 0;
          }
        }, error2 => {

        }
      )
    }
  }
}
