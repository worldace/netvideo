<?php

abstract class �񋓌^{
    private $�l;

    public function __construct($����){
        $ref = new ReflectionObject($this);
        if(!in_array($����, $ref->getConstants(), true)){
            throw new Exception("[$����]��" . get_class($this) . "�N���X�ɒ�`����Ă��܂���");
        }
        $this->�l = $����;
    }

    final public function __toString(){
        return (string)$this->�l;
    }

    final public function get(){
        return $this->�l;
    }
}
