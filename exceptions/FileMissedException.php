<?
namespace Cart;
class FileMissedException extends \Exception{

    protected string $response_type;

    public function __construct(?string $message, string $response_type){
        $this->message = $message;
        $this->response_type = $response_type;
    }

    public function response():void{
    _response()
        ->setType($this->response_type)
        ->setBody($this->message);
    }
}