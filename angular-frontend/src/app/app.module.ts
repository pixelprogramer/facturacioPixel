import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import {SharedModule} from './shared/shared.module';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';

import {routing,appRoutingProviders} from "./app.rounting";
import {FormsModule} from "@angular/forms";
import {HttpClientModule} from "@angular/common/http";

import { AppComponent } from './app.component';
import {CKEditorModule} from "ng2-ckeditor";
import {SafeHtmlPipe} from "./services/tanfromarHtml";
import { LoginComponent } from './components/login/login.component';
import { HomeAdminSistemasComponent } from './components/administradorSistemas/home-admin-sistemas/home-admin-sistemas.component';
import { CrearUsuariosComponent } from './components/administradorSistemas/crear-usuarios/crear-usuarios.component';
import { CrearPermisosComponent } from './components/administradorSistemas/crear-permisos/crear-permisos.component';
import { CrearMenuComponent } from './components/administradorSistemas/crear-menu/crear-menu.component';
import { AdministrarMenuComponent } from './components/administradorSistemas/administrar-menu/administrar-menu.component';
import { ValidarPaginaComponent } from './components/validar-pagina/validar-pagina.component';
import { CrearFacturaComponent } from './components/facturacion/crear-factura/crear-factura.component';
import { HomeAdministradorComponent } from './components/administrador/home-administrador/home-administrador.component';
import { GenerarFacturaComponent } from './components/facturacion/generar-factura/generar-factura.component';
import { CargarFacturaComponent } from './components/facturacion/cargar-factura/cargar-factura.component';
import { CobroFacturaComponent } from './components/facturacion/cobro-factura/cobro-factura.component';




@NgModule({
  declarations: [
    AppComponent,
    SafeHtmlPipe,

    LoginComponent,
    HomeAdminSistemasComponent,
    CrearUsuariosComponent,
    CrearPermisosComponent,
    CrearMenuComponent,
    AdministrarMenuComponent,
    ValidarPaginaComponent,
    CrearFacturaComponent,
    HomeAdministradorComponent,
    GenerarFacturaComponent,
    CargarFacturaComponent,
    CobroFacturaComponent
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    SharedModule,
    routing,
    FormsModule,
    HttpClientModule,
    CKEditorModule,

  ],
  providers: [appRoutingProviders],
  bootstrap: [AppComponent]
})
export class AppModule { }
