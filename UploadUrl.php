<?php

/**
 * Created by PhpStorm.
 * User: Henry
 * Date: 2017/11/26
 * Time: 9:17
 * 功能说明，通过url下载图片
 */
class UploadUrl{
    private $url;
    private $fileName;
    private $save_dir;

    /**
     * UploadUrl constructor.
     * @param $url
     * @param string $save_dir
     * @param string $fileName
     * @param int $type
     */
    public function __construct($url, $save_dir = '', $fileName = '', $type=0){
        $this->url = $url;
        $this->fileName = $fileName;
        $this->save_dir = $save_dir;
    }

    public  function getImage(){
        // 判断url是否为空
        if (trim($this->url) == ''){
            return ['file_name'=>'','save_path'=>'','error'=>'url不能为空'];
        }
        // 判断保存路径是否为空
        if(trim($this->save_dir) == ''){
            $this->save_dir = './';
        }
        //保存文件名
        if(trim($this->fileName) == ''){
            $ext = strrchr($this->url, '.');
            if(!in_array($ext, ['.gif','.jpg', '.png','.jpg' ])){
                return ['file_name'=>'','save_path'=>'','error'=>'请求的不是图片地址'];
            }
             $tmp_name = sprintf('%010d',time() - 946656000)
            . sprintf('%03d', microtime() * 1000)
            . sprintf('%04d', mt_rand(0,9999));
            $this->fileName = $tmp_name.$ext;
        }
        if(0 !== strrpos($this->save_dir,'/')){
            $this->save_dir .= '/';
        }
        //创建保存目录
        if(!file_exists($this->save_dir) && !mkdir($this->save_dir,0777,true)){
            return array('file_name'=>'','save_path'=>'','error'=>'文件目录创建失败');
        }
        //获取图片
        $imgInfo = $this->getHttpUrl($this->url);
        //var_dump($imgInfo);
        //文件大小
        $fp2=@fopen($this->save_dir.$this->fileName,'a');
        fwrite($fp2,$imgInfo);
        fclose($fp2);
        unset($imgInfo,$this->url);
        return ['file_name'=>$this->fileName,'save_path'=>$this->save_dir.$this->fileName,'error'=>0];
    }

    /**
     * @param $url url
     * @param string $type post or get
     * @param array $data []
     * @return mixed
     */
    private function getHttpUrl($url, $type = 'get', $data = [])
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if($type == 'post'){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}