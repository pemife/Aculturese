<div id="tablaAmigos">
  <table class="table table-striped table bordered">
    <tr>
      <th>Asistentes: (<?= count($listaAmigos) ?>)</th>
      <?php foreach ($listaAmigos as $amigo) { ?>
        <tr>
          <td id="amigo<?= $amigo->id ?>"><?= $amigo->nombre ?></td>
        </tr>
      <?php
      }
      // TODO: Controlar limite de amigos mostrados
      ?>
    </tr>
  </table>
</div>
