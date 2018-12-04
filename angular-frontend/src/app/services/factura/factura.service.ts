import {Observable} from "rxjs/Observable";
import {Injectable} from "@angular/core";
import {HttpClient,HttpHeaders} from "@angular/common/http";
import {GLOBAL} from "../global";


@Injectable()

export class FacturaService{
  public url: any;
  constructor(private _http:HttpClient){
    this.url=GLOBAL.url;
  }

  listarFacturasUsuario(token,id): Observable<any>{
    let parametros = 'token='+token+'&id_usuario='+id;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/listarFacturaUsuario',parametros, {headers: headers});
  }
  crearFactura(token,objFactura): Observable<any>{
    let json = JSON.stringify(objFactura);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/usuario/nuevaFactura',parametros, {headers: headers});
  }
  cargarRamales(token,objRamal): Observable<any>{
    let json = JSON.stringify(objRamal);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + '/administrador/usuario/nuevoRamal',parametros, {headers: headers});
  }
  actualizarRamales(token,objRamal): Observable<any>{
    let json = JSON.stringify(objRamal);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + '/administrador/usuario/actualizarRamal',parametros, {headers: headers});
  }
  listarRamales(token): Observable<any>{
    let parametros = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + '/administrador/usuario/listarRamales',parametros, {headers: headers});
  }
  actualizarFactura(token,objFactura): Observable<any>{
    let json = JSON.stringify(objFactura);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/usuario/actualizarFactura',parametros, {headers: headers});
  }
  listarTarifasFactura(token,id): Observable<any>{
    let parametros = 'token='+token+'&id='+id;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/factura/listarTarifa',parametros, {headers: headers});
  }
  crearTarifa(token,objTarifa): Observable<any>{
    let json = JSON.stringify(objTarifa);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/factura/crearTarifa',parametros, {headers: headers});
  }
  actualizarTarifa(token,objTarifa): Observable<any>{
    let json = JSON.stringify(objTarifa);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/factura/actualizarTarifa',parametros, {headers: headers});
  }
  cargarFacturas(token,fechas): Observable<any>{
    let parametros = 'token='+token+'&fecha='+fechas;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/factura/generarFactura',parametros, {headers: headers});
  }
  cargarFacturasUsuarioUnitario(token,fechas,id): Observable<any>{
    let parametros = 'token='+token+'&fecha='+fechas+'&id='+id;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/factura/generarFacturaUsuarioUnitario',parametros, {headers: headers});
  }
  generarFactura(token,id_ramal,id_usuario): Observable<any>{
    let parametros = 'token='+token+'&id='+id_ramal+'&id_usuario='+id_usuario;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/factura/generarReporteFacturaTodos',parametros, {headers: headers});
  }
  cargarFacturaPagar(token,registro_factura): Observable<any>{
    let json = JSON.stringify(registro_factura);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/factura/cargarFacturaCobrar',parametros, {headers: headers});
  }
  pagarFactura(token,registro_factura): Observable<any>{
    let json = JSON.stringify(registro_factura);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/factura/cobrarFactura',parametros, {headers: headers});
  }
  cargarAbono(token,objAbono,usuario,total): Observable<any>{
    let json = JSON.stringify(objAbono);
    let jsonUsuario= JSON.stringify(usuario)
    let parametros = 'token='+token+'&json='+json+'&usuario='+jsonUsuario+'&total='+total;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/abono/cargarAbono',parametros, {headers: headers});
  }
  cargarValorTotal(token,id_user): Observable<any>{

    let parametros = 'token='+token+'&id_user='+id_user;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/abono/cargarFacturaValor',parametros, {headers: headers});
  }
  efectuarAbono(token,objAbono): Observable<any>{
    let json = JSON.stringify(objAbono);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/abono/efectuarAbono',parametros, {headers: headers});
  }
}
