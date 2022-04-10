<?php

class myTools {

    public function main($arg, $opt)
    {
        system('clear');
        if(in_array('-h', $arg)) {
            echo "\n\n=============================================================\n";
            echo "Petunjuk Menjalankan Script";
            echo "\n=============================================================\n\n";
            echo "1. Gunakan php , contoh: php mytools-wandi.php\n";
            echo "2. Gunakan -h untuk melihat petunjuk penggunaan \n";
            echo "3. Gunakan -f untuk menentukan nama file, bersifat wajib diisi, misal php mytools-wandi.php -f /var/log/nginx/access.log \n";
            echo "4. Gunakan -t untuk menentukan format data mytools-wandi.php -f /var/log/nginx/access.log -t json\n";
            echo "5. Gunakan -o untuk menentukan format data mytools-wandi.php -f /var/log/nginx/access.log -o /var/www/satu.json\n\n\n\n";
        } else {
            if(!in_array('-f', $arg)) { 
                echo "Gunakan -f untuk menentukan nama file, bersifat wajib diisi\n\n";
            } else {
                if(in_array('-t', $arg)) { 
                    if(!isset($opt['t'])) {
                        self::plainText($opt['f'], $arg);
                    }else {
                        if(strtolower($opt['t']) == 'json') {
                            self::json($opt, $arg);
                        }else {
                            self::plainText($opt, $arg);
                        }
                    }
                } else {
                    self::plainText($opt, $arg);
                }
            }
        }
    }

    public static function plainText($opt, $arg)
    {
        if(in_array('-o', $arg)) { 
            if(!isset($opt['o'])) {
                echo("Anda belum menentukan tempat penyimpanan file\n\n");
            }else {
                $response = self::handleFile($opt['f']);
                $myfile = fopen($opt['o'], "w");
                foreach($response as $key => $val) {
                    fwrite($myfile, $val['data']);
                }
            }
        }else {
            $response = self::handleFile($opt['f']);
            $myfile = fopen("conversi.txt", "w");
            foreach($response as $key => $val) {
                fwrite($myfile, $val['data']);
            }
        }
        fclose($myfile);
        echo "Konversi data berhasil \n \n";
    }

    public static function json($opt, $arg)
    {
        if(in_array('-o', $arg)) { 
            if(!isset($opt['o'])) {
                echo("Anda belum menentukan tempat penyimpanan file\n\n");
            }else {
                $response = self::handleFile($opt['f']);
                $myfile = fopen($opt['o'], "w");
                fwrite($myfile, json_encode($response));
                fclose($myfile);
                echo "Konversi data berhasil \n \n";
            }
        }else {
            $response = self::handleFile($opt['f']);
            $myfile = fopen("conversi.json", "w");
            fwrite($myfile, json_encode($response));
            fclose($myfile);
            echo "Konversi data berhasil \n \n";
        }
    }

    public static function handleFile($filename)
    {
        $handle = fopen($filename,'r') or die ('File opening failed');
        $result = [];
        while (!feof($handle)) {
            $row = fgets($handle);
            $row = str_replace('"','', $row);
            $result[]['data'] = $row;
        }
        fclose($handle);
        return $result;
    }
}

$arg = $GLOBALS['argv'];
$opt = getopt("h:f:t:o:");

$my = new MyTools;
$my->main($arg, $opt);

?>