import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CargarRamalesComponent } from './cargar-ramales.component';

describe('CargarRamalesComponent', () => {
  let component: CargarRamalesComponent;
  let fixture: ComponentFixture<CargarRamalesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CargarRamalesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CargarRamalesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
