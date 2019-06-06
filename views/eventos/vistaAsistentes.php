<div id="tablaAsistentes">
  <table class="table table-striped table bordered">
    <tr>
      <th>Asistentes: (<?= count($listaAsistentes) ?>)</th>
      <?php foreach ($listaAsistentes as $asistente) { ?>
        <tr>
          <td id="asistente<?= $asistente->id ?>"><?= $asistente->nombre ?></td>
        </tr>
      <?php
      }
      // TODO: Controlar limite de asistentes mostrados
      ?>
    </tr>
  </table>
</div>
