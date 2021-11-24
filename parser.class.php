<!--
        E' importante che la struttura della tabella del file CSV rimanga invariata;
        [2 colonne, N righe, delimitatore = ","]
-->
<?php

class parser
{

    function parsefile($file)
    {
        $skip = 1;
        $numeri = new numeri();
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if($skip<=0)
                {
                    $id = $data[0];
                    $number = $data[1];
                    $check = $numeri->checkNumber($number);
                    if($check==0)
                    {
                        $numeri->insertRow($id, $number, $check);
                    }
                    elseif($check==2)
                    {
                        $number = $numeri->correctNumber($number);
                        $numeri->insertRow($id, $number, $check);
                    }
                    else
                    {
                        $numeri->insertRow($id, $number, $check);
                    }
                }
                $skip--;
            }
        }
    }

    function parsenumber($number)
    {
        $numeri = new numeri();
        $check = $numeri->checkNumber($number);
        if($check==0)
        {
            $numeri->insertRow(null, $number, $check);
        }
        elseif($check==2)
        {
            $number = $numeri->correctNumber($number);
            $numeri->insertRow(null, $number, $check);
        }
        else
        {
            $numeri->insertRow(null, $number, $check);
        }
    }
}

?>