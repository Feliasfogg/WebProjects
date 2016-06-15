<!--GET /me HTTP/1.1-->
<!--User-Agent: MyApp/1.0 (my-app-feedback@example.com)-->
<!--Host: api.hh.ru-->
<!--Accept: */*-->
<!--Authorization: Bearer JSGR1MUJ52K677MO1JCL6K7DMVBIIVMCPP5R4K23ITDISFG713SD2QHTFV8MJ7LQ-->
<?php
include 'HhClass.php';

$token = "PDVLBR7QSBSOQ16H45TO43HMUSFVK3M6M42TC6FRJVM2D3LJIB0VP6NP17KG6PHB";

$resumeId = "bf427a50ff026747660039ed1f366255465636";

$hh = new hh($token, $resumeId);
$data = $hh->resumeUpdate();

if($data->errors){
    var_dump($data);
    $message = "Error:\n type=".$data->errors[0]->type."\n value=".$data->errors[0]->value."\n description=".$data->description;
    mail('good-1991@mail.ru', 'ResumeUpdate Error', $message);
}
?>