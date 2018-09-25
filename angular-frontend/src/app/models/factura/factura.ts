export class Factura{
  constructor(
    public id_factura: any,
    public observacion_factura: any,
    public codigo_medidor_factura: any,
    public direccion_factura: any,
    public numero_tapa_factura: any,
    public id_usuario_factura_fk: any,
    public id_configuracion_factura_fk: any,
    public estado_factura: any,
    public fk_ramal_factura_usuario_id: any
  ){}
}
