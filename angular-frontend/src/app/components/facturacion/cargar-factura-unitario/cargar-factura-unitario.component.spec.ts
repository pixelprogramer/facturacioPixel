import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CargarFacturaUnitarioComponent } from './cargar-factura-unitario.component';

describe('CargarFacturaUnitarioComponent', () => {
  let component: CargarFacturaUnitarioComponent;
  let fixture: ComponentFixture<CargarFacturaUnitarioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CargarFacturaUnitarioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CargarFacturaUnitarioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
