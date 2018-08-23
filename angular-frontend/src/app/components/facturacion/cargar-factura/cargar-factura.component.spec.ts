import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CargarFacturaComponent } from './cargar-factura.component';

describe('CargarFacturaComponent', () => {
  let component: CargarFacturaComponent;
  let fixture: ComponentFixture<CargarFacturaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CargarFacturaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CargarFacturaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
