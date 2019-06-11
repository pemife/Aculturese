<?php
$js = <<<EOF

var esAmigo = $puedeVerAmigosJS;

$('#enlace').click(function(e){
  
});

EOF;
$this->registerJs($js);
?>

<div>
  <p>Para aceptar la invitacion de amistad pulsa <a id="enlace">aqui</a></p>
</div>
