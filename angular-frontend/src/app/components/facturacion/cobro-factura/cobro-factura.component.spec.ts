import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CobroFacturaComponent } from './cobro-factura.component';

describe('CobroFacturaComponent', () => {
  let component: CobroFacturaComponent;
  let fixture: ComponentFixture<CobroFacturaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CobroFacturaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CobroFacturaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
