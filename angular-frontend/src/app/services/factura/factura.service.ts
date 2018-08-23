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
  generarFactura(token): Observable<any>{
    let parametros = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'administrador/factura/generarReporteFacturaTodos',parametros, {headers: headers});
  }
}
