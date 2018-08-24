export class Registro_factura{
  constructor(
    public id_registro_factura: any,
    public codigo_registro_factura: any,
    public ruta_codigo_barras_factura: any,
    public estado_factura: any,
    public fecha_creacion_factura: any,
    public fecha_pago_factura: any,
    public fecha_inicial_facturado: any,
    public id_factura_registro_factura_fk: any,
    public fecha_final_factura: any,
    public json_tarifas: any,
    public id_usuario_registro_factura_fk: any,
    public json_cargue_factura: any
  ){}
}
