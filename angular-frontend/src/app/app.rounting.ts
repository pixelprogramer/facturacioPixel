import {ModuleWithProviders} from "@angular/core";
import {Routes, RouterModule} from "@angular/router";


import {AppComponent} from './app.component';

import {LoginComponent} from "./components/login/login.component";
import {HomeAdminSistemasComponent} from "./components/administradorSistemas/home-admin-sistemas/home-admin-sistemas.component";
import {CrearUsuariosComponent} from "./components/administradorSistemas/crear-usuarios/crear-usuarios.component";
import {CrearPermisosComponent} from "./components/administradorSistemas/crear-permisos/crear-permisos.component";
import {CrearMenuComponent} from "./components/administradorSistemas/crear-menu/crear-menu.component";
import {AdministrarMenuComponent} from "./components/administradorSistemas/administrar-menu/administrar-menu.component";
import {ValidarPaginaComponent} from "./components/validar-pagina/validar-pagina.component";
import {CrearFacturaComponent} from "./components/facturacion/crear-factura/crear-factura.component";
import {HomeAdministradorComponent} from "./components/administrador/home-administrador/home-administrador.component";
import {GenerarFacturaComponent} from "./components/facturacion/generar-factura/generar-factura.component";
import {CargarFacturaComponent} from "./components/facturacion/cargar-factura/cargar-factura.component";
import {CobroFacturaComponent} from "./components/facturacion/cobro-factura/cobro-factura.component";



const appRoutes: Routes = [

  {path: '', component: ValidarPaginaComponent},
  {path: '👑/💻/🏠', component: HomeAdminSistemasComponent},
  {path: '👑/💻/crear/👦', component: CrearUsuariosComponent},
  {path: '👑/💻/crearPermisos', component: CrearPermisosComponent},
  {path: '👑/💻/administrarMenu', component: AdministrarMenuComponent},
  {path: '👑/💻/crearMenus', component: CrearMenuComponent},
  {path: 'login', component: LoginComponent},
  //Facturas
  {path:'administrador/facturas',component: CrearFacturaComponent},
  {path: 'administrador/facturas/generarFacturas', component: GenerarFacturaComponent},
  {path:'administrador/home',component:HomeAdministradorComponent},
  {path: 'validarUsuario', component: ValidarPaginaComponent},
  {path: 'administrador/factura/cargarFacturas',component:CargarFacturaComponent},
  {path: 'administrador/factura/cobrarFactura',component:CobroFacturaComponent},
  {path: '**', component: ValidarPaginaComponent}

];

export const appRoutingProviders: any[] = [];
export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);
