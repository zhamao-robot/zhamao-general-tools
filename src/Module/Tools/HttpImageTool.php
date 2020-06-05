<?php


namespace Module\Manager;


use Co;
use Framework\ZMBuf;
use ZM\Annotation\Http\RequestMapping;
use ZM\Annotation\Swoole\OnStart;
use ZM\Utils\ZMRequest;
use ZM\Utils\ZMUtil;

class HttpImageTool
{
    const FILE_PATH = ZM_DATA . "images/";
    /**
     * @OnStart()
     */
    public function onWorkerStart(){
        @mkdir(self::FILE_PATH);
    }
    /**
     * @param $param
     * @RequestMapping("/images/{file_name}")
     */
    public function innerImage($param) {
        $filename = self::FILE_PATH;
        $param_name = $param["file_name"];
        $param_name = strtolower($param_name);
        if (mb_strpos($param_name, "..") !== false) {
            ctx()->getResponse()->status(404);
            ctx()->getResponse()->end();
            return;
        }
        $exp = explode(".", $param_name);
        if (($type = array_pop($exp)))
            ctx()->getResponse()->header("Content-Type", ZMBuf::config("file_header")[$type] ?? "text/html");
        ctx()->getResponse()->end(Co::readFile($filename.$param_name));
    }

    public static function downloadImageFromCQ(&$msg) {
        $files = [];
        $rec = 0;
        while(($cq = ZMUtil::getCQ($msg)) !== null) {
            ++$rec;
            if($rec > 5) break;
            if ($cq === null) return [];
            if ($cq["type"] == "image") {
                $url = $cq["params"]["url"] ?? null;
                if ($url === null) return [];
                $file = ZMRequest::get($url);
                if($file === false) continue;
                file_put_contents(self::FILE_PATH . $cq["params"]["file"], $file);
                $cqs = mb_strpos($msg, "[");
                $cqe = mb_strpos($msg, "]");
                $sub = mb_substr($msg, $cqs, $cqe + 1);
                $msg = str_replace($sub, "{{img:" . $cq["params"]["file"] . "}}", $msg);
                $files[]=$cq["params"]["file"];
            }
        }
        return $files;
    }
}
