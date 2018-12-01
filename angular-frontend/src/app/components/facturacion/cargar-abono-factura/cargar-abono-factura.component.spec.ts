import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CargarAbonoFacturaComponent } from './cargar-abono-factura.component';

describe('CargarAbonoFacturaComponent', () => {
  let component: CargarAbonoFacturaComponent;
  let fixture: ComponentFixture<CargarAbonoFacturaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CargarAbonoFacturaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CargarAbonoFacturaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
