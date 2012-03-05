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
     * $signMsg
     *
     * @var string
     **/
    private $signMsg = null;
    /**
     * $sign
     *
     * @var string
     **/
    private $sign = null;
    /**
     * $hash
     *
     * @var string
     **/
    private $hash = null;
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
     * $signMsgFile
     *
     * @var string
     **/
    private $signMsgFile = null;
    /**
     * $signFile
     *
     * @var string
     **/
    private $signFile = null;
    /**
     * $hashFile
     *
     * @var string
     **/
    private $hashFile = null;
    
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
        if (is_readable($this->dataFile) || $this->createFile()) {
            $this->signFile();
            if (CLEANUP_AFTER_SIGN) {
                $this->deleteFile($this->dataFile);
                $this->deleteFile($this->signMsgFile);
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
            $this->signMsgFile = SIGN_MSG_DIR . DIRECTORY_SEPARATOR 
                               . $this->fileName 
                               . '.' . SIGN_MSG_FILE_EXTENSION;
            exec(CRYPTCP_DIR . DIRECTORY_SEPARATOR . CRYPTCP_FILENAME 
               . ' -sign -f "' . CERT_DIR . DIRECTORY_SEPARATOR . CERT_FILENAME
               . '" -pin "' . CERT_PASSWORD . '" "' . $this->dataFile . '" "' 
               . $this->signMsgFile . '"');
            if (true) {
                if ((@$signMsg = file_get_contents($this->signMsgFile)) 
                    === false
                )
                    return false;
                $this->signMsg = $signMsg;
                return true;
           }
        }
    }
    /**
     * hash
     *
     * @return string
     * @author Sergey
     **/
    public function hash()
    {
        if (is_readable($this->dataFile) || $this->createFile()) {
            $this->hashFile();
            if (CLEANUP_AFTER_SIGN) {
                $this->deleteFile($this->dataFile);
                $this->deleteFile($this->hashFile);
            }
        }
        return $this->getHash();
    }
    /**
     * hashFile
     *
     * @return bool
     * @author Sergey
     **/
    private function hashFile()
    {
        if (is_readable($this->dataFile)) {
            $this->hashFile = HASH_DIR . DIRECTORY_SEPARATOR . $this->fileName 
                      . '.' . DATA_FILE_EXTENSION . '.' . HASH_FILE_EXTENSION;
            // cryptcp -hash -dir hashes test.txt
            exec(CRYPTCP_DIR . DIRECTORY_SEPARATOR . CRYPTCP_FILENAME 
               . ' -hash -dir "' . HASH_DIR . '" "' . $this->dataFile . '"');
            if (true) {
                if (($hash = file_get_contents($this->hashFile)) === false)
                    return false;
                $this->hash = base64_encode($hash);
           }
        }
    }
    /**
     * signf
     *
     * @return string
     * @author Sergey
     **/
    public function signf()
    {
        if (is_readable($this->dataFile) || $this->createFile()) {
            $this->signfFile();
            if (CLEANUP_AFTER_SIGN) {
                $this->deleteFile($this->dataFile);
                $this->deleteFile($this->File);
            }
        }
        return $this->getSignf();
    }
    /**
     * hashFile
     *
     * @return bool
     * @author Sergey
     **/
    private function signfFile()
    {
        if (is_readable($this->dataFile)) {
            $this->signFile = SIGN_DIR . DIRECTORY_SEPARATOR . $this->fileName 
                            . '.' . DATA_FILE_EXTENSION 
                            . '.' . SIGN_FILE_EXTENSION;
            // cryptcp -signf -dir \signs -f cert.crt d:\*.doc 
            exec(CRYPTCP_DIR . DIRECTORY_SEPARATOR . CRYPTCP_FILENAME 
               . ' -signf -dir "' . SIGN_DIR . '" -f "' . CERT_DIR 
               . DIRECTORY_SEPARATOR . CERT_FILENAME . '" -pin "' 
               . CERT_PASSWORD . '" "' . $this->dataFile . '" -sd' 
               . TSP_ADDRESS);
            if (true) {
                if (($sign = file_get_contents($this->signFile)) === false)
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
        $this->dataFile = FILE_DIR . DIRECTORY_SEPARATOR . $this->fileName 
                        . '.' . DATA_FILE_EXTENSION;
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
        return $this->signMsg;
    }
    /**
     * getHash
     *
     * @return string
     * @author Sergey
     **/
    public function getHash()
    {
        return $this->hash;
    }
    /**
     * getSign
     *
     * @return string
     * @author Sergey
     **/
    public function getSignf()
    {
        return $this->sign;
    }
} // END class CryptoCP
