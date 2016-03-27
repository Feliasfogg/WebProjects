<!--GET /me HTTP/1.1-->
<!--User-Agent: MyApp/1.0 (my-app-feedback@example.com)-->
<!--Host: api.hh.ru-->
<!--Accept: */*-->
<!--Authorization: Bearer JSGR1MUJ52K677MO1JCL6K7DMVBIIVMCPP5R4K23ITDISFG713SD2QHTFV8MJ7LQ-->
<?php
include 'HhClass.php';

$token = "KO9F7VK0I96IIR84GL0C6BRL1QJAOTF5LL9S4MKGCV10K94REH5751CHDFAITTDK";

$resumeId = "bf427a50ff026747660039ed1f366255465636";

$hh = new hh($token, $resumeId);
$data = $hh->resumeUpdate();

if($data->errors){
    var_dump($data);
    $message = "Error:\n type=".$data->errors[0]->type."\n value=".$data->errors[0]->value."\n description=".$data->description;
    mail('good-1991@mail.ru', 'ResumeUpdate Error', $message);
}
?>