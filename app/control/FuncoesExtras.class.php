<?php
class FuncoesExtras
{    
    public static function valorPorExtenso( $valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false )
    {
        $singular = null;
        $plural = null;

        if ( $bolExibirMoeda )
        {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        }
        else
        {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        }

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");

        if ( $bolPalavraFeminina )
        {        
            if ($valor == 1) 
            {
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            }
            else 
            {
                $u = array("", "um", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            }            
            
            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas","quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");    
        }

        $z = 0;

        $valor = number_format( $valor, 2, ".", "." );
        $inteiro = explode( ".", $valor );

        for ( $i = 0; $i < count( $inteiro ); $i++ ) 
        {
            for ( $ii = mb_strlen( $inteiro[$i] ); $ii < 3; $ii++ ) 
            {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count( $inteiro ) - ($inteiro[count( $inteiro ) - 1] > 0 ? 1 : 2);
        for ( $i = 0; $i < count( $inteiro ); $i++ )
        {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count( $inteiro ) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ( $valor == "000")
                $z++;
            elseif ( $z > 0 )
                $z--;
                
            if ( ($t == 1) && ($z > 0) && ($inteiro[0] > 0) )
                $r .= ( ($z > 1) ? " de " : "") . $plural[$t];
                
            if ( $r )
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        $rt = mb_substr( $rt, 1 );

        return($rt ? trim( $rt ) : "zero");
    }
    
    public static function dataPorExtenso($data="")
    {
        setlocale(LC_TIME, 'portuguese'); 
        date_default_timezone_set('America/Sao_Paulo'); 
        
        if (empty(trim($data)))
        {    
            $data = date('Y-m-d');
        }
        
        return strftime("%d de %B de %Y", strtotime($data));
        
    }
    
    public static function retiraFormatacao($string)
    {
        $string = str_replace('.','',$string);
        $string = str_replace(',','',$string);
        $string = str_replace('-','',$string);
        $string = str_replace('/','',$string);
        $string = str_replace('(','',$string);
        $string = str_replace(')','',$string);
        return $string;
    }
    
    public static function retiraFormatacaoMoeda($valor)  {
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
        return $valor;
    }
    
    public static function dateDif($data1, $data2)
    {
        $data1 = new DateTime($data1);
        $data2 = new DateTime($data2);
        
        $intervlo = $data1->diff($data2);
        
        $retorno = '';
        
        if ($intervlo->y > 0)
        {
            $retorno = $intervlo->y ;
            $retorno .= $intervlo->y == 1 ? ' ano' : ' anos';
        }
        
        if ($intervlo->m > 0)
        {
            $retorno .= $retorno == '' ? $intervlo->m : ', ' . $intervlo->m;
            $retorno .= $intervlo->m == 1 ? ' mês' : ' meses';
        }
        
        if ($intervlo->d > 0)
        {
            $retorno .= $retorno == '' ? $intervlo->d : ' e ' . $intervlo->d;
            $retorno .= $intervlo->d == 1 ? ' dia' : ' dias';
        }
        
        return $retorno;
    }
    
    public static function dateDifDias($data1, $data2)
    {
        $data = explode('/', $data1);
        $data1 = mktime(0, 0, 0, $data[1], $data[0], $data[2]);
        
        $data = explode('/', $data2);
        $data2 = mktime(0, 0, 0, $data[1], $data[0], $data[2]);
        
        $diferenca = $data2 - $data1;
        
        $dias = (int)floor( $diferenca / (60 * 60 * 24));
        
        return $dias * -1;
    }
    
    public static function geraTimestamp($data) 
    {
        $partes = explode('/', $data);
        return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
    }
    
    public static function tirarAcentos($string)
    {
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","(ç)","(Ç)","/(ñ)/","/(Ñ)/","(nº)","(Nº)"),explode(" ","a A e E i I o O u U c C n N n N"),$string);
    }
    
    public static function hexToStr($hex)
    {
        // Remove spaces if the hex string has spaces
        $hex = str_replace(' ', '', $hex);
        return hex2bin($hex);
    }
    
    public static function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++)
        {
            if($mask[$i] == '#')
            {
                if(isset($val[$k]))
                    $maskared .= $val[$k++];
            }
            else
            {
                if(isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
}
?>