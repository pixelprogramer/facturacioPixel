<?PHP

use Symfony\Component\Yaml\Yaml;


class conexPGSeguridad
{
    private $validacionConexion;

    function __construct()
    {
        $config = Yaml::parseFile(__DIR__ . './../../Config/config.yml');
        $this->validacionConexion = pg_connect("host=localhost dbname=acueducto user=postgres password=pixelprogramer123 options='--client_encoding=UTF8'") or die('NO HAY CONEXION: ' . pg_last_error());
        pg_client_encoding($this->validacionConexion);
    }

    public function getConexion()
    {
        return $this->validacionConexion;
    }
    public function consultaComplejaNorAso($sql)
    {
        $result = pg_query($this->validacionConexion, $sql);
        if (pg_num_rows($result)>0)
        {
            return pg_fetch_assoc($result);
        }else
        {
            return 0;
        }

    }
    public function consultaComplejaNor($sql)
    {
        $result = pg_query($this->validacionConexion, $sql);

        return $result;
    }
    public function consultaComplejaAso($sql)
    {
        $result = pg_query($this->validacionConexion, $sql);
        if (pg_num_rows($result)>0)
        {
            while ($row = pg_fetch_assoc($result)) {
                $data[] = $row;
            }
        }else
        {
            $data=0;
        }

        return $data;
    }

    public function consultaSimple($sql)
    {
        pg_query($this->validacionConexion, $sql);
    }
}

