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
     * $pkcs7Sign
     *
     * @var string
     **/
    private $pkcs7Sign = null;
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
     * $pkcs7SignFile
     *
     * @var string
     **/
    private $pkcs7SignFile = null;
    
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
            $this->hashExecute();
            if (CLEANUP_AFTER_SIGN) {
                $this->deleteFile($this->dataFile);
                $this->deleteFile($this->hashFile);
            }
        }
        return $this->getHash();
    }
    /**
     * hashExecute
     *
     * @return bool
     * @author Sergey
     **/
    private function hashExecute()
    {
        if (is_readable($this->dataFile)) {
            $this->hashFile = HASH_DIR . DIRECTORY_SEPARATOR . $this->fileName 
                      . '.' . DATA_FILE_EXTENSION . '.' . HASH_FILE_EXTENSION;
            exec(CRYPTCP_DIR . DIRECTORY_SEPARATOR . CRYPTCP_FILENAME 
               . ' -hash -dir "' . HASH_DIR . '" "' . $this->dataFile . '"');
            if (true) {
                if (($hash = file_get_contents($this->hashFile)) === false)
                    return false;
                $this->hash = base64_encode($hash);
                return true;
           }
        }
    }
    /**
     * pureSign
     *
     * @return string
     * @author Sergey
     **/
    public function pureSign()
    {
        if (is_readable($this->dataFile) || $this->createFile()) {
            $this->pureSignExecute();
            if (CLEANUP_AFTER_SIGN) {
                $this->deleteFile($this->dataFile);
                $this->deleteFile($this->signFile);
            }
        }
        return $this->getPureSign();
    }
    /**
     * pureSignExecute
     * 
     * Use csptest util to create 512 bits sign
     * Note: csptest util is work with container
     *
     * @return bool
     * @author Sergey
     **/
    private function pureSignExecute()
    {
        if (is_readable($this->dataFile)) {
            $this->signFile = SIGN_DIR . DIRECTORY_SEPARATOR . $this->fileName 
                            . '.' . DATA_FILE_EXTENSION 
                            . '.' . SIGN_FILE_EXTENSION;
            exec(CSPTEST_DIR . DIRECTORY_SEPARATOR . CSPTEST_FILENAME 
               . ' -keyset -sign GOST' 
               . ' -in "' . $this->dataFile. '"' 
               . ' -out "' . $this->signFile . '"' 
               . ' -container "' . CSPTEST_CONTAINER_NAME . '"');
            if (true) {
                if (($sign = file_get_contents($this->signFile)) === false)
                    return false;
                $this->sign = base64_encode($sign);
                return true;
           }
        }
    }
    /**
     * pkcs7Sign
     *
     * @return string
     * @author Sergey
     **/
    public function pkcs7Sign()
    {
        if (is_readable($this->dataFile) || $this->createFile()) {
            $this->pkcs7SignExecute();
            if (CLEANUP_AFTER_SIGN) {
                $this->deleteFile($this->dataFile);
                $this->deleteFile($this->pkcs7SignFile);
            }
        }
        return $this->getPKCS7Sign();
    }
    /**
     * pkcs7SignExecute
     * 
     * Use csptest util to generate PKCS#7 signed message, then extract last 64 
     * bytes and invert it
     * Note: csptest util is work with ?
     *
     * @return bool
     * @author Sergey
     **/
    private function pkcs7SignExecute()
    {
        if (is_readable($this->dataFile)) {
            $this->pkcs7SignFile = SIGN_PKCS7_DIR . DIRECTORY_SEPARATOR 
                                 . $this->fileName 
                                 . '.' . DATA_FILE_EXTENSION 
                                 . '.' . SIGN_PKCS7_FILE_EXTENSION;
            // $this->pkcs7SignExpFile = SIGN_PKCS7_DIR . DIRECTORY_SEPARATOR 
            //                         . 'exp_'
            //                         . $this->fileName 
            //                         . '.' . DATA_FILE_EXTENSION 
            //                         . '.' . SIGN_PKCS7_FILE_EXTENSION;
            exec(CSPTEST_DIR . DIRECTORY_SEPARATOR . CSPTEST_FILENAME 
               . ' -sfsign -sign -alg GOST' 
               . ' -in "' . $this->dataFile. '"' 
               . ' -out "' . $this->pkcs7SignFile . '"' 
               . ' -detached'
               // . ' -base64'
               . ' -my "' . CSPTEST_DNAME . '"');
            if (true) {
                if (($pkcs7Sign = file_get_contents($this->pkcs7SignFile)) 
                    === false
                )
                    return false;
                
                $pkcs7Sign = strrev(substr($pkcs7Sign, -64, 64));
                
                // if (file_put_contents($this->pkcs7SignExpFile, $pkcs7Sign) 
                //     === false
                // )
                //     return false;
                
                $this->pkcs7Sign = base64_encode($pkcs7Sign);
                return true;
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
     * getFileName
     *
     * @return string
     * @author Sergey
     **/
    public function getFileName()
    {
        return $this->fileName;
    }
    /**
     * getSign
     *
     * @return string
     * @author Sergey
     **/
    public function getSignMsg()
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
     * getPureSign
     *
     * @return string
     * @author Sergey
     **/
    public function getPureSign()
    {
        return $this->sign;
    }
    /**
     * getPKCS7Sign
     *
     * @return string
     * @author Sergey
     **/
    public function getPKCS7Sign()
    {
        return $this->pkcs7Sign;
    }
    
    /**
     * getCertificate
     *
     * @return string
     * @author Sergey
     **/
    public function getCertificate()
    {
        if (($cert = file_get_contents(CERT_DIR . DIRECTORY_SEPARATOR 
                                     . CERT_FILENAME)) === false
        )
            return '';
        return base64_encode($cert);
    }
} // END class CryptoCP
