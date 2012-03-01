<?php
/**
 * CryptoCP
 *
 * @author Sergey
 * @copyright Anlab, Sergey S., 1 марта, 2012
 * @package cryptocp
 **/
class CryptoCP
{
    /**
     * $data
     *
     * @var string
     **/
    private $data;
    /**
     * $sign
     *
     * @var string
     **/
    private $sign = null;
    /**
     * $fileName
     *
     * @var string
     **/
    private $fileName;
    /**
     * $dataFile
     *
     * @var string
     **/
    private $dataFile = null;
    /**
     * $signFile
     *
     * @var string
     **/
    private $signFile = null;
    /**
     * __construct
     *
     * @access public
     * @return void
     * @author Sergey
     **/
    public function __construct($string = null) {
        $this->data = $string;
    }
    /**
     * sign
     *
     * @return string
     * @author Sergey
     **/
    public function sign()
    {
        if ($this->createFile()) {
            $this->signFile();
            if (CLEANUP_AFTER_SIGN) {
                $this->deleteFile($this->dataFile);
                $this->deleteFile($this->signFile);
            }
        }
        return $this->getSign();
    }
    /**
     * signFile
     *
     * @return bool
     * @author Sergey
     **/
    private function signFile()
    {
        if (is_readable($this->dataFile)) {
            $this->signFile = FILES_DIR . '\\' . $this->fileName . '.' 
                            . SIGN_FILE_EXTENSION;
            echo CRYPTOCP_DIR . '\\' . CRYPTOCP_FILENAME . ' -sign -f "' 
               . CERT_DIR . '\\' . CERT_FILENAME . '" -pin "' . CERT_PASSWORD 
               . '" "' . $this->dataFile . '" "' . $this->signFile . '"';
            if (true) {
                if ((@$sign = file_get_contents($this->signFile)) === false)
                    return false;
                $this->sign = $sign;
           }
        }
    }
    /**
     * createFile
     *
     * @return bool
     * @author Sergey
     **/
    private function createFile()
    {
        $this->fileName = $this->generateFileName();
        $this->dataFile = FILES_DIR . '\\' . $this->fileName . '.' 
                        . DATA_FILE_EXTENSION;
        if (file_put_contents($this->dataFile, $this->data) === false)
            return false;
        return true;
    }
    /**
     * deleteFile
     *
     * @return bool
     * @author Sergey
     **/
    private function deleteFile($file = null)
    {
        if ($file === null) {
            $file = $this->dataFile;
        }
        return unlink($file);
    }
    /**
     * generateFileName
     *
     * @return string
     * @author Sergey
     **/
    private function generateFileName()
    {
        return date('YmdHisu') . '_' . (rand() + 10000);
    }
    
    /**
     * setData
     *
     * @param string $string
     * @return mixed
     * @author Sergey
     **/
    public function setData($string)
    {
        $this->data = $string;
        return $this;
    }
    /**
     * getData
     *
     * @return string
     * @author Sergey
     **/
    public function getData()
    {
        return $this->data;
    }
    /**
     * getSign
     *
     * @return string
     * @author Sergey
     **/
    public function getSign()
    {
        return $this->sign;
    }
} // END class CryptoCP
