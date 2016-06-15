<?php

class hh
{
    private $token;
    private $resumeId;

    public function __construct($token, $resumeId)
    {
        $this->token = $token;
        $this->resumeId = $resumeId;
    }

    public function resumeUpdate()
    {
       return json_decode( $this->execute("/resumes/$this->resumeId/publish"));
    }

    private function execute($method)
    {
        $ch = curl_init('https://api.hh.ru' . $method);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $this->token));
        curl_setopt($ch, CURLOPT_USERAGENT, 'ResumeUpdate');
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}

?>