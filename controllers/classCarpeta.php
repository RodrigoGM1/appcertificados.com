<?php

class Carpeta{
    public function crear(string $nombreCarpeta){
        $direcionE = "../EvidenciasDoc/".$nombreCarpeta;
        $direcionC = "../CertificadosDoc/".$nombreCarpeta;
        $direcionR = "../ReportesDoc/".$nombreCarpeta;
        $direcionM = "../EvidenciasSub/Manifiestos/".$nombreCarpeta;
        $direcionN = "../EvidenciasSub/Notas/".$nombreCarpeta;

        mkdir($direcionC, 0777, true);
        mkdir($direcionE, 0777, true);
        mkdir($direcionR, 0777, true);
        mkdir($direcionM, 0777, true);
        mkdir($direcionN, 0777, true);
    }

    public function modificar(string $nuevaCarpeta, string $viejaCarpeta){
        $direcionEAnt = "../EvidenciasDoc/".$viejaCarpeta;
        $direcionCAnt = "../CertificadosDoc/".$viejaCarpeta;
        $direcionRAnt = "../ReportesDoc/".$viejaCarpeta;
        $direcionMAnt = "../EvidenciasSub/Manifiestos/".$viejaCarpeta;
        $direcionNAnt = "../EvidenciasSub/Notas/".$viejaCarpeta;

        $direcionC = "../CertificadosDoc/".$nuevaCarpeta;
        $direcionE = "../EvidenciasDoc/".$nuevaCarpeta;
        $direcionR = "../ReportesDoc/".$nuevaCarpeta;
        $direcionM = "../EvidenciasSub/Manifiestos/".$nuevaCarpeta;
        $direcionN = "../EvidenciasSub/Notas/".$nuevaCarpeta;

        rename($direcionCAnt, $direcionC);
        rename($direcionEAnt, $direcionE);
        rename($direcionRAnt, $direcionR);
        rename($direcionMAnt, $direcionM);
        rename($direcionNAnt, $direcionN);
    }

    public function eliminar(string $nombreCarpeta){
        $direcionC = "../CertificadosDoc/".$nombreCarpeta;
        $direcionE = "../EvidenciasDoc/".$nombreCarpeta;
        $direcionR = "../ReportesDoc/".$nombreCarpeta;
        $direcionM = "../EvidenciasSub/Manifiestos/".$nombreCarpeta;
        $direcionN = "../EvidenciasSub/Notas/".$nombreCarpeta;

        rmdir($direcionC);
        rmdir($direcionE);
        rmdir($direcionR);
        rmdir($direcionM);
        rmdir($direcionN);
    }
}
