import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";

@Component({
  selector: 'app-home-administrador',
  templateUrl: './home-administrador.component.html',
  styleUrls: ['./home-administrador.component.scss'],
  providers: [ElementsService]
})
export class HomeAdministradorComponent implements OnInit {

  constructor(private _ElementService:ElementsService) { }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('HomeAdministradorComponent');
  }

}
